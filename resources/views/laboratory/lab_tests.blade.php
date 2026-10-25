<x-layout>

    <div class="manage-window-card">


        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Manage Lab Tests</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 610px;" placeholder="Search..." />
                    <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addTestModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body mw-table-body">
                <!-- Add Modal -->
                <div class="modal fade rhu-modal" id="addTestModal" tabindex="-1" aria-labelledby="addTestLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold text-black" id="addTestLabel">Add Test</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                
                            <!-- Add Modal Form -->
                            <form id="addTestForm" method="POST" action="/add-lab_tests">
                                @csrf
                                <div class="modal-body">
                                    <h6 class="mb-3">Test Details</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control w-100" name="name" id="name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">Price</label>
                                            <input type="text" class="form-control w-100" name="price" id="price" required>
                                        </div>
                                    </div>
                
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select w-100" name="status" id="status" required>
                                                <option selected value="Available">Available</option>
                                                <option value="Unavailable">Unavailable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary px-4">Save</button>
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                

                <!-- Edit Modal -->
                <div class="modal fade rhu-modal" id="editTestAccount" tabindex="-1" aria-labelledby="editTestLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold text-black" id="editTestLabel">Edit Test</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                
                            <!-- Edit Modal Form -->
                            <form id="editTestForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <h6 class="mb-3">Test Information</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="edit_name" class="form-label">Name</label>
                                            <input type="text" class="form-control w-100" name="name" id="edit_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="edit_price" class="form-label">Price</label>
                                            <input type="text" class="form-control w-100" name="price" id="edit_price" required>
                                        </div>
                                    </div>
                
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="edit_status" class="form-label">Status</label>
                                            <select class="form-select w-100" name="status" id="edit_status" required>
                                                <option value="Available">Available</option>
                                                <option value="Unavailable">Unavailable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
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
                                <th>Test Name</th>
                                <th>Price</th>
                                <th>Status</th>

                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($lab_tests->isNotEmpty())
                                    @foreach ($lab_tests as $lab_test)
                                        <tr class="mw-column-name">
                                            <td>{{ $lab_test->name }}</td>
                                            <td>{{ $lab_test->price}}</td>
                                            <td>{{ $lab_test->status }}</td>

                                            <td>
                                                <div class="form-button-action">
                                                    <!-- Edit Button -->
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editTestAccount"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        onclick="editTestAccount({{ $lab_test->id }})">
                                                        <i class="fa fa-pen mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-link btn-danger" onclick="confirmDeleteTest({{ $lab_test->id }})">
                                                        <i class="fa fa-times"><span class="mw-btn-edit-text">Delete</span></i>
                                                    </button>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="mw-column-name">
                                        <td colspan="3" class="text-center">No Vaccine available</td>
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
        function editTestAccount(pId) {
        const selectedClient = @json($lab_tests).find(lab_test => lab_test.id == pId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('edit_name').value = selectedClient.name || '';
            document.getElementById('edit_price').value = selectedClient.price || '';
            document.getElementById('edit_status').value = selectedClient.status || '';


            document.getElementById('editTestForm').action = `/update-lab_tests/${pId}`;
            } else {
                console.error('Selected test not found.');
            }
        }


        // delete method
        function confirmDeleteTest(clientId) {
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
                    fetch(`/delete-lab_tests/${clientId}`, {
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
