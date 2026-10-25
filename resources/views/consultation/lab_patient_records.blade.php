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
                    <h4 class="mw-title">Patient Records</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 630px;" placeholder="Search..." />
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
                            <div class="modal-header font-semibold text-black">
                                <h5 class="modal-title" id="addLabPatientLabel">Appointment Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Add Modal Form -->
                            <form id="addLabPatientForm" method="POST" action="/add-con_patient_records" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body mw-table-body">
                                    <h6 class="mb-3 font-semibold text-black">Consultation Details</h6>
                                    <div class="row">
                                        <!-- Name -->
                                        <div class="col-md-4 mb-3">
                                            <label for="appointment_id" class="form-label">Name</label>
                                            <select class="form-select w-100" name="appointment_id" id="appointment_id" required>
                                                <option value="" selected disabled>Select a Name</option>
                                                @foreach($appointments as $appointment)
                                                    <option value="{{ $appointment->id }}" data-sub-type="{{ $appointment->sub_type }}" data-reason="{{ $appointment->reason }}" data-dose="{{ $appointment->dose_number }}">{{ $appointment->name }} ? {{ $appointment->sub_type }} ({{ $appointment->date->format('Y-m-d g:i A') }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Mode of Consultation -->
                                        <div class="col-md-4 mb-3">
                                            <label for="sub_type" class="form-label">Mode of Consultation</label>
                                            <input type="text" class="form-control w-100" name="sub_type" id="sub_type" readonly>
                                        </div>

                                        <!-- Reason -->
                                        <div class="col-md-4 mb-3">
                                            <label for="reason" class="form-label">Reason</label>
                                            <input type="text" class="form-control w-100" name="reason" id="reason" readonly>
                                        </div>
                                    </div>

                                    <div class="row d-flex justify-content-center">
                                        <!-- Doctor -->
                                        <div class="col-md-6 mb-3">
                                            <label for="doctor" class="form-label">Doctor</label>
                                            <input type="text" class="form-control capitalize-input w-100" name="doctor" id="doctor" required>
                                        </div>
                                    </div>


                                    <div class="row d-flex justify-content-center">
                                        <!-- Doctor's Note -->
                                        <div class="col-md-6 mb-3">
                                            <label for="analysis" class="form-label">Doctor's Note *</label>
                                            <textarea type="text" class="form-control w-100 py-4" name="analysis" id="analysis" rows="10" required></textarea>
                                        </div>
                                    </div>
                                    <!-- Hidden Status Field -->
                                    <div class="row">
                                        <div class="col-md-6" style="display: none;">
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
                                    <button type="submit" class="btn btn-primary">Save</button>
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
                                <th>Name</th>
                                <th>Mode of Consultation</th>
                                <th>Reason</th>
                                <th>Doctor</th>
                               <th>Doctor's Analysis</th>
                               <th>Date</th>
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
                                            <td>{{ $patient->reason }}</td>
                                            <td>{{ $patient->doctor}}</td>
                                            {{-- <td>
                                                <span style="display: inline-block; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ asset('storage/' . $patient->result) }}
                                                </span>
                                                <a href="{{ asset('storage/' . $patient->result) }}" class="btn btn-primary btn-sm d-inline" download>Download Test Result</a>
                                            </td> --}}
                                            <td>{{ $patient->analysis }}</td>
                                            <td>{{ $patient->date }}</td>
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
        document.getElementById('appointment_id').addEventListener('change', function () {
            const appointment = this.selectedOptions[0];
            document.getElementById('sub_type').value = appointment?.dataset.subType || '';
            document.getElementById('reason').value = appointment?.dataset.reason || '';
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
