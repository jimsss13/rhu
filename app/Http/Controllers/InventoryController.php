<?php

namespace App\Http\Controllers;

use App\Models\RhuMedicine;
use App\Models\RhuSupply;
use App\Models\RhuUser;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Handles inventory department operations: medicines, medical supplies,
 * medical equipment, and vaccine management.
 */
class InventoryController extends Controller
{
    use LogsActivity;

    protected $currentDepartment;

    protected $currentRole;

    public function __construct()
    {
        $this->currentDepartment = session('currentDepartment');
        $this->currentRole = session('currentRole');
    }

    public function reports()
    {
        if (auth()->user()->department !== 'INVENTORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $medicines = RhuSupply::where('type', 'Medicines')->get();
        $medicalSupplies = RhuSupply::where('type', 'Medical Supplies')->get();
        $medicalEquipments = RhuSupply::where('type', 'Medical Equipments')->get();
        $vaccines = RhuSupply::where('type', 'Vaccines')->get();

        return view('inventory.reports', compact('medicalSupplies', 'medicines', 'medicalEquipments', 'vaccines'));
    }

    public function inventory()
    {
        if (auth()->user()->department !== 'INVENTORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        return view('inventory.homepage', [
            'totalMedicines' => RhuSupply::where('type', 'Medicines')->count(),
            'totalSupplies' => RhuSupply::where('type', 'Medical Supplies')->count(),
            'totalEquipments' => RhuSupply::where('type', 'Medical Equipments')->count(),
            'totalVaccines' => RhuSupply::where('type', 'Vaccines')->count(),
            'currentDepartment' => $this->currentDepartment,
        ]);
    }

    public function inventory_staff_management()
    {
        if (auth()->user()->department !== 'INVENTORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $staffs = RhuUser::where('role', 'Admin')
            ->where('department', 'INVENTORY')
            ->get()
            ->map(function ($staff) {
                if ($staff->bday) {
                    $staff->bday = Carbon::parse($staff->bday)->format('Y-m-d');
                }

                return $staff;
            })
            ->sortBy('created_at');

        return view('inventory.staff_account', compact('staffs'));
    }

    // ── Medicines ──────────────────────────────────────────────

    public function medicines()
    {
        if (auth()->user()->department !== 'INVENTORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $medicines = RhuSupply::where('type', 'Medicines')->get()
            ->map(function ($medicine) {
                if ($medicine->expiration_date) {
                    $medicine->expiration_date = Carbon::parse($medicine->expiration_date)->format('Y-m-d');
                }

                return $medicine;
            });

        return view('inventory.medicines', compact('medicines'));
    }

    public function add_medicines(Request $request)
    {
        $validatedAttributes = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies')->ignore($request->id),
            ],
            'dosage_f' => 'nullable|string|max:255',
            'dosage_s' => 'required|string|max:255',
            'location_code' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:99999',
            'expiration_date' => 'nullable|date',
            'end_user' => 'nullable|string|max:255',
        ]);

        $validatedAttributes['type'] = 'Medicines';

        $x = RhuSupply::create($validatedAttributes);

        $medicineRecord = RhuMedicine::where('name', $request->name)->first();

        if (! $medicineRecord) {
            RhuMedicine::create([
                'name' => $request->name,
                'total' => $validatedAttributes['quantity'],
                'current' => $validatedAttributes['quantity'],
            ]);
        } else {
            $medicineRecord->total += $validatedAttributes['quantity'];
            $medicineRecord->current += $validatedAttributes['quantity'];
            $medicineRecord->save();
        }

        $this->logActivity("Added new medicine: {$x->name}");

        return redirect()->back()->with('success', 'New medicine added successfully.');
    }

    public function update_medicines(Request $request, $pId)
    {
        $supplies = RhuSupply::findOrFail($pId);

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies')->ignore($supplies->id),
            ],
            'dosage_f' => 'nullable|string|max:255',
            'dosage_s' => 'required|string|max:255',
            'location_code' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:99999',
            'expiration_date' => 'nullable|date',
            'end_user' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($pId, $validatedData) {
            $supplies = RhuSupply::whereKey($pId)->where('type', 'Medicines')->lockForUpdate()->firstOrFail();
            $medicine = RhuMedicine::where('name', $supplies->name)->lockForUpdate()->first();
            if (! $medicine) {
                throw ValidationException::withMessages(['quantity' => 'Medicine record not found.']);
            }
            $quantity = (int) $validatedData['quantity'];
            $supplies->update(array_merge($validatedData, ['quantity' => $supplies->quantity + $quantity]));
            $medicine->update([
                'name' => $supplies->name, 'current' => $supplies->quantity,
                'total' => $medicine->total + $quantity,
            ]);
        });
        $supplies->refresh();

        $this->logActivity("Updated medicine: {$supplies->name}");

        session()->flash('success', 'Medicine added successfully.');

        return redirect()->back();
    }

    public function update_medicines1(Request $request, $pId)
    {
        $supply = RhuSupply::findOrFail($pId);

        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1|max:99999',
        ]);

        DB::transaction(function () use ($pId, $validatedData) {
            $supply = RhuSupply::whereKey($pId)->where('type', 'Medicines')->lockForUpdate()->firstOrFail();
            $medicine = RhuMedicine::where('name', $supply->name)->lockForUpdate()->first();
            $quantity = (int) $validatedData['quantity'];
            if (! $medicine || $quantity > $supply->quantity || $quantity > $medicine->current) {
                throw ValidationException::withMessages(['quantity' => 'Insufficient stock or missing medicine record.']);
            }
            $supply->decrement('quantity', $quantity);
            $medicine->current -= $quantity;
            $medicine->turned_over += $quantity;
            $medicine->save();
        });

        $this->logActivity("Dispensed medicine: {$supply->name}");

        session()->flash('success', 'Medicine information updated successfully.');

        return redirect()->back();
    }

    public function delete_medicines($id)
    {
        try {
            $supplies = RhuSupply::findOrFail($id);

            if ($supplies->type === 'Medicines') {
                RhuMedicine::where('name', $supplies->name)->delete();
            }

            $this->logActivity("Deleted {$supplies->name} medicine");

            $supplies->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Medical Supplies ──────────────────────────────────────────

    public function medical_supplies()
    {
        if (auth()->user()->department !== 'INVENTORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $medical_supplies = RhuSupply::where('type', 'Medical Supplies')->get();

        return view('inventory.medical_supplies', compact('medical_supplies'));
    }

    public function add_medical_supplies(Request $request)
    {
        $validatedAttributes = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies'),
            ],
            'batchNo' => 'nullable|string|max:255',
            'location_code' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:99999',
        ]);

        $validatedAttributes['type'] = 'Medical Supplies';

        $supplies = RhuSupply::create($validatedAttributes);

        $this->logActivity("Added {$supplies->name}, {$supplies->type}");

        return redirect()->back()->with('success', 'New medical supply added successfully.');
    }

    public function update_medical_supplies(Request $request, $pId)
    {
        $medical_supplies = RhuSupply::findOrFail($pId);

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies')->ignore($medical_supplies->id),
            ],
            'batchNo' => 'nullable|string|max:255',
            'location_code' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:99999',
        ]);

        $medical_supplies->update($validatedData);

        $this->logActivity("Updated {$medical_supplies->name}, {$medical_supplies->type}");

        session()->flash('success', 'Medical Supply information updated successfully.');

        return redirect()->back();
    }

    public function delete_medical_supplies($id)
    {
        try {
            $supplies = RhuSupply::findOrFail($id);

            $this->logActivity("Deleted {$supplies->name}, {$supplies->type}");

            $supplies->delete();

            session()->flash('success', 'Medical supply deleted successfully.');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Medical Equipment ──────────────────────────────────────────

    public function medical_equipments()
    {
        if (auth()->user()->department !== 'INVENTORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $medical_equipments = RhuSupply::where('type', 'Medical Equipments')->get();

        return view('inventory.medical_equipments', compact('medical_equipments'));
    }

    public function add_medical_equipments(Request $request)
    {
        $validatedAttributes = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies'),
            ],
            'batchNo' => 'nullable|string|max:255',
            'location_code' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:99999',
        ]);

        $validatedAttributes['type'] = 'Medical Equipments';

        $supplies = RhuSupply::create($validatedAttributes);

        $this->logActivity("Added {$supplies->name}, {$supplies->type}");

        return redirect()->back()->with('success', 'New medical equipment added!');
    }

    public function update_medical_equipments(Request $request, $pId)
    {
        $medical_equipments = RhuSupply::findOrFail($pId);

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies')->ignore($medical_equipments->id),
            ],
            'batchNo' => 'nullable|string|max:255',
            'location_code' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:99999',
        ]);

        $medical_equipments->update($validatedData);

        $this->logActivity("Updated {$medical_equipments->name}, {$medical_equipments->type}");

        return redirect()->back()->with('success', 'Medical equipment updated!');
    }

    public function delete_medical_equipments($id)
    {
        try {
            $equipments = RhuSupply::findOrFail($id);

            $this->logActivity("Deleted {$equipments->name}, {$equipments->type}");

            $equipments->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Vaccines ──────────────────────────────────────────────

    public function vaccines()
    {
        if (auth()->user()->department !== 'INVENTORY') {
            return redirect('/')->with('error', 'Access denied. You do not have the required permissions.');
        }

        $vaccines = RhuSupply::where('type', 'Vaccines')->get()
            ->map(function ($vaccine) {
                if ($vaccine->expiration_date) {
                    $vaccine->expiration_date = Carbon::parse($vaccine->expiration_date)->format('Y-m-d');
                }

                return $vaccine;
            });

        return view('inventory.vaccines', compact('vaccines'));
    }

    public function add_vaccines(Request $request)
    {
        $validatedAttributes = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies'),
            ],
            'expiration_date' => 'nullable|date',
            'location_code' => 'nullable|string|max:10',
            'quantity' => 'required|integer|min:1|max:99999',
        ]);

        $validatedAttributes['type'] = 'Vaccines';

        $vax = RhuSupply::create($validatedAttributes);

        $this->logActivity("Added {$vax->name}, {$vax->type}");

        return redirect()->back()->with('success', 'New vaccine added successfully.');
    }

    public function update_vaccines(Request $request, $pId)
    {
        $vaccines = RhuSupply::findOrFail($pId);

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rhu_supplies')->ignore($vaccines->id),
            ],
            'expiration_date' => 'nullable|date',
            'location_code' => 'required|string|max:255',
            'quantity' => 'sometimes|required|integer|min:0|max:99999',
        ]);

        $vaccines->update($validatedData);

        $this->logActivity("Updated {$vaccines->name}, {$vaccines->type}");

        session()->flash('success', 'Vaccine updated successfully.');

        return redirect()->back();
    }

    public function delete_vaccines($id)
    {
        try {
            $vaccines = RhuSupply::findOrFail($id);

            $this->logActivity("Deleted {$vaccines->name}, {$vaccines->type}");

            $vaccines->delete();

            session()->flash('success', 'Vaccine deleted successfully.');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
