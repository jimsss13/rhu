<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rhu_user', function (Blueprint $table) {
            $table->id();
            foreach (['f_name', 'm_name', 'l_name', 'suffix', 'gender', 'contactNo', 'street', 'brgy', 'zip_code', 'municipality', 'province', 'address', 'upload_id', 'profile_picture'] as $column) {
                $table->string($column)->nullable();
            }
            $table->date('bday')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('department')->default('Client')->index();
            $table->string('status')->default('Pending')->index();
            $table->string('role')->default('Client');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('rhu_appointment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->index();
            foreach (['name', 'sub_type', 'doctor', 'dose_number', 'contactNo', 'email', 'refer', 'result'] as $column) {
                $table->string($column)->nullable();
            }
            $table->string('type')->index();
            $table->dateTime('date')->nullable();
            $table->string('status')->default('Pending')->index();
            $table->text('reason')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('rhu_client', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->index();
            foreach (['name', 'sub_type', 'doctor', 'dose_number', 'result'] as $column) {
                $table->string($column)->nullable();
            }
            $table->string('type')->index();
            $table->text('reason')->nullable();
            $table->dateTime('date')->nullable();
            $table->unsignedInteger('volume')->nullable();
            $table->text('analysis')->nullable();
            $table->string('status')->index();
            $table->timestamps();
        });

        Schema::create('rhu_medical_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->unique();
            foreach (['well_health', 'antibiotics', 'infection_medication', 'medication_deferral', 'aspirin', 'vaccinations', 'pregnant', 'donated_recently', 'apheresis', 'blood_transfusion', 'transplant', 'graft', 'contact_blood', 'needlestick_injury', 'sexual_contact_hiv', 'prostitute_contact', 'drug_use_contact', 'hemophilia_contact', 'male_contact_with_male', 'saliva_contact_hepatitis', 'contact_blood_hepatitis', 'sexual_contact_hepatitis', 'tattoo', 'piercing', 'acupuncture', 'syphilis_gonorrhea', 'juvenile_detention', 'hiv_aids_positive', 'used_needles', 'clotting_factor', 'hepatitis', 'malaria', 'chagas', 'babesiosis'] as $column) {
                $table->string($column)->nullable();
            }
            $table->timestamps();
        });

        Schema::create('rhu_announcement', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title');
            $table->text('description');
            $table->date('date');
            $table->text('fullcontext');
            $table->string('isShow')->default('Yes');
            $table->timestamps();
        });

        Schema::create('rhu_blood_type', function (Blueprint $table) {
            $table->id();
            $table->string('blood_type')->unique();
            $table->unsignedBigInteger('total')->default(0);
            $table->unsignedBigInteger('current')->default(0);
            $table->unsignedBigInteger('turned_over')->default(0);
            $table->timestamps();
        });

        Schema::create('rhu_turned_over', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('blood_type');
            $table->unsignedInteger('volume');
            $table->date('date');
            $table->timestamps();
        });

        Schema::create('rhu_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->index();
            foreach (['batchNo', 'dosage_f', 'dosage_s', 'location_code', 'end_user'] as $column) {
                $table->string($column)->nullable();
            }
            $table->date('expiration_date')->nullable();
            $table->date('expiration')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();
        });

        Schema::create('rhu_medicine', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('current')->default(0);
            $table->unsignedInteger('turned_over')->default(0);
            $table->timestamps();
        });

        Schema::create('rhu_test', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('status')->default('Available');
            $table->timestamps();
        });

        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->index();
            $table->text('content');
            $table->dateTime('date')->nullable();
            $table->string('status')->default('unread');
            $table->timestamps();
        });

        Schema::create('receipt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->index();
            $table->date('date')->nullable();
            foreach (['receipt_no', 'series', 'agency', 'payor', 'nature', 'account_code', 'teller', 'collecting_officer'] as $column) {
                $table->string($column)->nullable();
            }
            $table->decimal('amount', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            foreach (['name', 'department', 'position'] as $column) {
                $table->string($column)->nullable();
            }
            $table->text('activity')->nullable();
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['logs', 'receipt', 'notification', 'rhu_test', 'rhu_medicine', 'rhu_supplies', 'rhu_turned_over', 'rhu_blood_type', 'rhu_announcement', 'rhu_medical_history', 'rhu_client', 'rhu_appointment', 'rhu_user'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
