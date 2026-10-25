<?php

namespace App\Exports;

use App\Models\RhuAppointment;
use App\Models\RhuClient;
use App\Models\RhuSupply;
use App\Models\RhuTest;
use App\Models\RhuTurnedOver;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
 * Generates dynamic Excel exports based on the department or data category.
 */
class DynamicExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected string $department
    ) {}

    public function collection(): Collection
    {
        return match ($this->department) {
            // Blood department
            'appointments' => RhuAppointment::select('name', 'sub_type', 'date', 'contactNo', 'status')->get(),
            'blood-donation' => RhuClient::select('name', 'sub_type', 'volume', 'date', 'status')->get(),
            'turned_over' => RhuTurnedOver::select('name', 'blood_type', 'date', 'volume')->get(),
            'b_appointments' => RhuAppointment::where('type', 'blood')->select('name', 'sub_type', 'date', 'contactNo', 'status')->get(),
            'b_blood-donation' => RhuClient::where('type', 'blood')->select('name', 'sub_type', 'volume', 'date', 'status')->get(),

            // Vaccination
            'vax_appointments' => RhuAppointment::where('type', 'vaccination')->select('name', 'sub_type', 'dose_number', 'date', 'contactNo', 'status')->get(),
            'vax_blood-donation' => RhuClient::where('type', 'vaccination')->select('name', 'sub_type', 'dose_number', 'date', 'status')->get(),

            // Consultation
            'con_appointments' => RhuAppointment::where('type', 'consultation')->select('name', 'sub_type', 'date', 'contactNo', 'status')->get(),
            'con_blood-donation' => RhuClient::where('type', 'consultation')->select('name', 'sub_type', 'date', 'status')->get(),

            // Laboratory
            'lab_appointments' => RhuAppointment::where('type', 'laboratory')->select('name', 'sub_type', 'date', 'contactNo', 'status')->get(),
            'lab_blood-donation' => RhuClient::where('type', 'laboratory')->select('name', 'sub_type', 'result', 'date', 'status')->get(),
            'testList' => RhuTest::select('name', 'price', 'status')->get(),

            // Inventory
            'medicines' => RhuSupply::where('type', 'Medicines')->select('name', 'dosage_f', 'dosage_s', 'location_code', 'quantity')->get(),
            'medicalSupplies' => RhuSupply::where('type', 'Medical Supplies')->select('name', 'batchNo', 'location_code', 'quantity')->get(),
            'medicalEquipments' => RhuSupply::where('type', 'Medical Equipments')->select('name', 'batchNo', 'location_code', 'quantity')->get(),
            'vaccines' => RhuSupply::where('type', 'Vaccines')->select('name', 'expiration_date', 'location_code', 'quantity')->get(),

            // Super admin patient lists
            'laboratory' => RhuClient::where('type', 'laboratory')->select('name', 'sub_type', 'result', 'date', 'status')->get(),
            'consultation' => RhuClient::where('type', 'consultation')->select('name', 'sub_type', 'doctor', 'analysis', 'date', 'status')->get(),
            'vaccination' => RhuClient::where('type', 'vaccination')->select('name', 'sub_type', 'dose_number', 'date', 'status')->get(),
            'blood' => RhuClient::where('type', 'blood')->select('name', 'sub_type', 'volume', 'date', 'status')->get(),

            default => collect(),
        };
    }

    public function headings(): array
    {
        return match ($this->department) {
            'appointments' => ['Patient Name', 'Blood Type', 'Date', 'Contact Number', 'Status'],
            'blood-donation' => ['Name', 'Blood Type', 'Volume', 'Date', 'Status'],
            'turned_over' => ['Blood Bank Name', 'Blood Type', 'Date', 'Volume'],
            'b_appointments' => ['Patient Name', 'Blood Type', 'Date', 'Contact Number', 'Status'],
            'b_blood-donation' => ['Name', 'Blood Type', 'Volume', 'Date', 'Status'],

            'vax_appointments' => ['Patient Name', 'Vaccine Type', 'Dose Number', 'Date', 'Contact Number', 'Status'],
            'vax_blood-donation' => ['Patient Name', 'Vaccine Type', 'Dose Number', 'Date', 'Status'],

            'con_appointments' => ['Patient Name', 'Mode of Consultation', 'Date', 'Contact Number', 'Status'],
            'con_blood-donation' => ['Patient Name', 'Mode of Consultation', 'Date', 'Status'],

            'lab_appointments' => ['Patient Name', 'Test Requested', 'Date', 'Contact Number', 'Status'],
            'lab_blood-donation' => ['Patient Name', 'Test Requested', 'Result', 'Date', 'Status'],
            'testList' => ['Test Name', 'Price', 'Status'],

            'medicines' => ['Medicine Name', 'Dosage Form', 'Dosage Strength', 'Location Area Code / Rack Number', 'Quantity'],
            'medicalSupplies' => ['Supply Name', 'Batch / Lot No.', 'Location Area Code / Rack Number', 'Quantity'],
            'medicalEquipments' => ['Equipment Name', 'Batch / Lot No.', 'Location Area Code / Rack Number', 'Quantity'],
            'vaccines' => ['Vaccine Name', 'Expiration Date', 'Location Area Code / Rack Number', 'Quantity'],

            'laboratory' => ['Name', 'Test Requested', 'Result', 'Date', 'Status'],
            'consultation' => ['Name', 'Mode of Consultation', 'Doctor', "Doctor's Analysis", 'Date', 'Status'],
            'vaccination' => ['Name', 'Vaccine Type', 'Dose Number', 'Date', 'Status'],
            'blood' => ['Name', 'Blood Type', 'Volume', 'Date', 'Status'],

            default => [],
        };
    }
}
