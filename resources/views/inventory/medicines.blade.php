<x-layout>

    <div class="manage-window-card">


        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Manage Medicine Supply</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 530px;" placeholder="Search..." />
                    <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body mw-table-body">
                <!-- Add Modal -->
                <div class="modal fade rhu-modal" id="addMedicineModal" tabindex="-1" aria-labelledby="addMedicineLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold text-black" id="addMedicineLabel">Add New Medicine</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
            
                            <!-- Add Modal Form -->
                            <form id="addMedicineForm" method="POST" action="/add-medicines">
                                @csrf
                                <div class="modal-body">
                                    <h6 class="mb-3">Medicine Details</h6>
            
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="name" class="form-label">Item Description</label>
                                            <input type="text" class="form-control w-100" name="name" id="name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="expiration_date" class="form-label">Expiration Date</label>
                                            <input type="date" class="form-control w-100" name="expiration_date" id="expiration_date" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="end_user" class="form-label">End User</label>
                                            <input type="text" class="form-control w-100" name="end_user" id="end_user" required>
                                        </div>
                                    </div>
            
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="dosage_f" class="form-label">Dosage Form</label>
                                            <select class="form-select" name="dosage_f" id="dosage_f" required>
                                                <option value="Tablet">Tablet</option>
                                                <option value="Capsule">Capsule</option>
                                                <option value="Syrup">Syrup</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dosage_s" class="form-label">Dosage Strength</label>
                                            <select class="form-select" name="dosage_s" id="dosage_s" required>
                                                <option value="100mg">100mg</option>
                                                <option value="250mg">250mg</option>
                                                <option value="500mg">500mg</option>
                                                <option value="1g">1g</option>
                                                <option value="5ml">5ml</option>
                                                <option value="10ml">10ml</option>
                                            </select>
                                        </div>
                                    </div>
            
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="location_code" class="form-label">Location Area Code/ Rack Number</label>
                                            <input type="text" class="form-control w-100" name="location_code" id="location_code" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="text" class="form-control w-100" name="quantity" id="quantity" required>
                                        </div>
                                    </div>
                                </div>
            
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary px-4">Save</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            

                <!-- Add Medicine Modal -->
<div class="modal fade rhu-modal" id="editMedicineAccount" tabindex="-1" aria-labelledby="editMedicineLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title font-semibold text-black" id="editMedicineLabel">Add Medicine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Edit Modal Form -->
            <form id="editMedicineForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Medicine Information -->
                    <h6 class="mb-3">Medicine's Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_name" class="form-label">Medicine Name</label>
                            <input type="text" class="form-control w-100" name="name" id="edit_name" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="edit_expiration_date" class="form-label">Expiration Date</label>
                            <input type="date" class="form-control w-100" name="expiration_date" id="edit_expiration_date" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_end_user" class="form-label">End User</label>
                            <input type="text" class="form-control w-100" name="end_user" id="edit_end_user" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_dosage_f1" class="form-label">Dosage Form</label>
                            <input type="text" class="form-control w-100" name="dosage_f" id="edit_dosage_f" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_dosage_s1" class="form-label">Dosage Strength</label>
                            <input type="text" class="form-control w-100" name="dosage_s" id="edit_dosage_s" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_location_code" class="form-label">Location Area Code/ Rack Number</label>
                            <input type="text" class="form-control w-100" name="location_code" id="edit_location_code" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control w-100" name="quantity" id="edit_quantity" required>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Turnover Modal -->
<div class="modal fade rhu-modal" id="editMedicineAccount1" tabindex="-1" aria-labelledby="editMedicineLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title font-semibold text-black" id="editMedicineLabel1">Administer Drug</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Edit Modal Form -->
            <form id="editMedicineForm1" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Medicine Information -->
                    <h6 class="mb-3">Medicine's Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_name1" class="form-label">Medicine Name</label>
                            <input type="text" class="form-control w-100" name="name" id="edit_name1" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_expiration_date1" class="form-label">Expiration Date</label>
                            <input type="date" class="form-control w-100" name="expiration_date" id="edit_expiration_date1" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_end_user1" class="form-label">End User</label>
                            <input type="text" class="form-control w-100" name="end_user" id="edit_end_user1" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_dosage_f1" class="form-label">Dosage Form</label>
                            <input type="text" class="form-control w-100" name="dosage_f" id="edit_dosage_f1" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_dosage_s1" class="form-label">Dosage Strength</label>
                            <input type="text" class="form-control w-100" name="dosage_s" id="edit_dosage_s1" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_location_code1" class="form-label">Location Area Code/ Rack Number</label>
                            <input type="text" class="form-control w-100" name="location_code" id="edit_location_code1" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_quantity1" class="form-label">Quantity</label>
                            <input type="text" class="form-control w-100" name="quantity" id="edit_quantity1" required>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



                <!-- Manage Window Table -->
                <div class="table-responsive mw-table-content">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Item Description</th>
                                <th>Dosage Form</th>
                                <th>Dosage Strenght</th>
                                <th>Expiration Date</th>
                                <th>End User</th>
                                <th>Location Area Code/ Rack Number</th>
                                <th>Quantity</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($medicines->isNotEmpty())
                                    @foreach ($medicines as $medicine)
                                        <tr class="mw-column-name">
                                            <td>{{ $medicine->name }}</td>
                                            <td>{{ $medicine->dosage_f }}</td>
                                            <td>{{ $medicine->dosage_s }}</td>
                                            {{-- <td>{{ $medicine->expiration_date }}</td> --}}
                                              <td>{{ \Carbon\Carbon::parse($medicine->expiration_date)->format('Y-m-d') }}</td>

                                            <td>{{ $medicine->end_user }}</td>
                                            <td>{{ $medicine->location_code }}</td>
                                            <td>{{ $medicine->quantity }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <!-- Edit Button -->
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editMedicineAccount"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        onclick="editMedicineAccount({{ $medicine->id }})">
                                                        <i class="fa fa-plus mw-btn-edit"><span class="mw-btn-edit-text">Add</span></i>
                                                    </button>
                                                     <!-- Edit Button -->
                                                     <button type="button" data-bs-toggle="modal" data-bs-target="#editMedicineAccount1"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        onclick="editMedicineAccount1({{ $medicine->id }})">
                                                        <i class="fa fa-handshake mw-btn-edit"><span class="mw-btn-edit-text">Administer</span></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-link btn-danger" onclick="confirmDeleteMedicine({{ $medicine->id }})">
                                                        <i class="fa fa-times"><span class="mw-btn-edit-text">Delete</span></i>
                                                    </button>

                                                </div>
                                            </td>
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
            </div>
        </div>


    </div>


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

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Insufficient stock. Available!',
                text: '{{ session('error') }}', // Display the success message from the session
                timer: 3000, // Auto-close after 3 seconds
                showConfirmButton: false
            });
        @endif

    </script>

    <script>

        function editMedicineAccount(pId) {
        const selectedClient = @json($medicines).find(medicine => medicine.id == pId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('edit_name').value = selectedClient.name || '';
            document.getElementById('edit_dosage_f').value = selectedClient.dosage_f || '';
            document.getElementById('edit_dosage_s').value = selectedClient.dosage_s || '';
            document.getElementById('edit_location_code').value = selectedClient.location_code || '';
            document.getElementById('edit_quantity').value = '';
            document.getElementById('edit_end_user').value = selectedClient.end_user || '';
            document.getElementById('edit_expiration_date').value = selectedClient.expiration_date || '';

            document.getElementById('editMedicineForm').action = `/update-medicines/${pId}`;
            } else {
                console.error('Selected client not found.');
            }
        }


        function editMedicineAccount1(pId) {
        const selectedClient = @json($medicines).find(medicine => medicine.id == pId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('edit_name1').value = selectedClient.name || '';
            document.getElementById('edit_dosage_f1').value = selectedClient.dosage_f || '';
            document.getElementById('edit_dosage_s1').value = selectedClient.dosage_s || '';
            document.getElementById('edit_location_code1').value = selectedClient.location_code || '';
            document.getElementById('edit_end_user1').value = selectedClient.end_user || '';
            document.getElementById('edit_expiration_date1').value = selectedClient.expiration_date || '';

            // document.getElementById('edit_quantity1').value = selectedClient.quantity || '';


            document.getElementById('editMedicineForm1').action = `/update-medicines1/${pId}`;
            } else {
                console.error('Selected client not found.');
            }
        }



        // delete method
        function confirmDeleteMedicine(clientId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send an AJAX request to delete the client
                    fetch(`/delete-medicines/${clientId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Deleted',
                                    text: 'Deleted successfully!',
                                    timer: 2000, // Optional: Auto-close after 2 seconds
                                }).then(() => {
                                    // Wait for 0.5 seconds before reloading the page
                                    setTimeout(() => {
                                        location.reload();
                                    }, 400); // 0.5-second delay
                                });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Something went wrong while deleting the client.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'An unexpected error occurred.',
                            'error'
                        );
                        console.error('Error:', error);
                    });
                }
            });
        }

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
