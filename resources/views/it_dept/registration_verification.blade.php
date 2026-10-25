<x-layout>

    <div class="manage-window-card">

        @if (session('sweetalert'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('sweetalert') }}',
                });
            </script>
        @endif

        {{-- for shared window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Verify Account</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 680px;" placeholder="Search..." />
                    {{-- <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#manageSharedWindow">
                        <i class="fa fa-plus"></i>
                    </button> --}}
                </div>
            </div>
            <div class="mw-table-body">

 <!-- Edit Modal -->
<div class="modal fade rhu-modal" id="editClientModal" tabindex="-1" aria-labelledby="editClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title font-semibold text-black" id="editClientLabel">View Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Edit Modal Form -->
            <form id="editClientForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Personal Information -->
                    <h6 class="mb-3">Personal Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editFname" class="form-label">First Name</label>
                            <input type="text" class="form-control w-100" name="f_name" id="editFname" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="editMname" class="form-label">Middle Name</label>
                            <input type="text" class="form-control w-100" name="m_name" id="editMname" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="editLname" class="form-label">Last Name</label>
                            <input type="text" class="form-control w-100" name="l_name" id="editLname" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editSuffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control w-100" name="suffix" id="editSuffix" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="editGender" class="form-label">Gender</label>
                            <input type="text" class="form-control w-100" name="gender" id="editGender" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="editBday" class="form-label">Birthday</label>
                            <input type="date" class="form-control w-100" name="bday" id="editBday" readonly>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <h6 class="my-3">Contact Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editContactNo" class="form-label">Contact Number</label>
                            <input type="text" class="form-control w-100" name="contactNo" id="editContactNo" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control w-100" name="email" id="editEmail" readonly>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <h6 class="my-3">Address Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editStreet" class="form-label">Street</label>
                            <input type="text" class="form-control w-100" name="street" id="editStreet" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="editBrgy" class="form-label">Barangay</label>
                            <input type="text" class="form-control w-100" name="brgy" id="editBrgy" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editMunicipality" class="form-label">Municipality</label>
                            <input type="text" class="form-control w-100" name="municipality" id="editMunicipality" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="editProvince" class="form-label">Province</label>
                            <input type="text" class="form-control w-100" name="province" id="editProvince" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editZipCode" class="form-label">Zip Code</label>
                            <input type="text" class="form-control w-100" name="zip_code" id="editZipCode" readonly>
                        </div>
                    </div>

                    <!-- Upload ID -->
                    <h6 class="my-3">Identification</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editUploadId">Upload ID</label>
                                <!-- Display the smaller image preview -->
                                <img id="uploadIdImagePreview"
                                     style="display:none; width: 100px; cursor: pointer; margin-top: 10px; margin-left:90px;"
                                     alt="Upload ID Preview"
                                     onclick="enlargeImage(this)">
                            </div>
                        </div>

                        <!-- Modal for larger image preview -->
                        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true" >
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="width: 1000px !important; height:550px !important">
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
                            <label for="editStatus" class="form-label">Status</label>
                            <input type="text" class="form-control w-100" name="status" id="editStatus" readonly>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


                <!-- Manage Shared Window Table -->
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
                        <tbody class="logs-column-body">
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
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editClientModal"
                                                    class="btn btn-link btn-primary btn-lg"
                                                    onclick="editClientAccount({{ $client->id }})">
                                                    <i class="fa fa-eye mw-btn-edit"><span class="mw-btn-edit-text">View</span></i>
                                                </button>
                                                <!-- Approve Button -->
                                                <button type="button" class="btn btn-link btn-success btn-lg" onclick="approveClientAccount({{ $client->id }})">
                                                    <i class="fa fa-check"><span class="mw-btn-edit-text">Approve</span></i>
                                                </button>
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


{{-- sweetalert for approved --}}
<script>
    function approveClientAccount(clientId) {
    // SweetAlert confirmation
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to approve this client.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Send an AJAX request to update the status
            fetch(`/approve-client/${clientId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: 'Approved' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                            'Approved!',
                            'Patient account approved.',
                            'success'
                        ).then(() => {
                            // Redirect to the /email route
                            fetch(`/approve-client-email/${clientId}`, {
                                method: 'POST',
                                redirect: 'manual',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                            }).finally(() => window.location.reload());
                        });
                } else {
                    Swal.fire(
                        'Error!',
                        'Something went wrong while approving the client.',
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

{{-- sweetalert for delete --}}

<script>
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
        // Delete shared window
        function deleteClientAccount(pId) {
            document.getElementById('deleteClientAccount').action = `/client_account/${pId}`;
        }

        // Edit shared window
        function editClientAccount(pId) {
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

    </script>




{{-- search --}}
<script>
    document.getElementById('search').addEventListener('keyup', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('.logs-column-body tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let match = false;

        cells.forEach(cell => {
            const text = cell.textContent.toLowerCase();
            if (text.includes(query)) {
                match = true;
                cell.innerHTML = highlightText(cell.textContent, query);  // Highlight the matched text
            } else {
                cell.innerHTML = cell.textContent;  // Remove any previous highlights
            }
        });

        // Show/hide the row based on whether there's a match
        if (match) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

function highlightText(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
}

</script>
</x-layout>
