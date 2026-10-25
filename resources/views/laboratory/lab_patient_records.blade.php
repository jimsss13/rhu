<x-layout>

    <div class="manage-window-card">

        {{-- for manage window --}}
        <div class="card mw-table">
            <div class="mw-header">
                <div class="d-flex align-items-center">
                    <h4 class="mw-title">Patient Records</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 630px;" placeholder="Search..."/>

                    <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addLabPatientModal">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body mw-table-body">
                <!-- Add Modal -->
                <div class="modal fade rhu-modal" id="addLabPatientModal" tabindex="-1" aria-labelledby="addLabPatientLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold text-black" id="addLabPatientLabel">Appointment Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Add Modal Form -->
                            <form id="addLabPatientForm" method="POST" action="/add-lab_patient_records" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <h6 class="mb-3">Test Details</h6>

                                    <!-- Name and Test Requested -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="appointment_id" class="form-label">Name</label>
                                            <select class="form-select w-100" name="appointment_id" id="appointment_id" required>
                                                <option value="" selected disabled>Select a Name</option>
                                                @foreach($appointments as $appointment)
                                                    <option value="{{ $appointment->id }}" data-sub-type="{{ $appointment->sub_type }}" data-reason="{{ $appointment->reason }}" data-dose="{{ $appointment->dose_number }}">{{ $appointment->name }} ? {{ $appointment->sub_type }} ({{ $appointment->date->format('Y-m-d g:i A') }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="sub_type" class="form-label">Test Requested</label>
                                            <input type="text" class="form-control w-100" name="sub_type" id="sub_type" readonly>
                                        </div>
                                    </div>

                                    <!-- Test Result Upload -->
                                    <div class="row mb-3 d-flex justify-content-center">
                                        <div class="col-md-6">
                                            <label for="result" class="form-label">Test Result <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control w-100" name="result" id="result" required>
                                        </div>

                                        <!-- Hidden Status Field -->
                                        <div class="col-md-6 d-none">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select w-100" name="status" id="status" required>
                                                <option selected value="Completed">Completed</option>
                                                <option value="Rejected">Rejected</option>
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



                <!-- Manage Window Table -->
                <div class="table-responsive mw-table-content">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Name</th>
                                <th>Test Requested</th>
                                <th>Test Result</th>

                                <th>Status</th>

                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @if ($patients->isNotEmpty())
                                    @foreach ($patients as $patient)
                                        <tr class="mw-column-name">
                                            <td>{{ $patient->name }}</td>
                                            <td>{{ $patient->sub_type}}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $patient->result) }}" target="_blank" class="btn btn-info btn-sm d-inline">Preview</a>
                                                <a href="{{ asset('storage/' . $patient->result) }}" class="btn btn-primary btn-sm d-inline" download>Download</a>
                                            </td>



                                            <td>{{ $patient->status }}</td>

                                            <td>
                                                <div class="form-button-action">
                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-link btn-danger" onclick="deleteLabPatient({{ $patient->id }})">
                                                        <i class="fa fa-times"><span class="mw-btn-edit-text">Delete</span></i>
                                                    </button>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="mw-column-name">
                                        <td colspan="3" class="text-center">No Patient available</td>
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


    </script>
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
        document.getElementById('appointment_id').addEventListener('change', function () {
            const appointment = this.selectedOptions[0];
            document.getElementById('sub_type').value = appointment?.dataset.subType || '';
        });
    </script>


    {{-- delete --}}
    <script>
        // delete method
        function deleteLabPatient(clientId) {
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
                    fetch(`/delete-patient_records/${clientId}`, {
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

</x-layout>
