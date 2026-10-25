<?php

namespace App\Services;

use App\Models\RhuAppointment;
use App\Models\RhuBloodType;
use App\Models\RhuClient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class AppointmentRecordService
{
    public function record(int $appointmentId, string $type, array $attributes): RhuClient
    {
        $resultPath = null;

        try {
            return DB::transaction(function () use ($appointmentId, $type, $attributes, &$resultPath) {
                $appointment = RhuAppointment::whereKey($appointmentId)
                    ->where('type', $type)
                    ->where('status', 'Approved')
                    ->lockForUpdate()
                    ->first();

                if (! $appointment) {
                    throw ValidationException::withMessages([
                        'appointment_id' => 'Select an approved appointment from this department.',
                    ]);
                }

                unset($attributes['appointment_id']);

                if (($attributes['result'] ?? null) instanceof UploadedFile) {
                    $resultPath = $attributes['result']->store('test_results', 'public');
                    $attributes['result'] = $resultPath;
                }

                $record = RhuClient::create(array_merge($attributes, [
                    'client_id' => $appointment->client_id,
                    'name' => $appointment->name,
                    'type' => $appointment->type,
                    'sub_type' => $appointment->sub_type,
                    'reason' => $appointment->reason,
                    'date' => now(),
                ]));

                $appointment->status = $record->status;
                if ($resultPath !== null) {
                    $appointment->result = $resultPath;
                }
                $appointment->save();

                if ($type === 'blood' && $record->status === 'Completed') {
                    $stock = RhuBloodType::firstOrCreate(['blood_type' => $appointment->sub_type], [
                        'total' => 0, 'current' => 0, 'turned_over' => 0,
                    ]);
                    $stock->incrementEach(['total' => $record->volume, 'current' => $record->volume]);
                }

                return $record;
            });
        } catch (Throwable $e) {
            if ($resultPath !== null) {
                Storage::disk('public')->delete($resultPath);
            }

            throw $e;
        }
    }
}
