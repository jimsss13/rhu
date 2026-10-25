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
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Manage Medical Supplies</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 530px;" placeholder="Search..." />
                    <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addSuppliesModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body mw-table-body">
                <!-- Add Modal -->
                <div class="modal fade rhu-modal" id="addSuppliesModal" tabindex="-1" aria-labelledby="addSuppliesLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold text-black" id="addSuppliesLabel">Add New Medical Supply</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
            
                            <!-- Add Modal Form -->
                            <form id="addSuppliesForm" method="POST" action="/add-medical_supplies">
                                @csrf
                                <div class="modal-body">
                                    <h6 class="mb-3">Medical Supplies Details</h6>
            
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control w-100" name="name" id="name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="batchNo" class="form-label">Batch. Lot No.</label>
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
<div class="modal fade rhu-modal" id="editSuppliesAccount" tabindex="-1" aria-labelledby="editSuppliesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title font-semibold text-black" id="editSuppliesLabel">Edit Medical Supply Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Edit Modal Form -->
            <form id="editSuppliesForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Medical Supply Information -->
                    <h6 class="mb-3">Medical Supply Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control w-100" name="name" id="edit_name">
                        </div>
                        <div class="col-md-6">
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
                                <th>Supply Name</th>
                                <th>Batch. Lot No.</th>
                                <th>Location Area Code/ Rack Number</th>
                                <th>Quantity</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($medical_supplies->isNotEmpty())
                                    @foreach ($medical_supplies as $medical_supplie)
                                        <tr class="mw-column-name">
                                            <td>{{ $medical_supplie->name }}</td>
                                            <td>{{ $medical_supplie->batchNo }}</td>
                                            <td>{{ $medical_supplie->location_code }}</td>
                                            <td>{{ $medical_supplie->quantity }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <!-- Edit Button -->
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editSuppliesAccount"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        onclick="editSuppliesAccount({{ $medical_supplie->id }})">
                                                        <i class="fa fa-pen mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-link btn-danger" onclick="confirmDeleteSupplies({{ $medical_supplie->id }})">
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

    <script>


        // Edit shared window
        function editSuppliesAccount(pId) {
        const selectedClient = @json($medical_supplies).find(medical_supplie => medical_supplie.id == pId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('edit_name').value = selectedClient.name || '';
            document.getElementById('edit_batchNo').value = selectedClient.batchNo || '';
            document.getElementById('edit_location_code').value = selectedClient.location_code || '';
            document.getElementById('edit_quantity').value = selectedClient.quantity || '';


            document.getElementById('editSuppliesForm').action = `/update-medical_supplies/${pId}`;
            } else {
                console.error('Selected client not found.');
            }
        }


        // delete method
        function confirmDeleteSupplies(clientId) {
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
                    fetch(`/delete-medical_supplies/${clientId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'The client has been deleted.',
                                'success'
                            );
                            // Optionally refresh the page or update the UI
                            location.reload();
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
