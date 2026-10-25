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
                    <h4 class="mw-title">Blood Donation Appointments</h4>
                    <input type="text" id="search" class="form-control" style="margin-left: 520px;" placeholder="Search..." />
                </div>
            </div>
            <div class="mw-table-body">
                <!-- Edit Modal -->
                <div class="modal fade rhu-modal" id="editLabAppointmentModal" tabindex="-1" aria-labelledby="editLabAppointmentLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title font-semibold text-black" id="editLabAppointmentLabel">Edit Appointment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Edit Modal Form -->
                            <form id="editLabAppointmentForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <h6 class="mb-3">Appointment Details</h6>

                                    <input type="hidden" class="form-control" name="client_id" id="editClientId" readonly>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="editName" class="form-label">Patient Name</label>
                                            <input type="text" class="form-control w-100" name="name" id="editName" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="editBloodType" class="form-label">Blood Type</label>
                                            <select class="form-select w-100" name="sub_type" id="editBloodType" required>
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
                                        <div class="col-md-4">
                                            <label for="editDate" class="form-label">Appointment Date</label>
                                            <input type="text" class="form-control w-100" name="date" id="editDate" readonly>
                                        </div>
                                    </div>

                                    <div id="medicalHistorySection">
                                        <h6 class="mb-3">Medical History</h6>
                                        <!-- Loop for Medical History Questions -->
                                        @php
                                            $questions = [
                                                "1. Feeling well and healthy today?" => "well_health",
                                                "2. Currently taking an antibiotic?" => "antibiotics",
                                                "3. Currently taking any other medication for an infection?" => "infection_medication",
                                                "4. Have you ever taken any medications on the Medication Deferral List?" => "medication_deferral",
                                                "5. In the past 48 hours, have you taken aspirin or anything that has aspirin in it?" => "aspirin",
                                                "6. In the past 4 weeks, have you had any vaccinations or any other kind of shot?" => "vaccinations",
                                                "7. In the past 6 weeks, have you been pregnant or are you pregnant now?" => "pregnant",
                                                "8. In the past 8 weeks, have you donated blood, platelets, or plasma?" => "donated_recently",
                                                "9. In the past 16 weeks, have you donated a double unit of red cells using an apheresis device?" => "apheresis",
                                                "10. Had a blood transfusion?" => "blood_transfusion",
                                                "11. Had a transplant such as organ, tissue, or bone marrow?" => "transplant",
                                                "12. Had a graft such as bone or skin?" => "graft",
                                                "13. Come into contact with someone else's blood?" => "contact_blood",
                                                "14. Had an accidental needlestick injury?" => "needlestick_injury",
                                                "15. Had sexual contact with anyone who has HIV/AIDS or had a positive test for the HIV/AIDS virus?" => "sexual_contact_hiv",
                                                "16. Had sexual contact with a prostitute or anyone else who takes money or drugs or other payment for sex?" => "prostitute_contact",
                                                "17. Had sexual contact with anyone who has ever used needles to take drugs or steroids?" => "drug_use_contact",
                                                "18. Had sexual contact with anyone who has hemophilia or has used clotting factor concentrates?" => "hemophilia_contact",
                                                "19. Female donors: had sexual contact with a male who has ever had sexual contact with another male? (Males: check No)" => "male_contact_with_male",
                                                "20. Come into contact with saliva (including kissing) from a person who has hepatitis?" => "saliva_contact_hepatitis",
                                                "21. Come into contact with blood from a person who has hepatitis?" => "contact_blood_hepatitis",
                                                "22. Had sexual contact with a person who has hepatitis?" => "sexual_contact_hepatitis",
                                                "23. Had a tattoo applied?" => "tattoo",
                                                "24. Had ear or body piercing?" => "piercing",
                                                "25. Had acupuncture?" => "acupuncture",
                                                "26. Had or been treated for syphilis or gonorrhea?" => "syphilis_gonorrhea",
                                                "27. Been in juvenile detention, lockup, jail, or prison for more than 72 hours?" => "juvenile_detention",
                                                "28. Ever had a positive test for the HIV/AIDS virus?" => "hiv_aids_positive",
                                                "29. Ever used needles to take drugs, steroids, or anything not prescribed by a doctor?" => "used_needles",
                                                "30. Ever received clotting factor concentrates?" => "clotting_factor",
                                                "31. Ever had hepatitis?" => "hepatitis",
                                                "32. Ever had malaria?" => "malaria",
                                                "33. Ever had Chagas' disease?" => "chagas",
                                                "34. Ever had babesiosis?" => "babesiosis",
                                            ];
                                        @endphp
                                        @foreach($questions as $text => $name)
                                            <div class="form-check mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>{{ $text }}</div>
                                                <div style="display: flex;">
                                                    <div style="margin-right: 3rem;">
                                                        <input type="radio" class="form-check-input" name="{{ $name }}" value="yes" disabled> Yes
                                                    </div>
                                                    <div>
                                                        <input type="radio" class="form-check-input" name="{{ $name }}" value="no" disabled> No
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



                <div class="table-responsive mw-table-content">
                    <table id="add-row" class="display table">
                        <thead>
                            <tr class="mw-column-name">
                                <th>Patient Name</th>
                                <th>Blood Type</th>
                                <th>Date</th>
                                {{-- <th>Contact Number</th> --}}
                                <th>Status</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody class="logs-column-body">
                            @php
                            // Define an array to map laboratory tests to their full names
                            $bloodTypes = [
                                'A_positive' => 'A+',
                                'A_negative' => 'A-',
                                'B_positive' => 'B+',
                                'B_negative' => 'B-',
                                'AB_positive' => 'AB+',
                                'AB_negative' => 'AB-',
                                'O_positive' => 'O+',
                                'O_negative' => 'O-',
                                ];
                            @endphp
                            @if ($blood_appointments->isNotEmpty())
                                @foreach ($blood_appointments as $blood_appointment)
                                    <tr class="mw-column-name">
                                        <td>{{ $blood_appointment->name }}</td>
                                        <td>{{ $bloodTypes[$blood_appointment->sub_type] ?? $blood_appointment->sub_type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($blood_appointment->date)->format('Y-m-d g:i A') }}</td>
                                        {{-- <td>{{ $blood_appointment->contactNo }}</td> --}}
                                        <td>{{ $blood_appointment->status }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <!-- Edit Button -->
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editLabAppointmentModal"
                                                    class="btn btn-link btn-primary btn-lg"
                                                    onclick="editLabAppointmentAccount({{ $blood_appointment->id }}, {{ $blood_appointment->client_id }})">
                                                    <i class="fa fa-eye mw-btn-edit"><span class="mw-btn-edit-text">View</span></i>
                                                </button>
                                                <!-- Approve Button -->
                                                <button type="button" class="btn btn-link btn-success btn-lg" onclick="approveLabAppointmentAccount({{ $blood_appointment->id }}, {{ $blood_appointment->client_id }})">
                                                    <i class="fa fa-check"><span class="mw-btn-edit-text">Approve</span></i>
                                                </button>
                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-link btn-danger" onclick="confirmLabAppointmentClient({{ $blood_appointment->id }})">
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

{{-- approved --}}
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
    function editLabAppointmentAccount(pId, clientId) {
        const selectedClient = @json($blood_appointments).find(client => client.id == pId);
        const selectedMedicalHistory = @json($medical_history).find(client => client.client_id == clientId);

        if (selectedClient) {
            // Fill the form fields with the selected client's data
            document.getElementById('editName').value = selectedClient.name || '';
            document.getElementById('editBloodType').value = selectedClient.sub_type || '';
            document.getElementById('editDate').value = selectedClient.date || '';
            // document.getElementById('editContactNo').value = selectedClient.contactNo || '';
            document.getElementById('editClientId').value = selectedClient.client_id || '';

            document.getElementById('editLabAppointmentForm').action = `/update-blood_appointments/${pId}`;
        }

        if (selectedMedicalHistory) {
        // Set the radio buttons based on selectedMedicalHistory values
        const medicalQuestions = [
            { name: 'well_health', value: selectedMedicalHistory.well_health },
            { name: 'antibiotics', value: selectedMedicalHistory.antibiotics },
            { name: 'infection_medication', value: selectedMedicalHistory.infection_medication },
            { name: 'medication_deferral', value: selectedMedicalHistory.medication_deferral },
            { name: 'aspirin', value: selectedMedicalHistory.aspirin },
            { name: 'vaccinations', value: selectedMedicalHistory.vaccinations },
            { name: 'pregnant', value: selectedMedicalHistory.pregnant },
            { name: 'donated_recently', value: selectedMedicalHistory.donated_recently },
            { name: 'apheresis', value: selectedMedicalHistory.apheresis },
            { name: 'blood_transfusion', value: selectedMedicalHistory.blood_transfusion },
            { name: 'transplant', value: selectedMedicalHistory.transplant },
            { name: 'graft', value: selectedMedicalHistory.graft },
            { name: 'contact_blood', value: selectedMedicalHistory.contact_blood },
            { name: 'needlestick_injury', value: selectedMedicalHistory.needlestick_injury },
            { name: 'sexual_contact_hiv', value: selectedMedicalHistory.sexual_contact_hiv },
            { name: 'prostitute_contact', value: selectedMedicalHistory.prostitute_contact },
            { name: 'drug_use_contact', value: selectedMedicalHistory.drug_use_contact },
            { name: 'hemophilia_contact', value: selectedMedicalHistory.hemophilia_contact },
            { name: 'male_contact_with_male', value: selectedMedicalHistory.male_contact_with_male },
            { name: 'saliva_contact_hepatitis', value: selectedMedicalHistory.saliva_contact_hepatitis },
            { name: 'contact_blood_hepatitis', value: selectedMedicalHistory.contact_blood_hepatitis },
            { name: 'sexual_contact_hepatitis', value: selectedMedicalHistory.sexual_contact_hepatitis },
            { name: 'tattoo', value: selectedMedicalHistory.tattoo },
            { name: 'piercing', value: selectedMedicalHistory.piercing },
            { name: 'acupuncture', value: selectedMedicalHistory.acupuncture },
            { name: 'syphilis_gonorrhea', value: selectedMedicalHistory.syphilis_gonorrhea },
            { name: 'juvenile_detention', value: selectedMedicalHistory.juvenile_detention },
            { name: 'hiv_aids_positive', value: selectedMedicalHistory.hiv_aids_positive },
            { name: 'used_needles', value: selectedMedicalHistory.used_needles },
            { name: 'clotting_factor', value: selectedMedicalHistory.clotting_factor },
            { name: 'hepatitis', value: selectedMedicalHistory.hepatitis },
            { name: 'malaria', value: selectedMedicalHistory.malaria },
            { name: 'chagas', value: selectedMedicalHistory.chagas },
            { name: 'babesiosis', value: selectedMedicalHistory.babesiosis }
        ];

        medicalQuestions.forEach(question => {
            const selectedRadio = document.querySelector(`input[name="${question.name}"][value="${question.value}"]`);
            if (selectedRadio) {
                selectedRadio.checked = true;
            }
        });
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
