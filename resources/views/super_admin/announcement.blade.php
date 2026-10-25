<x-layout>
        <div class="manage-window-card">
            {{-- Manage window --}}
            <div class="card mw-table">
                <div class="mw-header">
                    <div class="d-flex align-items-center">
                        <h4 class="mw-title">Manage Announcement</h4>
                        <input type="text" id="search" class="form-control" style="margin-left: 550px;" placeholder="Search..." />
                        <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body mw-table-body">

                    <!-- Add Modal -->
                    <div class="modal fade rhu-modal" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title font-semibold text-black" id="addAnnouncementLabel">Add New Announcement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                    
                                <!-- Add Modal Form -->
                                <form id="addAnnouncementForm" method="POST" action="/add-announcement" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <h6 class="mb-3">Announcement Details</h6>
                    
                                        <!-- Title and Date -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="title" class="form-label">Title</label>
                                                <input type="text" class="form-control w-100" name="title" id="title" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" class="form-control w-100" name="date" id="date" required>
                                            </div>
                                        </div>
                    
                                        <!-- Description -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control w-100" name="description" id="description" rows="2" required></textarea>
                                            </div>
                                        </div>
                    
                                        <!-- Context -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="fullcontext" class="form-label">Context</label>
                                                <textarea class="form-control w-100" name="fullcontext" id="fullcontext" rows="3" required></textarea>
                                            </div>
                                        </div>
                    
                                        <!-- Image Upload -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="image" class="form-label">Upload Image</label>
                                                <input type="file" class="form-control w-100" name="image" id="image" accept="image/*" required>
                                            </div>
                                        </div>
                                    </div>
                    
                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary px-4">Save Announcement</button>
                                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Edit Modal -->
                    <div class="modal fade rhu-modal" id="editAnnouncementAccount" tabindex="-1" aria-labelledby="editAnnouncementLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title font-semibold text-black" id="editAnnouncementLabel">Edit Announcement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                    
                                <!-- Edit Modal Form -->
                                <form id="editAnnouncementForm" method="POST" action="" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <h6 class="mb-3">Edit Announcement Details</h6>
                    
                                        <!-- Title and Date -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="editTitle" class="form-label">Title</label>
                                                <input type="text" class="form-control w-100" name="editTitle" id="editTitle" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="editDate" class="form-label">Date</label>
                                                <input type="date" class="form-control w-100" name="editDate" id="editDate" required>
                                            </div>
                                        </div>
                    
                                        <!-- Description -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="editDescription" class="form-label">Description</label>
                                                <textarea class="form-control w-100" name="editDescription" id="editDescription" rows="2" required></textarea>
                                            </div>
                                        </div>
                    
                                        <!-- Context -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="editFullcontext" class="form-label">Context</label>
                                                <textarea class="form-control w-100" name="editFullcontext" id="editFullcontext" rows="3" required></textarea>
                                            </div>
                                        </div>
                    
                                        <!-- Status -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="editIsShow" class="form-label">Status</label>
                                                <select class="form-select w-100" name="editIsShow" id="editIsShow" required>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>
                    
                                            <!-- Existing Image Preview -->
                                            <div class="col-md-6">
                                                <label for="editImage" class="form-label">Change Image</label>
                                                <input type="file" class="form-control w-100" name="editImage" id="editImage" accept="image/*">
                                                <img id="uploadIdImageAnnouncement" class="mt-3 d-block" style="width: 100px; display: none;" alt="Preview">
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
