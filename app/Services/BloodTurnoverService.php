<?php

namespace App\Services;

use App\Models\RhuBloodType;
use App\Models\RhuTurnedOver;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BloodTurnoverService
{
    public function save(array $attributes, ?int $id = null): RhuTurnedOver
    {
        return DB::transaction(function () use ($attributes, $id) {
            $record = $id === null ? null : RhuTurnedOver::whereKey($id)->lockForUpdate()->firstOrFail();
            $types = array_unique([$attributes['blood_type'], $record?->blood_type ?? $attributes['blood_type']]);
            $stocks = RhuBloodType::whereIn('blood_type', $types)->orderBy('blood_type')->lockForUpdate()->get()->keyBy('blood_type');

            if ($stocks->count() !== count($types)) {
                throw ValidationException::withMessages(['blood_type' => 'Selected blood type is not available.']);
            }

            if ($record && $record->blood_type === $attributes['blood_type']) {
                $this->adjust($stocks[$record->blood_type], (int) $attributes['volume'] - $record->volume);
            } else {
                if ($record) {
                    $this->adjust($stocks[$record->blood_type], -$record->volume);
                }
                $this->adjust($stocks[$attributes['blood_type']], (int) $attributes['volume']);
            }

            if ($record) {
                $record->update($attributes);

                return $record;
            }

            return RhuTurnedOver::create($attributes);
        });
    }

    public function delete(int $id): RhuTurnedOver
    {
        return DB::transaction(function () use ($id) {
            $record = RhuTurnedOver::whereKey($id)->lockForUpdate()->firstOrFail();
            $stock = RhuBloodType::where('blood_type', $record->blood_type)->lockForUpdate()->firstOrFail();
            $this->adjust($stock, -$record->volume);
            $record->delete();

            return $record;
        });
    }

    private function adjust(RhuBloodType $stock, int $volume): void
    {
        if ($volume > $stock->current || -$volume > $stock->turned_over) {
            throw ValidationException::withMessages(['volume' => 'Insufficient stock or inconsistent turnover totals.']);
        }

        $stock->current -= $volume;
        $stock->turned_over += $volume;
        $stock->save();
    }
}
