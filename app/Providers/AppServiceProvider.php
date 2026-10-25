<?php

namespace App\Providers;

use App\Enums\Department;
use App\Models\RhuAppointment;
use App\Models\RhuClient;
use App\Models\RhuUser;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('staff', fn (RhuUser $user) => strtolower($user->role ?? '') === 'admin'
            && in_array($user->status, ['Approved', 'Active'], true)
            && Department::tryFrom($user->department ?? '') !== null
            && $user->department !== Department::Client->value
        );

        Gate::define('manage-user', fn (RhuUser $user, RhuUser $target) => Gate::forUser($user)->allows('staff')
            && (in_array($user->department, ['IT DEPARTMENT', 'SUPER ADMIN'], true)
                || ($target->department === $user->department && strtolower($target->role ?? '') === 'admin'))
        );

        Gate::define('assign-department', fn (RhuUser $user, string $department) => Gate::forUser($user)->allows('staff')
            && Department::tryFrom($department) !== null
            && $department !== Department::Client->value
            && (in_array($user->department, ['IT DEPARTMENT', 'SUPER ADMIN'], true)
                || $department === $user->department)
        );

        Gate::define('manage-appointment', fn (RhuUser $user, RhuAppointment $appointment) => Gate::forUser($user)->allows('staff') && strtolower($user->department) === $appointment->type
        );

        Gate::define('manage-record', fn (RhuUser $user, RhuClient $record) => Gate::forUser($user)->allows('staff') && strtolower($user->department) === $record->type
        );

        Gate::define('export-data', function (RhuUser $user, string $category) {
            $department = match ($category) {
                'appointments', 'blood-donation' => 'SUPER ADMIN',
                'b_appointments', 'b_blood-donation', 'turned_over', 'blood' => 'BLOOD',
                'vax_appointments', 'vax_blood-donation', 'vaccination' => 'VACCINATION',
                'con_appointments', 'con_blood-donation', 'consultation' => 'CONSULTATION',
                'lab_appointments', 'lab_blood-donation', 'testList', 'laboratory' => 'LABORATORY',
                'medicines', 'medicalSupplies', 'medicalEquipments', 'vaccines' => 'INVENTORY',
                default => null,
            };

            return $department !== null && Gate::forUser($user)->allows('staff')
                && in_array($user->department, [$department, 'SUPER ADMIN'], true);
        });
    }
}
