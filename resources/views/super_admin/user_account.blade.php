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
                <h4 class="mw-title">Manage Client Account</h4>
                {{-- <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="fa fa-plus"></i>
                </button> --}}
            </div>
        </div>
        <div class="card-body mw-table-body">
            <!-- Add Modal -->
            {{-- <form method="POST" action="/addClient">
                @csrf
                <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold"> Client</span> <span class="fw-light">Account</span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body mw-add-window-body" style="margin-right:10px;">
                                <p class="small">Create a new window.</p>
                                <div class="row">
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="w_id">Window</label>
                                            <input style="border:0;" type="text" name="w_name" id="w_name" oninput="formatInput(this)">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="w_status">Status</label>
                                            <select name="w_status" id="w_status" class="form-control">
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="p_id">Personnel</label>
                                            <select id="p_id" class="form-control" name="p_id" required>
                                                <option value="" disabled selected>Select a personnel</option>
                                                @if ($personnels)
                                                    @foreach ($personnels as $personnel)
                                                        <option value="{{ $personnel->p_id }}">
                                                            {{ $personnel->firstname }} {{ $personnel->lastname }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <p>No window available for your department.</p>
                                                @endif
                                            </select>
                                            @error('p_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mw-services-card">

                                    @if ($services)
                                        @foreach ($services as $service)
                                            <div class="col-md-12 col-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="services[]"
                                                        value="{{ $service->service_id }}" id="service-{{ $service->service_id }}">
                                                    <label class="form-check-label text-wrap" for="service-{{ $service->service_id }}">
                                                        {{ $service->service_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No services available for your department.</p>
                                    @endif
                                </div>

                                <input type="text" style="display: none;" name="dept_id" id="dept_id" value="{{ session('current_department_id') }}">
                            </div>
                            <div class="modal-footer border-0">
                                <button type="submit" class="btn btn-primary">Add</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form> --}}

            <!-- Edit Modal -->
            <div class="modal fade rhu-modal" id="editUserAccount" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" style="margin-left: 35rem !important;">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserLabel">Edit Client Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- edit Modal Form -->
                        <form id="editClientForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <!-- Personal Information -->
                                <h6 class="mb-3">Personal Information</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="editFname">First Name</label>
                                            <input type="text" class="form-control" name="f_name" id="editFname">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="editMname">Middle Name</label>
                                            <input type="text" class="form-control" name="m_name" id="editMname" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="editLname">Last Name</label>
                                            <input type="text" class="form-control" name="l_name" id="editLname" >
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editSuffix">Suffix</label>
                                            <input type="text" class="form-control" name="suffix" id="editSuffix" >
                                        </div>
                                    </div>
                                    <!-- Gender -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="editGender">Gender</label>
                                            {{-- <input type="text" class="form-control" name="gender" id="editGender" readonly> --}}
                                            <select class="form-control" name="gender" id="editGender" >
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editBday">Birthday</label>
                                            <input type="date" class="form-control" name="bday" id="editBday" >
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <h6 class="my-3">Contact Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editContactNo">Contact Number</label>
                                            <input type="text" class="form-control" name="contactNo" id="editContactNo" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editEmail">Email</label>
                                            <input type="email" class="form-control" name="email" id="editEmail" >
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Information -->
                                <h6 class="my-3">Address Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editStreet">Street</label>
                                            <input type="text" class="form-control" name="street" id="editStreet" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editBrgy">Barangay</label>
                                            <input type="text" class="form-control" name="brgy" id="editBrgy" >
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editMunicipality">Municipality</label>
                                            <input type="text" class="form-control" name="municipality" id="editMunicipality" >
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editProvince">Province</label>
                                            <input type="text" class="form-control" name="province" id="editProvince" >
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editZipCode">Zip Code</label>
                                            <input type="text" class="form-control" name="zip_code" id="editZipCode" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editUploadId">Upload ID</label>
                                            <!-- Display the smaller image preview -->
                                            <img id="uploadIdImagePreview"
                                                 style="display:none; width: 100px; cursor: pointer; margin-top: 10px;"
                                                 alt="Upload ID Preview"
                                                 onclick="enlargeImage(this)">
                                        </div>
                                    </div>

                                    <!-- Modal for larger image preview -->
                                    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true" >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content" style="width: 1000px !important; height:400px !important">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imagePreviewModalLabel">Upload ID Preview</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                 <!-- Display the larger image in the modal -->
                                                 <img id="enlargedImage"  alt="Enlarged Image" style="width: 1000px !important; height:400px !important">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editStatus">Status</label>
                                            {{-- <input type="text" class="form-control" name="status" id="editStatus" readonly> --}}
                                            <select class="form-control" name="status" id="editStatus">

                                                <option value="Approved">Approved</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Rejected">Rejected</option>
                                            </select>
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
                            <th>Address</th>
                            <th>Contact No.</th>
                            <th>Status</th>
                            <th style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($clients->isNotEmpty())
                                @foreach ($clients as $client)
                                    <tr class="mw-column-name">
                                        {{-- <td>{{ $index + 1 }}</td> --}}
                                        <td>{{ $client->username }}</td>
                                        <td>{{ $client->f_name }} {{ $client->l_name }} </td>
                                        <td>{{ $client->email }}</td>
                                        {{-- <td>{{ \Carbon\Carbon::parse($client->bday)->age }} years old</td> --}}
                                        <td>{{ ucwords(strtolower($client->street)) }}, {{ ucwords(strtolower($client->brgy)) }}, {{ ucwords(strtolower($client->municipality)) }}, {{ ucwords(strtolower($client->province)) }}</td>
                                        <td>{{ $client->contactNo }}</td>
                                        <td>{{ $client->status }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <!-- Edit Button -->
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editUserAccount"
                                                    class="btn btn-link btn-primary btn-lg"
                                                    onclick="editUserAccount({{ $client->id }})">
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
{{-- sweetalert for delete --}}

<script>
    // Function to display the image in the modal
    function enlargeImage(imgElement) {
        // Get the full image URL
        const imagePath = imgElement.src;

        // Set the source of the modal's image to the clicked image's URL
        document.getElementById('enlargedImage').src = imagePath;

        // Show the modal
        const imageModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        imageModal.show();
    }

    // Edit shared window
    function editUserAccount(pId) {
    const selectedClient = @json($clients).find(client => client.id == pId);

    if (selectedClient) {
        // Fill the form fields with the selected client's data
        document.getElementById('editFname').value = selectedClient.f_name || '';
        document.getElementById('editMname').value = selectedClient.m_name || '';
        document.getElementById('editLname').value = selectedClient.l_name || '';
        document.getElementById('editSuffix').value = selectedClient.suffix || '';

        console.log(selectedClient.bday);

        document.getElementById('editBday').value = selectedClient.bday || '';

        document.getElementById('editGender').value = selectedClient.gender || '';
        document.getElementById('editContactNo').value = selectedClient.contactNo || '';
        document.getElementById('editEmail').value = selectedClient.email || '';
        document.getElementById('editStreet').value = selectedClient.street || '';
        document.getElementById('editBrgy').value = selectedClient.brgy || '';
        document.getElementById('editMunicipality').value = selectedClient.municipality || '';
        document.getElementById('editProvince').value = selectedClient.province || '';
        document.getElementById('editZipCode').value = selectedClient.zip_code || '';
        document.getElementById('editStatus').value = selectedClient.status || '';

        const imagePreview = document.getElementById('uploadIdImagePreview');

        if (selectedClient.upload_id) {

            imagePreview.src = `/storage/${selectedClient.upload_id}`;  // Adjusted to include 'public/storage' link
            imagePreview.style.display = 'block';  // Show the image preview
        } else {
            imagePreview.style.display = 'none';  // Hide if no upload_id is available
        }
        // Set the form action dynamically
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
