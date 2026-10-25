<x-layout>

    <div class="manage-window-card">
    {{-- sweet alert for error handling --}}
    @if ($errors->has('editWName'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $errors->first('editWName') }}',
            timer: 3000, // Auto-close after 3 seconds
            showConfirmButton: false
        });
    </script>
    @endif

    @if ($errors->has('editPersonnel'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $errors->first('editPersonnel') }}',
            timer: 3000, // Auto-close after 3 seconds
            showConfirmButton: false
        });
    </script>
    @endif

    @if ($errors->has('editSWName'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $errors->first('editSWName') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if (session('sweetalert'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('sweetalert') }}',
        });
    </script>
    @endif

{{-- for manage window --}}
<div class="card mw-table">
    <div class="mw-header">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="mw-title">Patient Appointments</h4>
            {{-- <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="fa fa-plus"></i>
            </button> --}}
            <input type="text" id="search" class="form-control" style="margin-left: 350px;" placeholder="Search..." />
            <div class="d-flex align-items-center">
                <a href="{{ url('/export/lab_appointments') }}" id="exportLaboratory" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px;">Export</a>
                <a href="{{ url('/export/con_appointments') }}" id="exportConsultation" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                <a href="{{ url('/export/vax_appointments') }}" id="exportVaccination" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                <a href="{{ url('/export/b_appointments') }}" id="exportBlood" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>

                <select id="serviceSelect" class="form-select form-select-lg " aria-label="Service Selection" onchange="showTable()">
                    <option value="Laboratory" selected>Laboratory</option>
                    <option value="Consultation">Consultation</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Blood">Blood</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body mw-table-body">

          {{-- This is for Laboratory --}}
          <div class="table-responsive mw-table-content" id="Laboratory">
            <table id="add-row" class="display table">
                <thead>
                    <tr class="mw-column-name">
                        {{-- <th>#</th> --}}
                        <th>Name</th>
                        <th>Test</th>
                        <th>Date</th>
                        {{-- <th>Contact No</th> --}}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">

                    @php
                        // Define an array to map vaccine types to their full names
                        $laboratoryTests = [
                            'fecalysis' => 'Fecalysis',
                            'blood_typing' => 'Blood Typing',
                            'urinalysis' => 'Urinalysis',
                            'sputum' => 'Sputum',
                        ];
                    @endphp

                    @if ($appointments->isNotEmpty())
                        @foreach ($appointments->where('type', 'laboratory') as $appointment)
                            <tr>
                                <td>{{ $appointment->name }}</td>
                                <td>{{ $laboratoryTests[$appointment->sub_type] ?? $appointment->sub_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A') }}</td>
                                {{-- <td>{{ $appointment->contactNo }}</td> --}}
                                <td>{{ $appointment->status }}</td> <!-- Assuming 'status' is a field in your model -->
                            </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No appointments available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

          {{-- This is for Consultation --}}
          <div class="table-responsive mw-table-content" id="Consultation" style="display: none;">
            <table id="add-row" class="display table">
                <thead>
                    <tr class="mw-column-name">
                        <th>Name</th>
                        <th>Mode of consultation</th>
                        <th>Date</th>
                        {{-- <th>Contact Number</th> --}}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">
                    @if ($appointments->isNotEmpty())
                        @foreach ($appointments->where('type', 'consultation') as $appointment)
                            <tr>
                                <td>{{ $appointment->name }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $appointment->sub_type)) }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A')}}</td>
                                {{-- <td>{{ $appointment->contactNo }}</td> --}}
                                <td>{{ $appointment->status }}</td> <!-- Assuming 'status' is a field in your model -->
                            </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No appointments available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- This is for Vaccination --}}
        <div class="table-responsive mw-table-content" id="Vaccination" style="display: none;">
            <table id="add-row" class="display table">
                <thead>
                    <tr class="mw-column-name">
                        {{-- <th>#</th> --}}
                        <th>Name</th>
                        <th>Vaccine Type</th>
                        <th>Dose Number</th>
                        {{-- <th>Address</th> --}}
                        <th>Date</th>
                        {{-- <th>Contact No</th> --}}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">

                    @php
                        // Define an array to map vaccine types to their full names
                        $vaccineTypes = [
                            'hepatitis_b' => 'Hepatitis B',
                            'measles_mumps_rubella' => 'Measles, Mumps, Rubella (MMR)',
                            'chickenpox' => 'Chickenpox (Varicella)',
                            'polio' => 'Polio',
                            'tetanus' => 'Tetanus',
                            'diphtheria' => 'Diphtheria',
                            'hpv' => 'Human Papillomavirus (HPV)',
                            'influenza' => 'Influenza (Flu)',
                            'pneumococcal' => 'Pneumococcal',
                            'covid_19' => 'COVID-19',
                        ];
                    @endphp

                    @if ($appointments->isNotEmpty())
                        @foreach ($appointments->where('type', 'vaccination') as $appointment)
                            <tr>
                                <td>{{ $appointment->name }}</td>
                                <td>{{ $vaccineTypes[$appointment->sub_type] ?? $appointment->sub_type }}</td>
                                <td>{{ $appointment->dose_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A')}}</td>
                                {{-- <td>{{ $appointment->contactNo }}</td> --}}
                                <td>{{ $appointment->status }}</td> <!-- Assuming 'status' is a field in your model -->
                            </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No appointments available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- This is for Vaccination --}}
        <div class="table-responsive mw-table-content" id="Blood" style="display: none;">
            <table id="add-row" class="display table">
                <thead>
                    <tr class="mw-column-name">
                        <th>Name</th>
                        <th>Blood Type</th>
                        <th>Date</th>
                        {{-- <th>Contact Number</th> --}}
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">
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
                    @if ($appointments->isNotEmpty())
                        @foreach ($appointments->where('type', 'blood') as $appointment)
                            <tr>
                                <td>{{ $appointment->name }}</td>
                                <td>{{ $bloodTypes[$appointment->sub_type] ?? $appointment->sub_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('Y-m-d g:i A') }}</td>
                                {{-- <td>{{ $appointment->contactNo }}</td> --}}
                                <td>{{ $appointment->status }}</td> <!-- Assuming 'status' is a field in your model -->
                            </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No appointments available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>


    </div>

    {{-- for showing table --}}
    <script>
         function showTable() {
            const selectedValue = document.getElementById('serviceSelect').value;

            // Hide all tables
            const tables = document.querySelectorAll('.mw-table-content');
            tables.forEach(table => {
                table.style.display = 'none';
            });

            // Hide all export buttons
            const exportButtons = document.querySelectorAll('[id^="export"]');
            exportButtons.forEach(button => {
                button.style.display = 'none';
            });

            // Show the selected table
            if (selectedValue) {
                const selectedTable = document.getElementById(selectedValue);
                if (selectedTable) {
                    selectedTable.style.display = 'block';

                    // Show the corresponding export button
                    const exportButton = document.getElementById('export' + selectedValue);
                    if (exportButton) {
                        exportButton.style.display = 'inline-block';
                    }
                }
            }
        }
    </script>


    {{-- sweetalert for delete --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}', // Display the success message from the session
                timer: 3000, // Auto-close after 3 seconds
                showConfirmButton: false
            });
        @endif

        @if ($errors->has('editUsername'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ $errors->first() }}', // Display the first error message
                timer: 3000, // Auto-close after 3 seconds
                showConfirmButton: false
            });
        @endif
    </script>




    {{-- INPUT VALIDATION --}}
    <script>
        // format input
        function formatInput(input) {
            // Remove leading spaces
            input.value = input.value.replace(/^\s+/g, '');

            // Replace multiple spaces with a single space
            input.value = input.value.replace(/\s+/g, ' ');

            // Convert to uppercase
            input.value = input.value.toUpperCase();
        }
    </script>

    {{-- CRUD --}}
    <script>
        function setDropdownValue(dropdown, selectedValue, displayText = null) {
            // Check if the selected value exists in the dropdown
            let optionExists = false;
            Array.from(dropdown.options).forEach(option => {
                if (option.value == selectedValue) {
                    optionExists = true;
                    dropdown.value = selectedValue; // Set the value if it exists
                }
            });

            // Add a new hidden option if the value doesn't exist
            if (!optionExists) {
                const newOption = new Option(displayText || selectedValue, selectedValue);
                newOption.style.display = "none"; // Hide the new option
                dropdown.add(newOption);
                dropdown.value = selectedValue; // Set the new value as selected
            }
        }

    </script>

    </x-layout>
