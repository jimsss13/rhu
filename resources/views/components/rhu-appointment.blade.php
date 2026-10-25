@props(['appointments', 'tests', 'vaccines'])
<style>
    .hidden {
        display: none;
    }
</style>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

 <!--Laboratory Form -->
 <div id="LaboratoryForm" class="form-container hidden">
    <h3 class="text-xl font-bold mb-4">Laboratory Appointments</h3>

    @if ($appointments->isEmpty())
        <p>No appointments found.</p>
    @else
        <!-- 1) Wrap your table in .table-responsive -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Test</th>
                        <th>Date</th>
                        {{-- <th>Contact Number</th> --}}
                        <th>Result</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments->where('type', 'laboratory') as $appointment)
                        <tr>
                            <td>{{ $appointment->name }}</td>
                            <td>{{ $appointment->sub_type }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A') }}</td>
                            {{-- <td>{{ $appointment->contactNo }}</td> --}}
                            <td>
                                @if (!empty($appointment->result))
                                    <!-- 2) Truncate without forcing nowrap (optional) -->
                                    <span style="display: inline-block; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ asset('storage/' . $appointment->result) }}
                                    </span>
                                @else
                                    <span class="text-muted">No result available</span>
                                @endif
                            </td>
                            <td>{{ $appointment->status }}</td>
                            <td>
                                @if (empty($appointment->result))
                                    <span style="display: inline-block; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span class="text-muted">The appointment is not yet complete</span>
                                    </span>
                                @else
                                    <a href="{{ asset('storage/' . $appointment->result) }}" target="_blank" class="btn btn-info btn-sm d-inline">Preview</a>
                                    <a href="{{ asset('storage/' . $appointment->result) }}" class="btn btn-primary btn-sm d-inline" download>Download</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> <!-- /.table-responsive -->
    @endif

    <div class="d-flex justify-content-center">
        <div class="w-75">
            <h3 class="text-2xl font-bold mb-4 mt-5 text-center">Laboratory Appointment Form</h3>
            <form action="/lab-appointments" method="POST">
                @csrf
                <!-- Patient Name -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="patientName" class="form-label text-black font-bold">Patient Name:</label>
                            <input
                                type="text"
                                class="form-control w-100"
                                name="name"
                                id="patientName"
                                value="{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}"
                                readonly>
                        </div>
                    </div>
                    <!-- Test Lists -->
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="labTestType" class="form-label text-black font-bold">Test:</label>
                            <select class="form-select w-100" name="sub_type" id="labTestType" required>
                                @foreach($tests as $test)
                                    <option value="{{ $test->name }}">
                                        {{ $test->name }}: Php {{$test->price}}.00
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
    
                <!-- Hidden Input for Type -->
                <div class="mb-3 d-none">
                    <input
                        type="text"
                        class="form-control"
                        name="type"
                        id="type"
                        value="laboratory"
                        readonly>
                </div>
    
                <!-- Date Picker -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="datepicker" class="form-label text-black font-bold">Appointment Date:</label>
                            <div id="lab_datepickerContainer"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="lab_timeSlots" class="form-label text-black font-bold">Available Time Slots:</label>
                            <select id="lab_timeSlotsDropdown" class="form-select w-100" required>
                                <option value="" selected disabled>Select a time slot</option>
                                @php
                                    // Group appointments by date and format times
                                    $bookedAppointments1 = $appointments->where('type', 'laboratory')->groupBy(function ($appointment) {
                                        return \Carbon\Carbon::parse($appointment->date)->format('Y-m-d');
                                    })->map(function ($group) {
                                        return $group->map(function ($appointment) {
                                            return \Carbon\Carbon::parse($appointment->date)->format('h:i A');
                                        })->toArray();
                                    });
                                @endphp
    
                                @foreach ([
                                    '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
                                    '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM'
                                ] as $timeSlot)
                                    <option value="{{ $timeSlot }}">{{ $timeSlot }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
    
                <!-- Selected Date & Time -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="lab_selectedDateTime" class="form-label text-black font-bold">Selected Date & Time:</label>
                            <input
                                type="text"
                                class="form-control"
                                name="date"
                                id="lab_selectedDateTime"
                                readonly>
                        </div>
                    </div>
                </div>
    
                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    
</div>

{{-- Consultation Form --}}
<div id="ConsultationForm" class="form-container hidden">
    <h3 class="text-xl font-bold mb-4">Consultation Appointments</h3>
    @if ($appointments->isEmpty())
        <p>No appointments found.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Mode of consultation</th>
                    <th>Reason</th>
                    <th>Date</th>
                    {{-- <th>Contact Number</th> --}}
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments->where('type', 'consultation') as $appointment)
                    <tr>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $appointment->sub_type)) }}</td>
                        <td>{{ $appointment->reason }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A')}}</td>
                        {{-- <td>{{ $appointment->contactNo }}</td> --}}
                        <td>{{ $appointment->status }}</td> <!-- Assuming 'status' is a field in your model -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


<div class="d-flex justify-content-center">
    <div class="w-75">
        <h3 class="text-2xl font-bold mb-4 mt-5 text-center">Consultation Appointment Form</h3>
        <form action="/con-appointments" method="POST">
            @csrf
            <!-- Patient Name -->
            <div class="row mb-4 justify-content-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="patientName" class="form-label text-black font-bold">Patient Name:</label>
                        <input
                            type="text"
                            class="form-control w-100"
                            name="name"
                            id="patientName"
                            value="{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}"
                            readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="consultation" class="form-label text-black font-bold">Mode of Consultation:</label>
                        <select class="form-select w-100" name="sub_type" id="consultation" required>
                            <option value="physical" selected>Physical Consultation</option>
                            <option value="online">Online Consultation</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="reason" class="form-label text-black font-bold">Reason/Concern:</label>
                        <input
                            type="text"
                            class="form-control w-100"
                            name="reason"
                            id="reason"
                            required>
                    </div>
                </div>
            </div>

            <!-- Hidden Input for Type -->
            <div class="mb-3 d-none">
                <input
                    type="text"
                    class="form-control"
                    name="type"
                    id="type"
                    value="consultation"
                    readonly>
            </div>

            <!-- Date Picker -->
            <div class="row mb-4 justify-content-center">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="datepicker" class="form-label text-black font-bold">Appointment Date:</label>
                        <div id="con_datepickerContainer"></div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="con_timeSlots" class="form-label text-black font-bold">Available Time Slots:</label>
                        <select id="con_timeSlotsDropdown" class="form-select w-100" required>
                            <option value="" selected disabled>Select a time slot</option>
                            @php
                                // Group appointments by date and format times
                                $bookedAppointments2 = $appointments->where('type', 'consultation')->groupBy(function ($appointment) {
                                    return \Carbon\Carbon::parse($appointment->date)->format('Y-m-d');
                                })->map(function ($group) {
                                    return $group->map(function ($appointment) {
                                        return \Carbon\Carbon::parse($appointment->date)->format('h:i A');
                                    })->toArray();
                                });
                            @endphp

                            @foreach ([
                                '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
                                '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM'
                            ] as $timeSlot)
                                <option value="{{ $timeSlot }}">{{ $timeSlot }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Selected Date & Time -->
            <div class="row mb-4 justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="con_selectedDateTime" class="form-label text-black font-bold">Selected Date & Time:</label>
                        <input
                            type="text"
                            class="form-control w-100"
                            name="date"
                            id="con_selectedDateTime"
                            readonly>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

</div>

{{-- Vaccination Form --}}
<div id="VaccinationForm" class="form-container hidden">
    <h3 class="text-xl font-bold mb-4">Vaccination Appointments</h3>
    @if ($appointments->isEmpty())
        <p>No appointments found.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Vaccine Type</th>
                    <th>Dose Number</th>
                    <th>Date</th>
                    {{-- <th>Contact Number</th> --}}
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($appointments->where('type', 'vaccination') as $appointment)
                    <tr>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $appointment->sub_type }}</td>
                        <td>{{ $appointment->dose_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A')}}</td>
                        {{-- <td>{{ $appointment->contactNo }}</td> --}}
                        <td>{{ $appointment->status }}</td> <!-- Assuming 'status' is a field in your model -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif


    <div class="d-flex justify-content-center">
        <div class="w-75">
            <h3 class="text-2xl font-bold mb-4 mt-5 text-center">Vaccination Appointment Form</h3>
            <form action="/vax-appointments" method="POST">
                @csrf
    
                <!-- Patient Name -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="patientName" class="form-label text-black font-bold">Patient Name:</label>
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                id="patientName"
                                value="{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="vaccination" class="form-label text-black font-bold">Vaccine Type:</label>
                            <select class="form-select" name="sub_type" id="vaccination" required>
                                @foreach ($vaccines as $vaccine)
                                    <option value="{{ $vaccine->name }}">{{ $vaccine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dose_number" class="form-label text-black font-bold">Dose Number:</label>
                            <select class="form-select" name="dose_number" id="dose_number" required>
                                <option value="1" selected>1st Dose</option>
                                <option value="2">2nd Dose</option>
                            </select>
                        </div>
                    </div>
                </div>
    
                <!-- Hidden Input for Type -->
                <div class="d-none">
                    <input
                        type="text"
                        class="form-control"
                        name="type"
                        id="type"
                        value="vaccination"
                        readonly>
                </div>
    
                <!-- Date Picker -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="datepicker" class="form-label text-black font-bold">Appointment Date:</label>
                            <div id="vax_datepickerContainer"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="vax_timeSlots" class="form-label text-black font-bold">Available Time Slots:</label>
                            <select id="vax_timeSlotsDropdown" class="form-select" required>
                                <option value="" selected disabled>Select a time slot</option>
                                @php
                                    // Group appointments by date and format times
                                    $bookedAppointments3 = $appointments->where('type', 'vaccination')->groupBy(function ($appointment) {
                                        return \Carbon\Carbon::parse($appointment->date)->format('Y-m-d');
                                    })->map(function ($group) {
                                        return $group->map(function ($appointment) {
                                            return \Carbon\Carbon::parse($appointment->date)->format('h:i A');
                                        })->toArray();
                                    });
                                @endphp
    
                                @foreach ([ 
                                    '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
                                    '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM'
                                ] as $timeSlot)
                                    <option value="{{ $timeSlot }}">{{ $timeSlot }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
    
                <!-- Selected Date & Time -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vax_selectedDateTime" class="form-label text-black font-bold">Selected Date & Time:</label>
                            <input
                                type="text"
                                class="form-control"
                                name="date"
                                id="vax_selectedDateTime"
                                readonly>
                        </div>
                    </div>
                </div>
    
                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    
</div>

{{-- Blood Form --}}
<div id="BloodForm" class="form-container hidden">
    <h3 class="text-xl font-bold mb-4">Blood Donation Appointments</h3>
    @if ($appointments->isEmpty())
        <p>No appointments found.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Blood Type</th>
                    <th>Date</th>
                    {{-- <th>Contact Number</th> --}}
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Define an array to map laboratory tests to their full names
                    $bloodTypes = [
                        'A_positive' => 'A+',
                        'A_negative' => 'A-',
                        'B_positive' => 'B+',
                        'B_negative' => 'B-',
                        'AB_positive' => 'AB+',
                        'AB_negative' => 'AB-',
                        'O_positive' => 'O+',
                        'O_negative' => 'O-',
                    ];
                @endphp
                @foreach ($appointments->where('type', 'blood') as $appointment)
                    <tr>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $bloodTypes[$appointment->sub_type] ?? $appointment->sub_type }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A') }}</td>
                        {{-- <td>{{ $appointment->contactNo }}</td> --}}
                        <td>{{ $appointment->status }}</td> <!-- Assuming 'status' is a field in your model -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="d-flex justify-content-center">
        <div class="w-75">
            <h3 class="text-2xl font-bold mb-4 mt-5 text-center">Blood Donation Appointment Form</h3>
            <form action="/blood-appointments" method="POST">
                @csrf
    
                <!-- Patient Name -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="patientName" class="form-label text-black font-bold">Patient Name:</label>
                            <input
                                type="text"
                                class="form-control"
                                name="name"
                                id="patientName"
                                value="{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bloodType" class="form-label text-black font-bold">Blood Type:</label>
                            <select class="form-select" name="sub_type" id="bloodType" required>
                                <option value="A+" selected>A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                </div>
    
                <!-- Hidden Input for Type -->
                <div class="d-none">
                    <input
                        type="text"
                        class="form-control"
                        name="type"
                        id="type"
                        value="blood"
                        readonly>
                </div>
    
    
                <!-- Appointment Date -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="datepicker" class="form-label text-black font-bold">Appointment Date:</label>
                            <div id="blood_datepickerContainer"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="blood_timeSlots" class="form-label text-black font-bold">Available Time Slots:</label>
                            <select id="blood_timeSlotsDropdown" class="form-select" required>
                                <option value="" selected disabled>Select a time slot</option>
                                @php
                                    // Group appointments by date and format times
                                    $bookedAppointments4 = $appointments->where('type', 'blood')->groupBy(function ($appointment) {
                                        return \Carbon\Carbon::parse($appointment->date)->format('Y-m-d');
                                    })->map(function ($group) {
                                        return $group->map(function ($appointment) {
                                            return \Carbon\Carbon::parse($appointment->date)->format('h:i A');
                                        })->toArray();
                                    });
                                @endphp
    
                                @foreach ([
                                    '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM',
                                    '01:00 PM', '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM'
                                ] as $timeSlot)
                                    <option value="{{ $timeSlot }}">{{ $timeSlot }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="blood_selectedDateTime" class="form-label text-black font-bold">Selected Date & Time:</label>
                            <input
                                type="text"
                                class="form-control"
                                name="date"
                                id="blood_selectedDateTime"
                                readonly>
                        </div>
                    </div>
                </div>
    
            <!-- Medical History -->
            <h4 class="mb-2 font-bold">Medical History</h4>
            <p class="mb-4">Please answer the following questions:</p>

            <!-- Table Structure -->
            <table class="table table-bordered" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <tbody>
                            <!-- Dynamically Generated Questions -->
                            @php
                            // Map each question text to the expected field name.
                            $questions = [
                                "Feeling well and healthy today?" => "well_health",
                                "Currently taking an antibiotic?" => "antibiotics",
                                "Currently taking any other medication for an infection?" => "infection_medication",
                                "Have you ever taken any medications on the Medication Deferral List?" => "medication_deferral",
                                "In the past 48 hours, have you taken aspirin or anything that has aspirin in it?" => "aspirin",
                                "In the past 4 weeks, have you had any vaccinations or any other kind of shot?" => "vaccinations",
                                "In the past 6 weeks, have you been pregnant or are you pregnant now?" => "pregnant",
                                "In the past 8 weeks, have you donated blood, platelets, or plasma?" => "donated_recently",
                                "In the past 16 weeks, have you donated a double unit of red cells using an apheresis device?" => "apheresis",
                                "Had a blood transfusion?" => "blood_transfusion",
                                "Had a transplant such as organ, tissue, or bone marrow?" => "transplant",
                                "Had a graft such as bone or skin?" => "graft",
                                "Come into contact with someone else's blood?" => "contact_blood",
                                "Had an accidental needlestick injury?" => "needlestick_injury",
                                "Had sexual contact with anyone who has HIV/AIDS or had a positive test for the HIV/AIDS virus?" => "sexual_contact_hiv",
                                "Had sexual contact with a prostitute or anyone else who takes money or drugs or other payment for sex?" => "prostitute_contact",
                                "Had sexual contact with anyone who has ever used needles to take drugs or steroids?" => "drug_use_contact",
                                "Had sexual contact with anyone who has hemophilia or has used clotting factor concentrates?" => "hemophilia_contact",
                                "Female donors: had sexual contact with a male who has ever had sexual contact with another male? (Males: check No)" => "male_contact_with_male",
                                "Come into contact with saliva (including kissing) from a person who has hepatitis?" => "saliva_contact_hepatitis",
                                "Come into contact with blood from a person who has hepatitis?" => "contact_blood_hepatitis",
                                "Had sexual contact with a person who has hepatitis?" => "sexual_contact_hepatitis",
                                "Had a tattoo applied?" => "tattoo",
                                "Had ear or body piercing?" => "piercing",
                                "Had acupuncture?" => "acupuncture",
                                "Had or been treated for syphilis or gonorrhea?" => "syphilis_gonorrhea",
                                "Been in juvenile detention, lockup, jail, or prison for more than 72 hours?" => "juvenile_detention",
                                "Ever had a positive test for the HIV/AIDS virus?" => "hiv_aids_positive",
                                "Ever used needles to take drugs, steroids, or anything not prescribed by a doctor?" => "used_needles",
                                "Ever received clotting factor concentrates?" => "clotting_factor",
                                "Ever had hepatitis?" => "hepatitis",
                                "Ever had malaria?" => "malaria",
                                "Ever had Chagas' disease?" => "chagas",
                                "Ever had babesiosis?" => "babesiosis",
                            ];
                        @endphp
                        
                        <table class="table table-bordered" style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid black; padding: 10px; width: 70%;"></th>
                                    <th style="border: 1px solid black; padding: 10px; width: 15%; text-align: center;">Yes</th>
                                    <th style="border: 1px solid black; padding: 10px; width: 15%; text-align: center;">No</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $questionText => $fieldName)
                                <tr>
                                    <td style="border: 1px solid black; padding: 10px;">{{ $questionText }}</td>
                                    <td style="border: 1px solid black; padding: 10px; text-align: center;">
                                        <input type="radio" class="form-check-input" name="{{ $fieldName }}" value="yes" required>
                                    </td>
                                    <td style="border: 1px solid black; padding: 10px; text-align: center;">
                                        <input type="radio" class="form-check-input" name="{{ $fieldName }}" value="no">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    
                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                </div>
                
    
            </form>
        </div>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- for laboratory --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
    let selectedDate = null;
    let selectedTime = null;

    // Show calendar when button is clicked
    document.getElementById('lab_showCalendarBtn').addEventListener('click', function () {
        document.getElementById('lab_datepickerWrapper').classList.remove('hidden');
        this.style.display = 'none'; // Hide button after showing the calendar
    });

    // Initialize Flatpickr inline
    flatpickr("#lab_datepickerContainer", {
        minDate: "today",
        maxDate: new Date(new Date().getFullYear(), 11, 31),
        dateFormat: "Y-m-d",
        disable: [
            function (date) {
                return date.getDay() === 0 || date.getDay() === 6;
            }
        ],
        inline: true,
        onChange: function (selectedDates, dateStr) {
            selectedDate = dateStr;
            updateDateTime();
        }
    });

    // Handle time slot dropdown selection
    document.getElementById('lab_timeSlotsDropdown').addEventListener('change', function () {
        selectedTime = this.value;
        updateDateTime();
    });

    // Function to update the combined date and time
    function updateDateTime() {
        if (selectedDate && selectedTime) {
            const dateTime = `${selectedDate} ${selectedTime}`;
            document.getElementById('lab_selectedDateTime').value = dateTime;
        }
    }
    });

</script> --}}

{{-- for consultation --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
    let selectedDate = null;
    let selectedTime = null;

    // Show calendar when button is clicked
    document.getElementById('con_showCalendarBtn').addEventListener('click', function () {
        document.getElementById('con_datepickerWrapper').classList.remove('hidden');
        this.style.display = 'none'; // Hide button after showing the calendar
    });

    // Initialize Flatpickr inline
    flatpickr("#con_datepickerContainer", {
        minDate: "today",
        maxDate: new Date(new Date().getFullYear(), 11, 31),
        dateFormat: "Y-m-d",
        disable: [
            function (date) {
                return date.getDay() === 0 || date.getDay() === 6;
            }
        ],
        inline: true,
        onChange: function (selectedDates, dateStr) {
            selectedDate = dateStr;
            updateDateTime();
        }
    });

    // Handle time slot dropdown selection
    document.getElementById('con_timeSlotsDropdown').addEventListener('change', function () {
        selectedTime = this.value;
        updateDateTime();
    });

    // Function to update the combined date and time
    function updateDateTime() {
        if (selectedDate && selectedTime) {
            const dateTime = `${selectedDate} ${selectedTime}`;
            document.getElementById('con_selectedDateTime').value = dateTime;
        }
    }
    });

</script> --}}


{{-- sweetalert --}}
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif


{{-- for vaccination --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
    let selectedDate = null;
    let selectedTime = null;

    // Show calendar when button is clicked
    document.getElementById('vax_showCalendarBtn').addEventListener('click', function () {
        document.getElementById('vax_datepickerWrapper').classList.remove('hidden');
        this.style.display = 'none'; // Hide button after showing the calendar
    });

    // Initialize Flatpickr inline
    flatpickr("#vax_datepickerContainer", {
        minDate: "today",
        maxDate: new Date(new Date().getFullYear(), 11, 31),
        dateFormat: "Y-m-d",
        disable: [
            function (date) {
                return date.getDay() === 0 || date.getDay() === 6;
            }
        ],
        inline: true,
        onChange: function (selectedDates, dateStr) {
            selectedDate = dateStr;
            updateDateTime();
        }
    });

    // Handle time slot dropdown selection
    document.getElementById('vax_timeSlotsDropdown').addEventListener('change', function () {
        selectedTime = this.value;
        updateDateTime();
    });

    // Function to update the combined date and time
    function updateDateTime() {
        if (selectedDate && selectedTime) {
            const dateTime = `${selectedDate} ${selectedTime}`;
            document.getElementById('vax_selectedDateTime').value = dateTime;
        }
    }
    });
</script> --}}

{{-- for blood --}}
{{-- <script>
   document.addEventListener('DOMContentLoaded', function () {
    let selectedDate = null;
    let selectedTime = null;

    // Show calendar when button is clicked
    document.getElementById('blood_showCalendarBtn').addEventListener('click', function () {
        document.getElementById('blood_datepickerWrapper').classList.remove('hidden');
        this.style.display = 'none'; // Hide button after showing the calendar
    });

    // Initialize Flatpickr inline
    flatpickr("#blood_datepickerContainer", {
        minDate: "today",
        maxDate: new Date(new Date().getFullYear(), 11, 31),
        dateFormat: "Y-m-d",
        disable: [
            function (date) {
                return date.getDay() === 0 || date.getDay() === 6;
            }
        ],
        inline: true,
        onChange: function (selectedDates, dateStr) {
            selectedDate = dateStr;
            updateDateTime();
        }
    });

    // Handle time slot dropdown selection
    document.getElementById('blood_timeSlotsDropdown').addEventListener('change', function () {
        selectedTime = this.value;
        updateDateTime();
    });

    // Function to update the combined date and time
    function updateDateTime() {
        if (selectedDate && selectedTime) {
            const dateTime = `${selectedDate} ${selectedTime}`;
            document.getElementById('blood_selectedDateTime').value = dateTime;
        }
    }
    });
</script> --}}

{{-- date picker design --}}
<script>
    $(document).ready(function() {
        // Initialize the datepicker with current year constraint
        $('#appointmentDate').datepicker({
            dateFormat: 'mm/dd/yy', // Format month/day/year
            minDate: new Date(), // Prevent past dates
            maxDate: new Date(new Date().getFullYear(), 11, 31), // Set max date to the end of current year
            changeMonth: true, // Allow changing the month
            changeYear: false, // Disable changing the year (locked to the current year)
            showButtonPanel: true
        });
    });
</script>

{{-- formms for appointment --}}
<script>
    function showForm() {
    // Hide all forms
        document.querySelectorAll('.form-container').forEach(form => form.classList.add('hidden'));

        // Get selected value
        const selectedService = document.getElementById('serviceSelect').value;

        // Show the selected form
        if (selectedService) {
            const formToShow = document.getElementById(`${selectedService}Form`);
            if (formToShow) {
                formToShow.classList.remove('hidden');
            }
        }
    }
</script>



{{-- for time picker laboratory --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedDate = null;
        let selectedTime = null;

        const bookedAppointments = @json($bookedAppointments1);

        flatpickr("#lab_datepickerContainer", {
            minDate: "today",
            maxDate: new Date(new Date().getFullYear(), 11, 31),
            dateFormat: "Y-m-d",
            disable: [
                function (date) {
                    return date.getDay() === 0 || date.getDay() === 6; // Disable weekends
                }
            ],
            inline: true,
            onChange: function (selectedDates, dateStr) {
                selectedDate = dateStr;
                updateAvailableTimeSlots();
                updateDateTime();
            }
        });

        document.getElementById('lab_timeSlotsDropdown').addEventListener('change', function () {
            selectedTime = this.value;
            updateDateTime();
        });

        function updateAvailableTimeSlots() {
            const timeSlotDropdown = document.getElementById('lab_timeSlotsDropdown');
            const bookedTimes = bookedAppointments[selectedDate] || [];

            Array.from(timeSlotDropdown.options).forEach(option => {
                option.disabled = false;
            });

            bookedTimes.forEach(time => {
                Array.from(timeSlotDropdown.options).forEach(option => {
                    if (option.value === time) {
                        option.disabled = true;
                    }
                });
            });

            timeSlotDropdown.value = ""; // Reset dropdown selection
        }

        function updateDateTime() {
            if (selectedDate && selectedTime) {
                document.getElementById('lab_selectedDateTime').value = `${selectedDate} ${selectedTime}`;
            }
        }
    });
</script>

{{-- consultation --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedDate = null;
        let selectedTime = null;

        const bookedAppointments = @json($bookedAppointments2);

        flatpickr("#con_datepickerContainer", {
            minDate: "today",
            maxDate: new Date(new Date().getFullYear(), 11, 31),
            dateFormat: "Y-m-d",
            disable: [
                function (date) {
                    return date.getDay() === 0 || date.getDay() === 6; // Disable weekends
                }
            ],
            inline: true,
            onChange: function (selectedDates, dateStr) {
                selectedDate = dateStr;
                updateAvailableTimeSlots();
                updateDateTime();
            }
        });

        document.getElementById('con_timeSlotsDropdown').addEventListener('change', function () {
            selectedTime = this.value;
            updateDateTime();
        });

        function updateAvailableTimeSlots() {
            const timeSlotDropdown = document.getElementById('con_timeSlotsDropdown');
            const bookedTimes = bookedAppointments[selectedDate] || [];

            Array.from(timeSlotDropdown.options).forEach(option => {
                option.disabled = false;
            });

            bookedTimes.forEach(time => {
                Array.from(timeSlotDropdown.options).forEach(option => {
                    if (option.value === time) {
                        option.disabled = true;
                    }
                });
            });

            timeSlotDropdown.value = ""; // Reset dropdown selection
        }

        function updateDateTime() {
            if (selectedDate && selectedTime) {
                document.getElementById('con_selectedDateTime').value = `${selectedDate} ${selectedTime}`;
            }
        }
    });
</script>

{{-- for time picker vax --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedDate = null;
        let selectedTime = null;

        const bookedAppointments = @json($bookedAppointments3);


        flatpickr("#vax_datepickerContainer", {
            minDate: "today",
            maxDate: new Date(new Date().getFullYear(), 11, 31),
            dateFormat: "Y-m-d",
            disable: [
                function (date) {
                    return date.getDay() === 0 || date.getDay() === 6; // Disable weekends
                }
            ],
            inline: true,
            onChange: function (selectedDates, dateStr) {
                selectedDate = dateStr;
                updateAvailableTimeSlots();
                updateDateTime();
            }
        });

        document.getElementById('vax_timeSlotsDropdown').addEventListener('change', function () {
            selectedTime = this.value;
            updateDateTime();
        });

        function updateAvailableTimeSlots() {
            const timeSlotDropdown = document.getElementById('vax_timeSlotsDropdown');
            const bookedTimes = bookedAppointments[selectedDate] || [];

            Array.from(timeSlotDropdown.options).forEach(option => {
                option.disabled = false;
            });

            bookedTimes.forEach(time => {
                Array.from(timeSlotDropdown.options).forEach(option => {
                    if (option.value === time) {
                        option.disabled = true;
                    }
                });
            });

            timeSlotDropdown.value = ""; // Reset dropdown selection
        }

        function updateDateTime() {
            if (selectedDate && selectedTime) {
                document.getElementById('vax_selectedDateTime').value = `${selectedDate} ${selectedTime}`;
            }
        }
    });
</script>

{{-- blood --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let selectedDate = null;
        let selectedTime = null;

        const bookedAppointments = @json($bookedAppointments4);

        flatpickr("#blood_datepickerContainer", {
            minDate: "today",
            maxDate: new Date(new Date().getFullYear(), 11, 31),
            dateFormat: "Y-m-d",
            disable: [
                function (date) {
                    return date.getDay() === 0 || date.getDay() === 6; // Disable weekends
                }
            ],
            inline: true,
            onChange: function (selectedDates, dateStr) {
                selectedDate = dateStr;
                updateAvailableTimeSlots();
                updateDateTime();
            }
        });

        document.getElementById('blood_timeSlotsDropdown').addEventListener('change', function () {
            selectedTime = this.value;
            updateDateTime();
        });

        function updateAvailableTimeSlots() {
            const timeSlotDropdown = document.getElementById('blood_timeSlotsDropdown');
            const bookedTimes = bookedAppointments[selectedDate] || [];

            Array.from(timeSlotDropdown.options).forEach(option => {
                option.disabled = false;
            });

            bookedTimes.forEach(time => {
                Array.from(timeSlotDropdown.options).forEach(option => {
                    if (option.value === time) {
                        option.disabled = true;
                    }
                });
            });

            timeSlotDropdown.value = ""; // Reset dropdown selection
        }

        function updateDateTime() {
            if (selectedDate && selectedTime) {
                document.getElementById('blood_selectedDateTime').value = `${selectedDate} ${selectedTime}`;
            }
        }
    });
</script>
