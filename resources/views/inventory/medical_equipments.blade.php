<x-layout>

    <div class="manage-window-card">

        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Manage Medical Equipments</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 490px;" placeholder="Search..." />

                    <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
                <div class="card-body mw-table-body">
                    <!-- Add Modal -->
                    <div class="modal fade rhu-modal" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title font-semibold text-black" id="addEquipmentLabel">Add New Medical Equipment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                
                                <!-- Add Modal Form -->
                                <form id="addEquipmentForm" method="POST" action="/add-medical_equipments">
                                    @csrf
                                    <div class="modal-body">
                                        <h6 class="mb-3">Medical Equipment Details</h6>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control w-100" name="name" id="name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="batchNo" class="form-label">Batch/ Lot No.</label>
                                                <input type="text" class="form-control w-100" name="batchNo" id="batchNo" required>
                                            </div>
                                        </div>
                
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="location_code" class="form-label">Location Area Code/ Rack Number</label>
                                                <input type="text" class="form-control w-100" name="location_code" id="location_code" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="quantity" class="form-label">Quantity</label>
                                                <input type="text" class="form-control w-100 quantity-input" name="quantity" id="quantity" required>
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
                

<!-- Edit Modal -->
<div class="modal fade rhu-modal" id="editEquipmentAccount" tabindex="-1" aria-labelledby="editEquipmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title font-semibold text-black" id="editEquipmentLabel">Edit Medical Equipment Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Edit Modal Form -->
            <form id="editEquipmentForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Medical Equipment Information -->
                    <h6 class="mb-3">Medical Equipment Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control w-100" name="name" id="edit_name">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_batchNo" class="form-label">Batch/ Lot No.</label>
                            <input type="text" class="form-control w-100" name="batchNo" id="edit_batchNo">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_location_code" class="form-label">Location Area Code/ Rack Number</label>
                            <input type="text" class="form-control w-100" name="location_code" id="edit_location_code" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="text" class="form-control w-100 quantity-input" name="quantity" id="edit_quantity" required>
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
                                <th>Equipment Name</th>
                                <th>Batch. Lot No.</th>
                                <th>Location Area Code/ Rack Number</th>
                                <th>Quantity</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($medical_equipments->isNotEmpty())
                                    @foreach ($medical_equipments as $medical_equipment)
                                        <tr class="mw-column-name">
                                            <td>{{ $medical_equipment->name }}</td>
                                            <td>{{ $medical_equipment->batchNo }}</td>
                                            <td>{{ $medical_equipment->location_code }}</td>
                                            <td>{{ $medical_equipment->quantity }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <!-- Edit Button -->
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editEquipmentAccount"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        onclick="editEquipmentAccount({{ $medical_equipment->id }})">
                                                        <i class="fa fa-pen mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-link btn-danger" onclick="confirmDeleteEquipment({{ $medical_equipment->id }})">
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

    <script>


        // Edit shared window
        function editEquipmentAccount(pId) {
        const selectedClient = @json($medical_equipments).find(medical_equipment => medical_equipment.id == pId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('edit_name').value = selectedClient.name || '';
            document.getElementById('edit_batchNo').value = selectedClient.batchNo || '';
            document.getElementById('edit_location_code').value = selectedClient.location_code || '';
            document.getElementById('edit_quantity').value = selectedClient.quantity || '';

            document.getElementById('editEquipmentForm').action = `/update-medical_equipments/${pId}`;
            } else {
                console.error('Selected client not found.');
            }
        }


        // delete method
        function confirmDeleteEquipment(clientId) {
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
                    fetch(`/delete-medical_equipments/${clientId}`, {
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
