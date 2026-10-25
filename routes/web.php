<?php

use App\Exports\DynamicExport;
use App\Http\Controllers\BloodController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ITDepartmentController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\VaccinationController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// ── Public / Client Routes ─────────────────────────────────────

Route::controller(ClientController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/profile/edit', 'edit')->middleware(['auth', 'approved'])->name('profile.edit');
    Route::post('/profile/update', 'update')->middleware(['auth', 'approved'])->name('profile.update');
    Route::get('/edits', 'edits')->middleware(['auth', 'approved'])->name('edits');
    Route::get('/edit_profile', 'edit_profile')->middleware(['auth', 'approved'])->name('edit');
    Route::post('/update_edit_profile', 'update_edit_profile')->middleware(['auth', 'approved'])->name('update_edit_profile');
});

// ── Auth Routes ────────────────────────────────────────────────

Route::controller(RegisteredUserController::class)->group(function () {
    Route::get('/register', 'create');
    Route::post('/register', 'store');
});

Route::controller(SessionController::class)->group(function () {
    Route::get('/login', 'create')->name('login');
    Route::post('/login', 'store');
    Route::post('/logout', 'destroy')->middleware('auth')->name('logout');
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// ── Receipt Routes ─────────────────────────────────────────────

Route::get('/generate-receipt', [ReceiptController::class, 'generateReceipt'])->middleware(['auth', 'approved', 'can:staff']);
Route::get('/generate-image', [ReceiptController::class, 'generateImage'])->middleware(['auth', 'approved', 'can:staff']);

// ── IT Department Routes ───────────────────────────────────────

Route::middleware(['auth', 'approved', 'department:IT DEPARTMENT'])->controller(ITDepartmentController::class)->group(function () {
    Route::get('/it_dept', 'it_dept');
    Route::get('/user_account', 'user_account');
    Route::get('/staff_account', 'staff_account');
    Route::get('/registration_verification', 'registration_verification');
    Route::post('/approve-client/{id}', 'approveClient');
    Route::post('/approve-client-email/{id}', 'email');
    Route::get('/logs', 'logs');
});

// ── Super Admin Routes ─────────────────────────────────────────

Route::middleware(['auth', 'approved', 'department:SUPER ADMIN'])->controller(SuperAdminController::class)->group(function () {
    Route::get('/super_admin', 'super_admin');
    Route::get('/super_patient', 'super_patient');
    Route::get('/announcement', 'announcement');
    Route::post('/add-announcement', 'announcement_store')->name('announcement_store');
    Route::delete('/delete-announcement/{id}', 'deleteAnnouncement');
    Route::put('/update-announcement/{pId}', 'updateAnnouncement');
    Route::get('/super_admin_staff', 'super_admin_staff_account');
    Route::get('/super_admin_appointments', 'appointments');
});

// ── Laboratory Routes ──────────────────────────────────────────

Route::middleware(['auth', 'approved', 'department:LABORATORY'])->controller(LaboratoryController::class)->group(function () {
    Route::get('/laboratory', 'laboratory');
    Route::get('/lab_staff_management', 'lab_staff_management');
    Route::get('/lab_report', 'reports');
    Route::get('/display-lab_appointments', 'display_lab_appointments');
    Route::put('/update-lab_appointments/{pId}', 'update_lab_appointments');

    // Lab tests
    Route::get('/lab_tests', 'lab_tests');
    Route::put('/update-lab_tests/{pId}', 'update_lab_tests');
    Route::post('/add-lab_tests', 'add_lab_tests')->name('add_lab_tests');
    Route::delete('/delete-lab_tests/{id}', 'delete_lab_tests');

    // Patient records
    Route::get('/lab_patient_records', 'lab_patient_records');
    Route::post('/add-lab_patient_records', 'add_lab_patient_records')->name('add_lab_patient_records');

    // Dynamic name
    Route::get('/get-sub-type/{name}', 'getSubType');
});

// ── Consultation Routes ────────────────────────────────────────

Route::middleware(['auth', 'approved', 'department:CONSULTATION'])->controller(ConsultationController::class)->group(function () {
    Route::get('/consultation', 'consultation');
    Route::get('/con_staff_management', 'con_staff_management');
    Route::get('/con_report', 'reports');
    Route::put('/con_refer_reject/{pId}', 'con_refer_reject');

    // Appointments
    Route::get('/display-con_appointments', 'display_con_appointments');
    Route::put('/update-con_appointments/{pId}', 'update_con_appointments');

    // Patient records
    Route::get('/con_patient_records', 'con_patient_records');
    Route::post('/add-con_patient_records', 'add_con_patient_records')->name('add_con_patient_records');

    // Dynamic name
    Route::get('/get-sub-type-con/{name}', 'getSubType');
});

// ── Vaccination Routes ─────────────────────────────────────────

Route::middleware(['auth', 'approved', 'department:VACCINATION'])->controller(VaccinationController::class)->group(function () {
    Route::get('/vaccination', 'vaccination');
    Route::get('/vax_staff_management', 'vax_staff_management');
    Route::get('/vax_report', 'reports');

    // Appointments
    Route::get('/display-vax_appointments', 'display_vax_appointments');
    Route::put('/update-vax_appointments/{pId}', 'update_vax_appointments');

    // Patient records
    Route::get('/vax_patient_records', 'vax_patient_records');
    Route::post('/add-vax_patient_records', 'add_vax_patient_records')->name('add_vax_patient_records');

    // Dynamic name
    Route::get('/get-sub-type-vax/{name}', 'getSubType');
});

// ── Blood Department Routes ────────────────────────────────────

Route::middleware(['auth', 'approved', 'department:BLOOD'])->controller(BloodController::class)->group(function () {
    Route::get('/blood', 'blood');
    Route::get('/blood_reports', 'reports');
    Route::get('/blood_staff_management', 'blood_staff_management');

    // Appointments
    Route::get('/display-blood_appointments', 'display_blood_appointments');
    Route::put('/update-blood_appointments/{pId}', 'update_blood_appointments');

    // Patient records
    Route::get('/blood_patient_records', 'blood_patient_records');
    Route::post('/add-blood_patient_records', 'add_blood_patient_records')->name('add_blood_patient_records');

    // Dynamic name
    Route::get('/get-sub-type-blood/{name}', 'getSubType');

    // Turned over
    Route::get('/turned_overs', 'turned_overs');
    Route::put('/update-turned_overs/{pId}', 'update_turned_overs');
    Route::post('/add-turned_overs', 'add_turned_overs')->name('add_turned_overs');
    Route::delete('/delete-turned_overs/{id}', 'delete_turned_overs');
});

// ── Inventory Routes ───────────────────────────────────────────

Route::middleware(['auth', 'approved', 'department:INVENTORY'])->controller(InventoryController::class)->group(function () {
    Route::get('/inventory', 'inventory');
    Route::get('/inventory_staff_management', 'inventory_staff_management');
    Route::get('/inventory_reports', 'reports');

    // Medicines
    Route::get('/medicines', 'medicines');
    Route::put('/update-medicines/{pId}', 'update_medicines');
    Route::put('/update-medicines1/{pId}', 'update_medicines1');
    Route::post('/add-medicines', 'add_medicines')->name('add_medicines');
    Route::delete('/delete-medicines/{id}', 'delete_medicines');

    // Medical Supplies
    Route::get('/medical_supplies', 'medical_supplies');
    Route::put('/update-medical_supplies/{pId}', 'update_medical_supplies');
    Route::post('/add-medical_supplies', 'add_medical_supplies')->name('add_medical_supplies');
    Route::delete('/delete-medical_supplies/{id}', 'delete_medical_supplies');

    // Medical Equipment
    Route::get('/medical_equipments', 'medical_equipments');
    Route::put('/update-medical_equipments/{pId}', 'update_medical_equipments');
    Route::post('/add-medical_equipments', 'add_medical_equipments')->name('add_medical_equipments');
    Route::delete('/delete-medical_equipments/{id}', 'delete_medical_equipments');

    // Vaccines
    Route::get('/vaccines', 'vaccines');
    Route::put('/update-vaccines/{pId}', 'update_vaccines');
    Route::post('/add-vaccines', 'add_vaccines')->name('add_vaccines');
    Route::delete('/delete-vaccines/{id}', 'delete_vaccines');
});

// ── Export Route ────────────────────────────────────────────────

Route::middleware(['auth', 'approved', 'can:staff'])->get('/export/{department}', function ($department) {
    Gate::authorize('export-data', $department);

    return Excel::download(new DynamicExport($department), $department.'.xlsx');
});

Route::middleware(['auth', 'approved'])->group(function () {
    Route::post('/lab-appointments', [LaboratoryController::class, 'store']);
    Route::post('/con-appointments', [ConsultationController::class, 'store']);
    Route::post('/vax-appointments', [VaccinationController::class, 'store']);
    Route::post('/blood-appointments', [BloodController::class, 'store']);
});

Route::middleware(['auth', 'approved', 'can:staff'])->group(function () {
    Route::put('/client_account/{pId}', [ITDepartmentController::class, 'client_account_update']);
    Route::delete('/delete-client/{id}', [ITDepartmentController::class, 'deleteClient']);
    Route::post('/add-staff', [ITDepartmentController::class, 'staff_store'])->name('staff_store');
    Route::post('/approve-appointments/{id}', [LaboratoryController::class, 'approve_appointments']);
    Route::delete('/delete-appointments/{id}', [LaboratoryController::class, 'delete_appointments']);
    Route::post('/email/{id}', [LaboratoryController::class, 'email']);
    Route::delete('/delete-patient_records/{id}', [LaboratoryController::class, 'delete_patient_records']);
});
