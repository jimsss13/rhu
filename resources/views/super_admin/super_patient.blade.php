<x-layout>

    <div class="manage-window-card">

{{-- for manage window --}}
<div class="card mw-table">
    <div class="mw-header">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="mw-title">Patient Records</h4>
            <input type="text" id="search" class="form-control" style="margin-left: 400px;" placeholder="Search..." />
            {{-- <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addClientModal">
                <i class="fa fa-plus"></i>
            </button> --}}
            <div class="d-flex align-items-center">
                <a href="{{ url('/export/laboratory') }}" id="exportLaboratory" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px;">Export</a>
                <a href="{{ url('/export/consultation') }}" id="exportConsultation" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                <a href="{{ url('/export/vaccination') }}" id="exportVaccination" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                <a href="{{ url('/export/blood') }}" id="exportBlood" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>

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
                                <th>Result</th>
                                <th>Date</th>
                                <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">

                    @if ($patients->isNotEmpty())
                        @foreach ($patients->where('type', 'laboratory') as $patient)
                            <tr>
                                <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->sub_type }}</td>
                                        <td>
                                            <span style="display: inline-block; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ asset('storage/' . $patient->result) }}
                                            </span>
                                            <a href="{{ asset('storage/' . $patient->result) }}" class="btn btn-primary btn-sm d-inline" download>Download Test Result</a>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($patient->date)->format('Y-m-d') }}</td>
                                        <td>{{ $patient->status }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No patients available</td>
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
                        <th>Mode of Consultation</th>
                        <th>Doctor</th>
                        <th>Doctor's Analysis</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">
                    @if ($patients->isNotEmpty())
                        @foreach ($patients->where('type', 'consultation') as $patient)
                        <tr class="mw-column-name">
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->sub_type }}</td>
                            <td>{{ $patient->doctor}}</td>
                            <td>{{ $patient->analysis}}</td>
                            <td>{{ $patient->date}}</td>
                            <td>{{ $patient->status }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No patients available</td>
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
                        <th>Name</th>
                        <th>Vaccine type</th>
                        <th>Dose Number</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">

                    @if ($patients->isNotEmpty())
                        @foreach ($patients->where('type', 'vaccination') as $patient)
                            <tr>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->sub_type }}</td>
                                <td>{{ $patient->dose_number}}</td>
                                <td>{{ \Carbon\Carbon::parse($patient->date)->format('Y-m-d') }}</td>
                                {{-- <td>{{ $patient->date}}</td> --}}
                                <td>{{ $patient->status }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No patients available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- This is for Blood --}}
        <div class="table-responsive mw-table-content" id="Blood" style="display: none;">
            <table id="add-row" class="display table">
                <thead>
                    <tr class="mw-column-name">
                        <th>Name</th>
                                <th>Blood type</th>
                                <th>Volume</th>
                                <th>Date</th>
                                <th>Status</th>
                    </tr>
                </thead>
                <tbody class="logs-column-body">

                    @if ($patients->isNotEmpty())
                        @foreach ($patients->where('type', 'blood') as $patient)
                            <tr>
                                <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->sub_type }}</td>
                                        <td>{{ $patient->volume}}ml</td>
                                        <td>{{ $patient->date}}</td>
                                        <td>{{ $patient->status }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="mw-column-name">
                            <td colspan="3" class="text-center">No record available</td>
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
