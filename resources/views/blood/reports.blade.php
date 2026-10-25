<x-layout>

    <div class="manage-window-card">
        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mw-title">Report</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 470px;" placeholder="Search..." />


                    <div class="d-flex align-items-center">
                        <a href="{{ url('/export/b_appointments') }}" id="exportAppointments" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px;">Export</a>
                        <a href="{{ url('/export/b_blood-donation') }}" id="exportBloodDonation" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                        <a href="{{ url('/export/turned_over') }}" id="exportTurnedOver" class="btn btn-sm mr-2 " style="border: 1px solid; border-radius: 10px; display: none;">Export</a>

                        <select id="serviceSelect" class="form-select form-select-lg " aria-label="Service Selection" onchange="showTable()">
                            <option value="Appointments" selected>Appointments</option>
                            <option value="BloodDonation">Blood Donation</option>
                            <option value="TurnedOver">Turned Over</option>
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
                                <th>Blood Type</th>
                                <th>Date</th>
                                {{-- <th>Contact Number</th> --}}
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @php
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
                            @if ($blood_appointments->isNotEmpty())
                                @foreach ($blood_appointments as $blood_appointment)
                                    <tr class="mw-column-name">
                                        <td>{{ $blood_appointment->name }}</td>
                                        <td>{{ $bloodTypes[$blood_appointment->sub_type] ?? $blood_appointment->sub_type }}</td>
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
                                <th>Blood type</th>
                                <th>Volume</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @php
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

                            @if ($patients->isNotEmpty())
                                @foreach ($patients as $patient)
                                    <tr class="mw-column-name">
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $bloodTypes[$patient->sub_type] ?? $patient->sub_type }}</td>
                                        <td>{{ $patient->volume}}ml</td>
                                        <td>{{ $patient->date}}</td>
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

                {{-- This is for Turned Over --}}
                <div class="table-responsive mw-table-content" id="TurnedOver" style="display: none;">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Blood Bank Name</th>
                                <th>Blood Type</th>
                                <th>Date</th>
                                <th>Volume</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($turned_overs->isNotEmpty())
                                @foreach ($turned_overs as $turned_over)
                                    <tr class="mw-column-name">
                                        <td>{{ $turned_over->name }}</td>
                                        <td>{{ $turned_over->blood_type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($turned_over->date)->format('Y-m-d') }}</td>
                                        <td>{{ $turned_over->volume }}ml</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="mw-column-name">
                                    <td colspan="3" class="text-center">No Record available</td>
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
