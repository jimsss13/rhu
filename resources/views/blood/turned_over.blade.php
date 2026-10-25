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
    @error('blood_type')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    @error('volume')
    <span class="text-danger">{{ $message }}</span>
@enderror

        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Manage Turned Over Blood</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 520px;" placeholder="Search..." />
                    <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addVaccineModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body mw-table-body">
                <div class="modal fade rhu-modal" id="addVaccineModal" tabindex="-1" aria-labelledby="addVaccineLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold text-black" id="addVaccineLabel">Add Turned Over</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                
                            <!-- Add Modal Form -->
                            <form id="addVaccineForm" method="POST" action="/add-turned_overs">
                                @csrf
                                <div class="modal-body">
                                    <h6 class="mb-3">Turned Over Details</h6>
                
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control w-100" name="name" id="name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="date" class="form-label">Date</label>
                                            <input type="date" class="form-control w-100" name="date" id="date" required>
                                        </div>
                                    </div>
                
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="blood_type" class="form-label">Blood Type</label>
                                            <select class="form-select w-100" name="blood_type" id="blood_type" required>
                                                <option selected value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="volume" class="form-label">Volume (ml)</label>
                                            <input type="text" class="form-control w-100" name="volume" id="volume" required>
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
                

                {{-- <!-- Edit Modal -->
                <div class="modal fade rhu-modal" id="editVaccineAccount" tabindex="-1" aria-labelledby="editVaccineLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" style="margin-left: 35rem !important;">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="editVaccineLabel">Edit Turned Over Information</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- edit Modal Form -->
                            <form id="editVaccineForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <!-- Personal Information -->
                                    <h6 class="mb-3">Turned Over Blood Information</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" id="edit_name">
                                            </div>
                                        </div>
                                         <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="expiration_date">Expiration Date</label>
                                                <input type="date" class="form-control" name="expiration_date" id="edit_expiration_date">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="location_code">Location Area Code/ Rack Number</label>
                                                <input type="text" class="form-control" name="location_code" id="edit_location_code" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quantity">Quantity</label>
                                                <input type="text" class="form-control" name="quantity" id="edit_quantity" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}

                <!-- Manage Window Table -->
                <div class="table-responsive mw-table-content">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Blood Bank Name</th>
                                <th>Blood Type</th>
                                <th>Date</th>
                                <th>Volume</th>
                                <th style="width: 10%">Action</th>
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
                                            <td>
                                                <div class="form-button-action">
                                                    {{-- <!-- Edit Button -->
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editVaccineAccount"
                                                        class="btn btn-link btn-primary btn-lg"
                                                        onclick="editVaccineAccount({{ $vaccine->id }})">
                                                        <i class="fa fa-pen mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                                    </button> --}}

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-link btn-danger" onclick="confirmDeleteVaccine({{ $turned_over->id }})">
                                                        <i class="fa fa-times"><span class="mw-btn-edit-text">Delete</span></i>
                                                    </button>

                                                </div>
                                            </td>
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
        // function editVaccineAccount(pId) {
        // const selectedClient = @json($turned_overs).find(vaccine => vaccine.id == pId);

        // if (selectedClient) {
        //     // Fill the form fields with the selected client's data
        //     document.getElementById('edit_name').value = selectedClient.name || '';
        //     document.getElementById('edit_expiration_date').value = selectedClient.expiration_date || '';
        //     document.getElementById('edit_location_code').value = selectedClient.location_code || '';
        //     document.getElementById('edit_quantity').value = selectedClient.quantity || '';


        //     document.getElementById('editVaccineForm').action = `/update-turned_overs/${pId}`;
        //     } else {
        //         console.error('Selected client not found.');
        //     }
        // }


        // delete method
        function confirmDeleteVaccine(clientId) {
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
                    fetch(`/delete-turned_overs/${clientId}`, {
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
