<x-layout>
        <div class="manage-window-card">
            {{-- Manage window --}}
            <div class="card mw-table">
                <div class="mw-header">
                    <div class="d-flex align-items-center">
                        <h4 class="mw-title">Manage Announcement</h4>
                        <input type="text" id="search" class="form-control" placeholder="Search Logs..." />
                        <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body mw-table-body">

                    <!-- Add Modal -->
                    <div class="modal fade rhu-modal" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" style="margin-left: 35rem !important;">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addAnnouncementLabel">Add New Announcement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Add Modal Form -->
                                <form id="addAnnouncementForm" method="POST" action="/add-announcement" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <!-- Announcement Information -->
                                        <h6 class="mb-3">Announcement</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input type="text" class="form-control" name="title" id="title" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <input type="date" class="form-control" name="date" id="date" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <input type="text" class="form-control" name="description" id="description" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="fullcontext">Context</label>
                                                    <input type="text" class="form-control" name="fullcontext" id="fullcontext" required>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="isShow">Status</label>
                                                 <select class="form-control" name="isShow" id="isShow" required>

                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>

                                                </select>
                                            </div>
                                        </div> --}}

                                        <div>
                                            <label class="block text-black" for="image">Upload Image *</label>
                                            <input class="border border-border rounded-lg p-2 w-full" type="file" name="image" accept="image/*" required />
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Announcement</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div class="modal fade rhu-modal" id="editAnnouncementAccount" tabindex="-1" aria-labelledby="editStaffLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" style="margin-left: 35rem !important;">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editStaffLabel">Edit Announcement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Edit Modal Form -->
                                <form id="editAnnouncementForm" method="POST" action="" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="uploadIdImageAnnouncement">Upload ID</label>
                                                    <!-- Display the smaller image preview -->
                                                    <img id="uploadIdImageAnnouncement"
                                                        style="display:none; width: 100px; cursor: pointer; margin-top: 10px;"
                                                        alt="Upload Image"
                                                       >
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="block text-black" for="editImage">Upload Image *</label>
                                                <input class="border border-border rounded-lg p-2 w-full" type="file" name="editImage" id="editImage" accept="image/*" />
                                            </div>
                                        </div>
                                        <!-- Announcement Information -->
                                        <h6 class="mb-3">Announcement</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="editTitle">Title</label>
                                                    <input type="text" class="form-control" name="editTitle" id="editTitle" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="editDate">Date</label>
                                                    <input type="date" class="form-control" name="editDate" id="editDate" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="editDescription">Description</label>
                                                    <input type="text" class="form-control" name="editDescription" id="editDescription" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="editFullcontext">Context</label>
                                                    <input type="text" class="form-control" name="editFullcontext" id="editFullcontext" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="editIsShow">Status</label>
                                                 <select class="form-control" name="editIsShow" id="editIsShow" required>

                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>

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
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Full Context</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="logs-column-body">
                                @if ($announcements->isNotEmpty())
                                        @foreach ($announcements as $announcement)
                                            <tr class="mw-column-name">
                                                {{-- <td>{{ $index + 1 }}</td> --}}
                                                <td>{{ $announcement->title }}</td>
                                                <td>{{ $announcement->description }}</td>
                                                <td>{{ $announcement->fullcontext }}</td>
                                                <td>{{ \Carbon\Carbon::parse($announcement->date)->format('Y-m-d') }}</td>
                                                <td>{{ $announcement->isShow }}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <!-- Edit Button -->
                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#editAnnouncementAccount"
                                                            class="btn btn-link btn-primary btn-lg"
                                                            onclick="editAnnouncementAccount({{ $announcement->id }})">
                                                            <i class="fa fa-pen mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-link btn-danger" onclick="confirmDeleteAnnouncement({{ $announcement->id }})">
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

        {{-- Sweetalert for delete --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{!! session('error') !!}',
                    confirmButtonText: 'OK'
                });
            @endif
        </script>


        <script>

            function editAnnouncementAccount(pId) {
                const selectedAnnouncement = @json($announcements).find(announcement => announcement.id == pId);

            if (selectedAnnouncement) {
                // Populate form fields
                document.getElementById('editTitle').value = selectedAnnouncement.title || '';
                document.getElementById('editDescription').value = selectedAnnouncement.description || '';
                document.getElementById('editFullcontext').value = selectedAnnouncement.fullcontext || '';
                document.getElementById('editDate').value = selectedAnnouncement.date || '';
                document.getElementById('editIsShow').value = selectedAnnouncement.isShow || '';

                const imagePreview = document.getElementById('uploadIdImageAnnouncement');
                if (selectedAnnouncement.image) {
                    imagePreview.src = `/storage/${selectedAnnouncement.image}`;
                    imagePreview.style.display = 'block';
                } else {
                    imagePreview.style.display = 'none';
                }

                // Update form action URL
                document.getElementById('editAnnouncementForm').action = `/update-announcement/${pId}`;
            } else {
                console.error('Announcement not found.');
            }
            }

            function confirmDeleteAnnouncement(id) {
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
                        fetch(`/delete-announcement/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Deleted',
                                    text: 'The announcement was deleted successfully!',
                                    timer: 2000, // Optional: Auto-close after 2 seconds
                                }).then(() => {
                                    // Wait for 0.5 seconds before reloading the page
                                    setTimeout(() => {
                                        location.reload();
                                    }, 400); // 0.5-second delay
                                });
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

        {{-- Input validation --}}
        <script>
            // Format input
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
