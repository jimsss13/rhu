<x-layout>

<div class="manage-window-card">

    {{-- for manage window --}}
    <div class="card mw-table">
        <div class="mw-header">
            <div class="d-flex align-items-center">
                <h4 class="mw-title">Manage Admin Account</h4>
                <input type="text" id="search" class="form-control" style="margin-left: 550px;" placeholder="Search..." />

                <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body mw-table-body">


<!-- Add Modal -->
<div class="modal fade rhu-modal" id="addClientModal" tabindex="-1" aria-labelledby="addClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="mw-title font-semibold text-black" id="addClientLabel">Add Admin Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Add Modal Form -->
            <form id="addClientForm" method="POST" action="/add-staff">
                @csrf
                <div class="modal-body">
                    <!-- Personal Information -->
                    <h6 class="mb-3">Personal Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="addFname">First Name</label>
                            <input type="text" class="form-control capitalize-input w-100" name="f_name" id="addFname" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="addMname">Middle Name</label>
                            <input type="text" class="form-control capitalize-input w-100" name="m_name" id="addMname" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="addLname">Last Name</label>
                            <input type="text" class="form-control capitalize-input w-100" name="l_name" id="addLname" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="addUsername">Username</label>
                            <input type="text" class="form-control w-100" name="username" id="addUsername" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="addEmail">Email</label>
                            <input type="email" class="form-control w-100" name="email" id="addEmail" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" class="form-control w-100" name="password" id="password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control w-100" name="password_confirmation" id="password_confirmation" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="addDepartment">Department</label>
                            <select class="form-select w-100" name="department" id="addDepartment" required>
                                <option value="INVENTORY">INVENTORY</option>
                                <option value="IT DEPARTMENT">IT DEPARTMENT</option>
                                <option value="SUPER ADMIN">SUPER ADMIN</option>
                                <option value="CONSULTATION">CONSULTATION</option>
                                <option value="VACCINATION">VACCINATION</option>
                                <option value="LABORATORY">LABORATORY</option>
                                <option value="BLOOD">BLOOD DONATION</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="addStatus">Status</label>
                            <select class="form-select w-100" name="status" id="addStatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4">Add Admin</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade rhu-modal" id="editStaffAccount" tabindex="-1" aria-labelledby="editStaffLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg d-flex justify-content-center align-items-center">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="editStaffLabel">Edit Client Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Form -->
            <form id="editClientForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Personal Information -->
                    <h6 class="mb-3">Personal Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editFname" class="form-label">Firstname</label>
                            <input type="text" class="form-control w-100" name="f_name" id="editFname">
                        </div>
                        <div class="col-md-4">
                            <label for="editMname" class="form-label">Middlename</label>
                            <input type="text" class="form-control w-100" name="m_name" id="editMname">
                        </div>
                        <div class="col-md-4">
                            <label for="editLname" class="form-label">Lastname</label>
                            <input type="text" class="form-control w-100" name="l_name" id="editLname">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control w-100" name="username" id="editUsername">
                        </div>
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control w-100" name="email" id="editEmail">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editDepartment" class="form-label">Department</label>
                            <select class="form-control w-100" name="department" id="editDepartment" required>
                                <option value="INVENTORY">SUPER ADMIN</option>
                                <option value="IT DEPARTMENT">IT DEPARTMENT</option>
                                <option value="DENTAL CLINIC">DENTAL CLINIC</option>
                                <option value="CONSULTATION">CONSULTATION</option>
                                <option value="VACCINATION">VACCINATION</option>
                                <option value="LABORATORY">LABORATORY</option>
                                <option value="BLOOD DONATION">BLOOD DONATION</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-control w-100" name="status" id="editStatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
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
</div>

            <!-- Manage Window Table -->
            <div class="table-responsive mw-table-content">
                <table id="add-row" class="display table">
                    <thead>
                        <tr class="mw-column-name">
                            {{-- <th>#</th> --}}
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            {{-- <th>Address</th> --}}
                            <th>Department</th>
                            <th>Status</th>
                            <th style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody class="logs-column-body">
                        @if ($staffs->isNotEmpty())
                                @foreach ($staffs as $client)
                                    <tr class="mw-column-name">
                                        {{-- <td>{{ $index + 1 }}</td> --}}
                                        <td>{{ $client->username }}</td>
                                        <td>{{ $client->f_name }} {{ $client->l_name }} </td>
                                        <td>{{ $client->email }}</td>
                                        {{-- <td>{{ \Carbon\Carbon::parse($client->bday)->age }} years old</td> --}}
                                        {{-- <td>{{ ucwords(strtolower($client->street)) }}, {{ ucwords(strtolower($client->brgy)) }}, {{ ucwords(strtolower($client->municipality)) }}, {{ ucwords(strtolower($client->province)) }}</td> --}}
                                        <td>{{ $client->department }}</td>
                                        <td>{{ $client->status }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <!-- Edit Button -->
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editStaffAccount"
                                                    class="btn btn-link btn-primary btn-lg"
                                                    onclick="editStaffAccount({{ $client->id }})">
                                                    <i class="fa fa-pen mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                                </button>
                                                <!-- View Button -->
                                                {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#editClientModal"
                                                    class="btn btn-link btn-primary btn-lg"
                                                    onclick="editUSerAccount({{ $client->id }})">
                                                    <i class="fa fa-eye mw-btn-edit"><span class="mw-btn-edit-text">View</span></i>
                                                </button> --}}
                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-link btn-danger" onclick="confirmDeleteClient({{ $client->id }})">
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- email validation --}}
<script>
    $(document).ready(function () {
        // Validate email on blur
        $('#addEmail').on('blur', function () {
            const email = $(this).val();
            const domain = email.split('@')[1]; // Get the domain part of the email

            if (domain && domain.toLowerCase() === 'gmail.com') {
                $(this).css('border-color', ''); // Reset border color
                $('#emailError').remove(); // Remove any existing error message
            } else {
                $(this).css('border-color', 'red'); // Highlight the input with a red border
                if (!$('#emailError').length) {
                    // Append error message only if it doesn't exist
                    $(this)
                        .after('<small id="emailError" style="color: red;">Please enter a valid Gmail address.</small>');
                }
            }
        });

        // Prevent form submission if the email is not valid
        $('#addClientForm').on('submit', function (e) {
            const email = $('#addEmail').val();
            const domain = email.split('@')[1]; // Get the domain part of the email

            if (!domain || domain.toLowerCase() !== 'gmail.com') {
                e.preventDefault(); // Prevent form submission
                alert('Only Gmail addresses are allowed.'); // Alert the user
                $('#addEmail').focus(); // Set focus back to the email input
            }
        });
    });
</script>

{{-- sweetalert for delete --}}
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


</script>
<script>
    @if ($errors->any())
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessages,
        });
    @endif
</script>

<script>
        function editStaffAccount(pId) {
        const selectedClient = @json($staffs).find(client => client.id == pId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('editFname').value = selectedClient.f_name || '';
            document.getElementById('editMname').value = selectedClient.m_name || '';
            document.getElementById('editLname').value = selectedClient.l_name || '';
            document.getElementById('editDepartment').value = selectedClient.department || '';
            document.getElementById('editUsername').value = selectedClient.username || '';
            document.getElementById('editEmail').value = selectedClient.email || '';
            document.getElementById('editStatus').value = selectedClient.status || '';


            document.getElementById('editClientForm').action = `/client_account/${pId}`;
            } else {
                console.error('Selected client not found.');
            }
        }



        function confirmDeleteClient(clientId) {
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
                fetch(`/delete-client/${clientId}`, {
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
