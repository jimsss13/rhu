<?php

use App\Models\Log;
use App\Models\Notification;
use App\Models\RhuAnnouncement;
use App\Models\RhuAppointment;
use App\Models\RhuBloodType;
use App\Models\RhuClient;
use App\Models\RhuMedicine;
use App\Models\RhuSupply;
use App\Models\RhuTest;
use App\Models\RhuTurnedOver;
use App\Models\RhuUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Maatwebsite\Excel\Facades\Excel;

beforeEach(function () {
    $this->withoutVite();
    Http::preventStrayRequests();
    Mail::fake();
    config(['services.semaphore.api_key' => 'test-key', 'services.philsms.token' => 'test-token']);
});

function reviewUser(array $attributes = []): RhuUser
{
    return RhuUser::factory()->create(array_merge([
        'role' => 'Client', 'department' => 'Client', 'status' => 'Approved',
        'password' => 'abcdefgh', 'contactNo' => '09171234567',
    ], $attributes));
}

function reviewAppointment(string $type, array $attributes = []): RhuAppointment
{
    $patient = reviewUser();

    return RhuAppointment::create(array_merge([
        'client_id' => $patient->id, 'name' => 'Same Name', 'type' => $type,
        'sub_type' => 'Same service', 'status' => 'Approved', 'date' => now(),
        'reason' => 'Original reason', 'contactNo' => $patient->contactNo,
    ], $attributes));
}

it('creates every domain table on a fresh database', function () {
    foreach (['rhu_user', 'rhu_appointment', 'rhu_client', 'rhu_medical_history', 'rhu_announcement', 'rhu_blood_type', 'rhu_turned_over', 'rhu_supplies', 'rhu_medicine', 'rhu_test', 'notification', 'receipt', 'logs'] as $table) {
        expect(Schema::hasTable($table))->toBeTrue();
    }
});

it('authenticates a valid stored password without imposing a new policy', function (string $password) {
    $user = reviewUser(['password' => $password]);
    $this->post('/login', ['username' => $user->username, 'password' => $password])->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
})->with(['abcdefgh', 'abcdef']);

it('does not authenticate unapproved accounts or log a successful login', function (string $status) {
    $user = reviewUser(['status' => $status]);
    $this->post('/login', ['username' => $user->username, 'password' => 'abcdefgh'])->assertRedirect('/login');
    $this->assertGuest();
    expect(Log::where('user_id', $user->id)->exists())->toBeFalse();
})->with(['Pending', 'Rejected']);

it('revokes access when a signed-in account is no longer approved', function () {
    $user = reviewUser(['status' => 'Pending']);
    $this->actingAs($user)->get('/profile/edit')->assertRedirect('/login');
    $this->assertGuest();
});

it('denies client access to administrative endpoints', function (string $method, string $uri) {
    $this->actingAs(reviewUser())->call($method, $uri)->assertForbidden();
})->with([
    ['POST', '/add-staff'], ['PUT', '/client_account/1'], ['DELETE', '/delete-client/1'],
    ['POST', '/approve-client/1'], ['POST', '/approve-appointments/1'],
    ['DELETE', '/delete-appointments/1'], ['DELETE', '/delete-patient_records/1'],
    ['POST', '/add-announcement'], ['POST', '/add-medicines'],
    ['POST', '/add-lab_patient_records'], ['POST', '/add-con_patient_records'],
    ['POST', '/add-vax_patient_records'], ['POST', '/add-blood_patient_records'],
    ['GET', '/export/laboratory'], ['GET', '/export/appointments'],
]);

it('keeps approved client booking available', function () {
    $user = reviewUser();
    $this->actingAs($user)->post('/lab-appointments', [
        'name' => 'Patient', 'type' => 'laboratory', 'sub_type' => 'CBC',
        'date' => now()->addDay()->format('Y-m-d h:i A'),
    ])->assertSessionHasNoErrors()->assertRedirect();
    $this->assertDatabaseHas('rhu_appointment', ['client_id' => $user->id, 'type' => 'laboratory', 'status' => 'Pending']);
});

it('cannot bypass blood screening using a different booking route', function () {
    $this->actingAs(reviewUser())->post('/lab-appointments', [
        'name' => 'Patient', 'type' => 'blood', 'sub_type' => 'A+',
        'date' => now()->addDay()->format('Y-m-d h:i A'),
    ])->assertSessionHasErrors('type');
    expect(RhuAppointment::count())->toBe(0);
});

it('does not save a blood appointment when its questionnaire is incomplete', function () {
    $this->actingAs(reviewUser())->post('/blood-appointments', [
        'name' => 'Patient', 'type' => 'blood', 'sub_type' => 'A+',
        'date' => now()->addDay()->format('Y-m-d h:i A'),
    ])->assertSessionHasErrors('well_health');
    expect(RhuAppointment::count())->toBe(0);
});

it('only marks the current users notifications as read', function () {
    $user = reviewUser();
    $other = reviewUser();
    $own = Notification::create(['client_id' => $user->id, 'content' => 'Own', 'status' => 'unread']);
    $foreign = Notification::create(['client_id' => $other->id, 'content' => 'Other', 'status' => 'unread']);
    Livewire::actingAs($user)->test(\App\Livewire\Notifications::class)
        ->call('markAsRead', $foreign->id)->call('markAsRead', $own->id);
    expect($foreign->fresh()->status)->toBe('unread')->and($own->fresh()->status)->toBe('read');
});

it('renders department dashboards after provisioning', function (string $department, string $uri) {
    $this->actingAs(reviewUser(['role' => 'Admin', 'department' => $department]))
        ->get($uri)->assertOk();
})->with([
    ['IT DEPARTMENT', '/it_dept'], ['SUPER ADMIN', '/super_admin'],
    ['LABORATORY', '/laboratory'], ['CONSULTATION', '/consultation'],
    ['VACCINATION', '/vaccination'], ['BLOOD', '/blood'], ['INVENTORY', '/inventory'],
]);

it('rejects notification side effects over get requests', function () {
    $this->get('/approve-appointments/1')->assertStatus(405);
    $this->get('/approve-client-email/1')->assertStatus(405);
    $this->get('/email/1')->assertStatus(405);
});

it('prevents departmental staff from creating staff in another department', function () {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'LABORATORY']);
    $this->actingAs($staff)->post('/add-staff', [
        'f_name' => 'New', 'm_name' => 'Test', 'l_name' => 'Admin',
        'username' => 'new-admin', 'email' => 'new-admin@example.test',
        'password' => 'abcdefgh', 'password_confirmation' => 'abcdefgh',
        'department' => 'IT DEPARTMENT', 'status' => 'Active',
    ])->assertForbidden();
    $this->assertDatabaseMissing('rhu_user', ['username' => 'new-admin']);
});

it('allows staff management within its authorized scope', function (string $department, string $targetDepartment) {
    $staff = reviewUser(['role' => 'Admin', 'department' => $department]);
    $this->actingAs($staff)->post('/add-staff', [
        'f_name' => 'New', 'm_name' => 'Test', 'l_name' => 'Staff',
        'username' => 'new-staff', 'email' => 'new-staff@example.test',
        'password' => 'abcdefgh', 'password_confirmation' => 'abcdefgh',
        'department' => $targetDepartment, 'status' => 'Active',
    ])->assertSessionHasNoErrors()->assertRedirect();
    $this->assertDatabaseHas('rhu_user', ['username' => 'new-staff', 'department' => $targetDepartment, 'role' => 'Admin']);
})->with([['LABORATORY', 'LABORATORY'], ['IT DEPARTMENT', 'BLOOD'], ['SUPER ADMIN', 'INVENTORY']]);

it('denies shared operations on another departments resources', function () {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'LABORATORY']);
    $appointment = reviewAppointment('vaccination');
    $record = RhuClient::create(['name' => 'Other', 'type' => 'vaccination', 'sub_type' => 'Vaccine', 'status' => 'Completed']);
    $target = reviewUser(['role' => 'Admin', 'department' => 'BLOOD']);
    $this->actingAs($staff)->post('/approve-appointments/'.$appointment->id)->assertForbidden();
    $this->delete('/delete-appointments/'.$appointment->id)->assertForbidden();
    $this->put('/update-lab_appointments/'.$appointment->id, ['status' => 'Rejected'])->assertForbidden();
    $this->delete('/delete-patient_records/'.$record->id)->assertForbidden();
    $this->delete('/delete-client/'.$target->id)->assertForbidden();
    $this->put('/client_account/'.$target->id)->assertForbidden();
    $this->get('/export/vax_appointments')->assertForbidden();
    $this->get('/export/unknown')->assertForbidden();
});

it('allows a departmental export and a super administrator cross-department export', function (string $department) {
    Excel::fake();
    $staff = reviewUser(['role' => 'Admin', 'department' => $department]);
    $this->actingAs($staff)->get('/export/lab_appointments')->assertOk();
    Excel::assertDownloaded('lab_appointments.xlsx');
})->with(['LABORATORY', 'SUPER ADMIN']);

it('records only the selected appointment and preserves patient identity', function (string $type, string $uri, array $fields) {
    Storage::fake('public');
    $staff = reviewUser(['role' => 'Admin', 'department' => strtoupper($type)]);
    $selected = reviewAppointment($type);
    $other = reviewAppointment($type);
    if ($type === 'laboratory') {
        $fields['result'] = UploadedFile::fake()->create('result.pdf', 10, 'application/pdf');
    }
    $payload = array_merge($fields, [
        'appointment_id' => $selected->id, 'status' => 'Completed',
        'name' => 'Forged name', 'sub_type' => 'Forged service', 'client_id' => $other->client_id,
    ]);
    $this->actingAs($staff)->post($uri, $payload)->assertSessionHasNoErrors()->assertRedirect();
    expect($selected->fresh()->status)->toBe('Completed');
    expect($other->fresh()->status)->toBe('Approved');
    $record = RhuClient::sole();
    expect($record->client_id)->toBe($selected->client_id)
        ->and($record->name)->toBe($selected->name)
        ->and($record->sub_type)->toBe($selected->sub_type);
    if ($type === 'laboratory') {
        expect($selected->fresh()->result)->toBe($record->result)
            ->and($other->fresh()->result)->toBeNull();
        Storage::disk('public')->assertExists($record->result);
    }
    if ($type === 'blood') {
        expect(RhuBloodType::sole()->current)->toBe(450);
    }
    $this->post($uri, $payload)->assertSessionHasErrors('appointment_id');
    expect(RhuClient::count())->toBe(1);
})->with([
    ['laboratory', '/add-lab_patient_records', []],
    ['consultation', '/add-con_patient_records', ['doctor' => 'Doctor', 'analysis' => 'Notes']],
    ['vaccination', '/add-vax_patient_records', ['dose_number' => '1']],
    ['blood', '/add-blood_patient_records', ['volume' => 450]],
]);

it('rejects an appointment from another department without creating a record', function () {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'BLOOD']);
    $appointment = reviewAppointment('vaccination');
    $this->actingAs($staff)->post('/add-blood_patient_records', [
        'appointment_id' => $appointment->id, 'volume' => 450, 'status' => 'Completed',
    ])->assertSessionHasErrors('appointment_id');
    expect(RhuClient::count())->toBe(0)->and(RhuBloodType::count())->toBe(0);
});

it('preserves rejection status without adding rejected blood to stock', function () {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'BLOOD']);
    $appointment = reviewAppointment('blood');
    $this->actingAs($staff)->post('/add-blood_patient_records', [
        'appointment_id' => $appointment->id, 'volume' => 450, 'status' => 'Rejected',
    ])->assertSessionHasNoErrors();
    expect($appointment->fresh()->status)->toBe('Rejected')->and(RhuBloodType::count())->toBe(0);
});

it('reconciles blood stock when a turnover is edited or deleted', function () {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'BLOOD']);
    $a = RhuBloodType::create(['blood_type' => 'A+', 'total' => 1000, 'current' => 1000, 'turned_over' => 0]);
    $b = RhuBloodType::create(['blood_type' => 'B+', 'total' => 500, 'current' => 500, 'turned_over' => 0]);
    $payload = ['name' => 'Blood Bank', 'date' => '2026-10-26', 'blood_type' => 'A+', 'volume' => 200];
    $this->actingAs($staff)->post('/add-turned_overs', $payload)->assertSessionHasNoErrors();
    $record = RhuTurnedOver::sole();
    expect($a->fresh()->current)->toBe(800)->and($a->fresh()->turned_over)->toBe(200);
    $this->put('/update-turned_overs/'.$record->id, array_merge($payload, ['volume' => 300]))->assertSessionHasNoErrors();
    expect($a->fresh()->current)->toBe(700)->and($a->fresh()->turned_over)->toBe(300);
    $this->put('/update-turned_overs/'.$record->id, array_merge($payload, ['blood_type' => 'B+']))->assertSessionHasNoErrors();
    expect($a->fresh()->current)->toBe(1000)->and($b->fresh()->current)->toBe(300);
    $this->delete('/delete-turned_overs/'.$record->id)->assertOk();
    expect($b->fresh()->current)->toBe(500)->and($b->fresh()->turned_over)->toBe(0);
});

it('rolls back turnover stock adjustments when the new stock is insufficient', function () {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'BLOOD']);
    $a = RhuBloodType::create(['blood_type' => 'A+', 'total' => 1000, 'current' => 800, 'turned_over' => 200]);
    $b = RhuBloodType::create(['blood_type' => 'B+', 'total' => 100, 'current' => 100, 'turned_over' => 0]);
    $record = RhuTurnedOver::create(['name' => 'Blood Bank', 'date' => '2026-10-26', 'blood_type' => 'A+', 'volume' => 200]);
    $this->actingAs($staff)->put('/update-turned_overs/'.$record->id, [
        'name' => 'Blood Bank', 'date' => '2026-10-26', 'blood_type' => 'B+', 'volume' => 200,
    ])->assertSessionHasErrors('volume');
    expect($a->fresh()->current)->toBe(800)->and($b->fresh()->current)->toBe(100)
        ->and($record->fresh()->blood_type)->toBe('A+');
});

it('keeps medicine totals consistent through dispensing and a renamed restock', function () {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'INVENTORY']);
    $supply = RhuSupply::create(['name' => 'Medicine', 'type' => 'Medicines', 'dosage_s' => '10mg', 'quantity' => 100]);
    $medicine = RhuMedicine::create(['name' => 'Medicine', 'total' => 100, 'current' => 100, 'turned_over' => 0]);
    $this->actingAs($staff)->put('/update-medicines1/'.$supply->id, ['quantity' => 20])->assertSessionHasNoErrors();
    expect($supply->fresh()->quantity)->toBe(80)->and($medicine->fresh()->current)->toBe(80)
        ->and($medicine->fresh()->turned_over)->toBe(20);
    $this->put('/update-medicines/'.$supply->id, ['name' => 'Renamed', 'dosage_s' => '10mg', 'quantity' => 50])->assertSessionHasNoErrors();
    expect($supply->fresh()->quantity)->toBe(130)->and($medicine->fresh()->current)->toBe(130)
        ->and($medicine->fresh()->total)->toBe(150)->and($medicine->fresh()->name)->toBe('Renamed');
    $this->put('/update-medicines1/'.$supply->id, ['quantity' => 131])->assertSessionHasErrors('quantity');
    expect($supply->fresh()->quantity)->toBe(130);
});

it('rejects invalid stock quantities before doing arithmetic', function ($quantity) {
    $staff = reviewUser(['role' => 'Admin', 'department' => 'INVENTORY']);
    $supply = RhuSupply::create(['name' => 'Medicine', 'type' => 'Medicines', 'quantity' => 100]);
    $this->actingAs($staff)->put('/update-medicines1/'.$supply->id, ['quantity' => $quantity])->assertSessionHasErrors('quantity');
    expect($supply->fresh()->quantity)->toBe(100);
})->with(['abc', -1, 0, 1.5]);

it('renders patient record forms with unambiguous appointment ids', function (string $type, string $uri) {
    $staff = reviewUser(['role' => 'Admin', 'department' => strtoupper($type)]);
    $appointment = reviewAppointment($type);
    $this->actingAs($staff)->get($uri)->assertOk()
        ->assertSee('name="appointment_id"', false)
        ->assertSee('value="'.$appointment->id.'" data-sub-type=', false);
})->with([
    ['laboratory', '/lab_patient_records'], ['consultation', '/con_patient_records'],
    ['vaccination', '/vax_patient_records'], ['blood', '/blood_patient_records'],
]);

it('preserves visibility strings and date-only json values', function () {
    $user = reviewUser(['bday' => '1990-01-01']);
    expect($user->fresh()->toArray()['bday'])->toBe('1990-01-01');
    $supply = RhuSupply::create(['name' => 'Vaccine', 'type' => 'Vaccines', 'expiration_date' => '2027-01-01']);
    expect($supply->fresh()->toArray()['expiration_date'])->toBe('2027-01-01');
    foreach (['Yes', 'No'] as $visibility) {
        $announcement = RhuAnnouncement::create([
            'title' => $visibility, 'description' => 'Description', 'fullcontext' => 'Content',
            'date' => '2026-10-26', 'isShow' => $visibility,
        ]);
        expect($announcement->fresh()->toArray())->toMatchArray(['isShow' => $visibility, 'date' => '2026-10-26']);
    }
    $this->get('/')->assertOk()->assertViewHas('announcements', fn ($rows) => $rows->count() === 1 && $rows->first()->title === 'Yes');
});

it('serves profile editing for signed-in users and redirects guests', function () {
    $this->get('/profile/edit')->assertRedirect('/login');
    $this->actingAs(reviewUser())->get('/profile/edit')->assertOk()->assertViewIs('profiles.edit');
});

it('sends approval sms using the normalized recipient', function () {
    Http::fake(['api.semaphore.co/*' => Http::response([], 200)]);
    $staff = reviewUser(['role' => 'Admin', 'department' => 'LABORATORY']);
    $appointment = reviewAppointment('laboratory', ['status' => 'Pending']);
    $this->actingAs($staff)->post('/approve-appointments/'.$appointment->id)->assertOk();
    Http::assertSent(fn ($request) => $request['number'] === '639171234567');
});

it('generates the selected appointments receipt and sends its notification', function () {
    Storage::fake('public');
    Http::fake(['app.philsms.com/*' => Http::response([], 200)]);
    $staff = reviewUser(['role' => 'Admin', 'department' => 'LABORATORY']);
    $older = reviewAppointment('laboratory', ['sub_type' => 'Older service']);
    $selected = reviewAppointment('laboratory', ['client_id' => $older->client_id, 'sub_type' => 'Selected service', 'name' => 'Patient & Example']);
    RhuTest::create(['name' => 'Selected service', 'price' => '1200.50']);

    $this->actingAs($staff)->post('/email/'.$selected->id)->assertSessionMissing('error')->assertRedirect();

    Mail::assertSent(\App\Mail\DemoMail::class, fn ($mail) => str_contains($mail->mailData['body'], 'Selected service') && $mail->hasTo($selected->client->email) && count($mail->attachments()) === 1
    );
    $files = Storage::disk('public')->files('receipts');
    expect($files)->toHaveCount(1);
    expect(Storage::disk('public')->get($files[0]))->toStartWith('%PDF-');
    Http::assertSent(fn ($request) => $request['recipient'] === '639171234567');
    $this->assertDatabaseHas('notification', ['client_id' => $selected->client_id, 'status' => 'unread']);
});

it('can provision the initial administrator without default credentials', function () {
    $this->artisan('rhu:create-admin')
        ->expectsQuestion('First name', 'Initial')->expectsQuestion('Last name', 'Admin')
        ->expectsQuestion('Username', 'initial-admin')->expectsQuestion('Email', 'admin@example.test')
        ->expectsQuestion('Password', 'abcdefgh')->expectsQuestion('Confirm password', 'abcdefgh')
        ->assertSuccessful();
    $this->assertDatabaseHas('rhu_user', ['username' => 'initial-admin', 'department' => 'IT DEPARTMENT', 'role' => 'Admin', 'status' => 'Active']);
});

it('resets a password with a valid otp and allows the new password to log in', function () {
    $user = reviewUser();
    $this->withSession(['otp' => 123456, 'email' => $user->email, 'otp_expires' => now()->addMinutes(5)])
        ->post('/reset-password', [
            'otp' => '123456', 'new_password' => 'newpassword', 'new_password_confirmation' => 'newpassword',
        ])->assertRedirect('/login')->assertSessionMissing('otp');
    $this->post('/login', ['username' => $user->username, 'password' => 'newpassword'])->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
});

it('requires an unexpired otp session to change a password', function (bool $expired) {
    $user = reviewUser();
    if ($expired) {
        $this->withSession(['otp' => 123456, 'email' => $user->email, 'otp_expires' => now()->subMinute()]);
    }
    $this->post('/reset-password', [
        'otp' => '123456', 'new_password' => 'newpassword', 'new_password_confirmation' => 'newpassword',
    ])->assertSessionHas('error');
    expect(\Illuminate\Support\Facades\Hash::check('abcdefgh', $user->fresh()->password))->toBeTrue();
})->with([false, true]);

it('invalidates an otp after repeated incorrect attempts', function () {
    $user = reviewUser();
    $this->withSession(['otp' => 123456, 'email' => $user->email, 'otp_expires' => now()->addMinutes(5)]);
    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post('/reset-password', [
            'otp' => '111111', 'new_password' => 'newpassword', 'new_password_confirmation' => 'newpassword',
        ])->assertSessionHas('error');
    }
    $this->post('/reset-password', [
        'otp' => '123456', 'new_password' => 'newpassword', 'new_password_confirmation' => 'newpassword',
    ])->assertSessionHas('error')->assertSessionMissing('otp');
    expect(\Illuminate\Support\Facades\Hash::check('abcdefgh', $user->fresh()->password))->toBeTrue();
});

it('registers a pending client with canonical profile values', function () {
    $this->post('/register', [
        'f_name' => 'New', 'l_name' => 'Patient', 'bday' => '1990-01-01', 'gender' => 'male',
        'contactNo' => '09171234567', 'street' => 'Street', 'brgy' => 'Barangay', 'zip_code' => '4328',
        'municipality' => 'Lucban', 'province' => 'Quezon', 'email' => 'new.patient@gmail.com',
        'username' => 'new-patient', 'password' => 'abcdefgh', 'password_confirmation' => 'abcdefgh',
        'role' => 'Admin', 'department' => 'IT DEPARTMENT', 'status' => 'Active',
    ])->assertSessionHasNoErrors()->assertRedirect('/login');
    $this->assertGuest();
    $this->assertDatabaseHas('rhu_user', [
        'username' => 'new-patient', 'gender' => 'Male', 'department' => 'Client', 'role' => 'Client', 'status' => 'Pending',
    ]);
});

it('rejects a non-image profile upload', function () {
    Storage::fake('public');
    $user = reviewUser();
    $this->actingAs($user)->post('/profile/update', [
        'f_name' => $user->f_name, 'l_name' => $user->l_name, 'bday' => '1990-01-01', 'gender' => 'Male',
        'contactNo' => '09171234567', 'email' => $user->email,
        'profile_picture' => UploadedFile::fake()->create('payload.html', 10, 'text/html'),
    ])->assertSessionHasErrors('profile_picture');
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
});
