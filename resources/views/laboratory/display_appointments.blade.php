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

        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Laboratory Appointments</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 560px;" placeholder="Search..." />
               </div>
            </div>
            <div class="mw-table-body">

                <!-- Edit Modal -->
                <div class="modal fade rhu-modal" id="editLabAppointmentModal" tabindex="-1" aria-labelledby="editLabAppointmentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" style="margin-left: 35rem !important;">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="editLabAppointmentLabel">Edit Appointment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- edit Modal Form -->
                            <form id="editLabAppointmentForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <!-- Personal Information -->
                                    <h6 class="mb-3">Appointment Details</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="editName">Patient Name</label>
                                                <input type="text" class="form-control" name="name" id="editName" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="editSubType">Test</label>
                                                <input type="text" class="form-control" name="sub_type" id="editSubType" readonly>
                                                {{-- <select class="form-control" name="sub_type" id="editSubType" required>
                                                    <option value="fecalysis">Fecalysis</option>
                                                    <option value="blood_typing">Blood Typing</option>
                                                    <option value="urinalysis">Urinalysis</option>
                                                    <option value="sputum">Sputum</option>
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="editDate">Appointment Date</label>
                                                <input type="text" class="form-control" name="date" id="editDate" readonly>
                                                {{-- <input type="date" class="form-control" name="date" id="editDate" readonly> --}}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="editContactNo">Contact No.</label>
                                                <input type="text" class="form-control" name="contactNo" id="editContactNo" readonly>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    {{-- <button type="submit" class="btn btn-primary">Save Changes</button> --}}
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
                                <th>Patient Name</th>
                                <th>Test</th>
                                <th>Date</th>
                                {{-- <th>Contact Number</th> --}}
                                <th>Status</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @php
                                // Define an array to map laboratory tests to their full names
                                $laboratoryTests = [
                                    'fecalysis' => 'Fecalysis',
                                    'blood_typing' => 'Blood Typing',
                                    'urinalysis' => 'Urinalysis',
                                    'sputum' => 'Sputum',
                                ];
                            @endphp
                            @if ($lab_appointments->isNotEmpty())
                                @foreach ($lab_appointments as $lab_appointment)
                                    <tr class="mw-column-name">
                                        <td>{{ $lab_appointment->name }}</td>
                                        <td>{{ $laboratoryTests[$lab_appointment->sub_type] ?? $lab_appointment->sub_type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lab_appointment->date)->format('Y-m-d g:i A') }}</td>
                                        {{-- <td>{{ $lab_appointment->contactNo }}</td> --}}
                                        <td>{{ $lab_appointment->status }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <!-- Approve Button -->
                                                <button type="button" class="btn btn-link btn-success btn-lg" onclick="approveLabAppointmentAccount({{ $lab_appointment->id }},{{ $lab_appointment->client_id }})">
                                                    <i class="fa fa-check"><span class="mw-btn-edit-text">Approve</span></i>
                                                </button>
                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-link btn-danger" onclick="confirmLabAppointmentClient({{ $lab_appointment->id }})">
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

{{-- approved --}}
{{-- <script>
    function approveLabAppointmentAccount(clientId) {
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
                fetch(`/approve-appointments/${clientId}`, {
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
                            'The client has been approved.',
                            'success'
                        );
                        // Optionally refresh the page or update the UI
                        location.reload();
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
</script> --}}


<script>
    function approveLabAppointmentAccount(clientId, client_id) {
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
                fetch(`/approve-appointments/${clientId}`, {
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
                            'The client has been approved.',
                            'success'
                        ).then(() => {
                            // Redirect to the /email route
                            fetch(`/email/${clientId}`, {
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















{{-- delete --}}
<script>
    function confirmLabAppointmentClient(clientId) {
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
                fetch(`/delete-appointments/${clientId}`, {
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

{{-- update or show --}}
<script>
    // Edit shared window
    function editLabAppointmentAccount(pId) {
        const selectedClient = @json($lab_appointments).find(client => client.id == pId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('editName').value = selectedClient.name || '';
            document.getElementById('editSubType').value = selectedClient.sub_type || '';
            document.getElementById('editDate').value = selectedClient.date || '';
            // document.getElementById('editContactNo').value = selectedClient.contactNo || '';

            document.getElementById('editLabAppointmentForm').action = `/update-lab_appointments/${pId}`;
        } else {
            console.error('Selected client not found.');
        }
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

</x-layout>
