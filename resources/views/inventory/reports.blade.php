<x-layout>

    <div class="manage-window-card">
        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="mw-title">Report</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 430px;" placeholder="Search..."/>
                    <div class="d-flex align-items-center">

                        {{-- to be continue --}}
                        <a href="{{ url('/export/medicines') }}" id="exportmedicines" class="btn btn-sm mr-2" style="border: 1px solid; border-radius: 10px;">Export</a>
                        <a href="{{ url('/export/medicalSupplies') }}" id="exportmedicalSupplies" class="btn btn-sm mr-2" style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                        <a href="{{ url('/export/medicalEquipments') }}" id="exportmedicalEquipments" class="btn btn-sm mr-2" style="border: 1px solid; border-radius: 10px; display: none;">Export</a>
                        <a href="{{ url('/export/vaccines') }}" id="exportvaccines" class="btn btn-sm mr-2" style="border: 1px solid; border-radius: 10px; display: none;">Export</a>


                        <select id="serviceSelect" class="form-select form-select-lg" aria-label="Service Selection" onchange="showTable()">
                            <option value="medicines" selected>Medicines</option>
                            <option value="medicalSupplies">Medical Supplies</option>
                            <option value="medicalEquipments">Medical Equipments</option>
                            <option value="vaccines">Vaccines</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="card-body mw-table-body">

                {{-- This is for Appointments --}}
                <div class="table-responsive mw-table-content" id="medicines">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Medicine Name</th>
                                <th>Dosage Form</th>
                                <th>Dosage Strenght</th>
                                <th>Location Area Code/ Rack Number</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($medicines->isNotEmpty())
                                @foreach ($medicines as $medicine)
                                    <tr class="mw-column-name">
                                        <td>{{ $medicine->name }}</td>
                                        <td>{{ $medicine->dosage_f }}</td>
                                        <td>{{ $medicine->dosage_s }}</td>
                                        {{-- <td>{{ \Carbon\Carbon::parse($blood_appointment->date)->format('Y-m-d g:i A') }}</td> --}}
                                        <td>{{ $medicine->location_code }}</td>
                                        <td>{{ $medicine->quantity }}</td>
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

                {{-- This is for medicalSupplies --}}
                <div class="table-responsive mw-table-content" id="medicalSupplies" style="display: none;">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Medicine Name</th>
                                <th>Batch. Lot No.</th>
                                <th>Location Area Code/ Rack Number</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($medicalSupplies->isNotEmpty())
                                @foreach ($medicalSupplies as $medicalSupplie)
                                    <tr class="mw-column-name">
                                        <td>{{ $medicalSupplie->name }}</td>
                                        <td>{{ $medicalSupplie->batchNo }}</td>
                                        <td>{{ $medicalSupplie->location_code }}</td>
                                        <td>{{ $medicalSupplie->quantity }}</td>
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

                {{-- This is for medicalEquipments --}}
                <div class="table-responsive mw-table-content" id="medicalEquipments" style="display: none;">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Equipment Name</th>
                                <th>Batch. Lot No.</th>
                                <th>Location Area Code/ Rack Number</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($medicalEquipments->isNotEmpty())
                                @foreach ($medicalEquipments as $medicalEquipment)
                                    <tr class="mw-column-name">
                                        <td>{{ $medicalEquipment->name }}</td>
                                        <td>{{ $medicalEquipment->batchNo }}</td>
                                        <td>{{ $medicalEquipment->location_code }}</td>
                                        <td>{{ $medicalEquipment->quantity }}</td>
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

                {{-- This is for vaccines --}}
                <div class="table-responsive mw-table-content" id="vaccines" style="display: none;">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Medicine Name</th>
                                <th>Expiration Date</th>
                                <th>Location Area Code/ Rack Number</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($vaccines->isNotEmpty())
                                @foreach ($vaccines as $vaccine)
                                    <tr class="mw-column-name">
                                        <td>{{ $vaccine->name }}</td>
                                        {{-- <td>{{ $vaccine->expiration_date }}</td> --}}
                                        <td>{{ \Carbon\Carbon::parse($vaccine->expiration_date)->format('Y-m-d') }}</td>
                                        <td>{{ $vaccine->location_code }}</td>
                                        <td>{{ $vaccine->quantity }}</td>
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

    </x-layout>
