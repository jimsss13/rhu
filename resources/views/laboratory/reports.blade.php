<x-layout>

    <div class="manage-window-card">
        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mw-title">Report</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 460px;" placeholder="Search..." />
                    <div class=" d-flex align-items-center">
                        <a href="{{ url('/export/lab_appointments') }}" id="exportAppointments" class="btn btn-sm mr-2" style="border: 1px solid; border-radius: 10px;">Export</a>
                        <a href="{{ url('/export/lab_blood-donation') }}" id="exportBloodDonation" class="btn btn-sm mr-2" style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                        <a href="{{ url('/export/testList') }}" id="exportTestList" class="btn btn-sm mr-2" style="border: 1px solid; border-radius: 10px; display: none;">Export</a>


                        <select id="serviceSelect" class="form-select form-select-lg" aria-label="Service Selection" onchange="showTable()">
                            <option value="Appointments" selected>Appointments</option>
                            <option value="BloodDonation">Patient Records</option>
                            <option value="TestList">Test List</option>

                        </select>
                    </div>

                </div>
            </div>
            <div class="card-body mw-table-body">

                {{-- This is for Appointments --}}
                <div class="table-responsive mw-table-content" id="Appointments">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Patient Name</th>
                                <th>Test</th>
                                <th>Appointment Date</th>
                                {{-- <th>Contact Number</th> --}}
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($blood_appointments->isNotEmpty())
                                @foreach ($blood_appointments as $blood_appointment)
                                    <tr class="mw-column-name">
                                        <td>{{ $blood_appointment->name }}</td>
                                        <td>{{ $blood_appointment->sub_type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($blood_appointment->date)->format('Y-m-d g:i A') }}</td>
                                        {{-- <td>{{ $blood_appointment->contactNo }}</td> --}}
                                        <td>{{ $blood_appointment->status }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="mw-column-name">
                                    <td colspan="3" class="text-center">No client available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- This is for Blood Donation --}}
                <div class="table-responsive mw-table-content" id="BloodDonation" style="display: none;">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Name</th>
                                <th>Test</th>
                                <th>Result</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">

                            @if ($patients->isNotEmpty())
                                @foreach ($patients as $patient)
                                    <tr class="mw-column-name">
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->sub_type }}</td>
                                        <td>
                                            <span style="display: inline-block; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ asset('storage/' . $patient->result) }}
                                            </span>
                                            <a href="{{ asset('storage/' . $patient->result) }}" target="_blank" class="btn btn-info btn-sm d-inline">Preview</a>
                                            <a href="{{ asset('storage/' . $patient->result) }}" class="btn btn-primary btn-sm d-inline" download>Download</a>
                                        </td>

                                        <td>{{ \Carbon\Carbon::parse($patient->date)->format('Y-m-d') }}</td>
                                        <td>{{ $patient->status }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="mw-column-name">
                                    <td colspan="3" class="text-center">No Patient available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                 {{-- This is for Test List --}}
                 <div class="table-responsive mw-table-content" id="TestList" style="display: none;">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($tests->isNotEmpty())
                                @foreach ($tests as $test)
                                    <tr class="mw-column-name">
                                        <td>{{ $test->name }}</td>
                                        <td>{{ $test->price }}</td>
                                        <td>{{ $test->status }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="mw-column-name">
                                    <td colspan="3" class="text-center">No Patient available</td>
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

    </x-layout>
