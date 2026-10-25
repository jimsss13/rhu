<x-layout>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="py-1" style="background-color: #DBEAFE; margin-top:-14px; margin-left:-14px; margin-right:-14px;">
                    <h4 class="mw-title mt-2 ml-4 mb-2 text-3xl font-semibold text-black">Account Information</h4>
                    </div>
                    <div class="row">
                        <!-- Profile Picture -->
                        <div class="col-md-4 text-center">
                            @if ($user->profile_picture)
                                <img class="img-thumbnail" style="margin-left: 100px; margin-top:50px;" width="190" 
                                src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('../images/img/user.png') }}" 
                                alt="Profile Picture">
                            @else
                                <p class="text-muted">No profile picture available</p>
                            @endif
                        </div>
    
                        <!-- Profile Information -->
                        <div class="col-md-8 pt-4">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <p class="form-control-plaintext">{{ $user->f_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <p class="form-control-plaintext">{{ $user->l_name }}</p>
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Middle Name</label>
                                    <p class="form-control-plaintext">{{ $user->m_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <p class="form-control-plaintext">{{ $user->email }}</p>
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <p class="form-control-plaintext">{{ $user->username }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <p class="form-control-plaintext">{{ $user->department }}</p>
                                </div>
                            </div>
    
                            <!-- Trigger Button -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    <!-- Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="mw-title font-semibold text-black" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Profile Form -->
                    <form id="profileForm" action="{{ route('update_edit_profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
    
                        <!-- Profile Picture -->
                        <div class="row mb-4 text-center">
                            <div class="col-md-12">
                                @if ($user->profile_picture)
                                    <img class="img-thumbnail mb-3" style="margin-left:240px;" width="150" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                                @endif
                                <input type="file" class="form-control mx-auto w-60" name="profile_picture" accept="image/*">
                                <small class="text-muted d-block -ml-2">Allowed formats: JPG, PNG, GIF (Max: 2MB)</small>
                                @error('profile_picture')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    
                        <!-- Name Fields -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control capitalize-input w-60" name="f_name" value="{{ old('f_name', $user->f_name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control capitalize-input w-60" name="m_name" value="{{ old('m_name', $user->m_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control capitalize-input w-60" name="l_name" value="{{ old('l_name', $user->l_name) }}" required>
                            </div>
                        </div>
    
                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control w-60" name="username" value="{{ old('username', $user->username) }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" id="email" class="form-control w-60" name="email" value="{{ old('email', $user->email) }}" readonly>
                                <small id="emailError" class="text-danger d-none">Please enter a valid Gmail address.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control w-60" name="username" value="{{ old('department', $user->department) }}" readonly>
                            </div>
                        </div>
    
                        <!-- Email and Password Fields -->
                        <div class="row mb-3 d-flex justify-content-center">
                            <div class="col-md-4">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control w-60" name="password" placeholder="Enter new password">
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control w-60" name="password_confirmation" placeholder="Confirm new password">
                            </div>
                        </div>
    
                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert for delete -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Email validation script -->
    <script>
        $(document).ready(function () {
            // Validate email on blur
            $('#email').on('blur', function () {
                const email = $(this).val();
                const domain = email.split('@')[1]; // Get the domain part of the email

                if (domain && domain.toLowerCase() === 'gmail.com') {
                    $(this).css('border-color', '');
                    $('#emailError').hide();
                } else {
                    $(this).css('border-color', 'red');
                    $('#emailError').show();
                }
            });

            // Prevent form submission if email is not valid
            $('#profileForm').on('submit', function (e) {
                const email = $('#email').val();
                const domain = email.split('@')[1];

                if (!domain || domain.toLowerCase() !== 'gmail.com') {
                    e.preventDefault();
                    alert('Only Gmail addresses are allowed.');
                    $('#email').focus();
                }
            });
        });
    </script>

    <!-- Capitalization script -->
    <script>
        function capitalizeAndValidate(event) {
            let inputValue = event.target.value.replace(/[^a-zA-Z\s]/g, '');
            inputValue = inputValue.replace(/\s+/g, ' ');  // Remove multiple spaces
            inputValue = inputValue.replace(/\b\w/g, char => char.toUpperCase());
            event.target.value = inputValue;
        }

        document.querySelectorAll('.capitalize-input').forEach(function(input) {
            input.addEventListener('input', capitalizeAndValidate);
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
    document.addEventListener('DOMContentLoaded', function () {
        const contactNoInput = document.getElementById('contactNo');
        const mobileError = document.getElementById('mobileError');

        contactNoInput.addEventListener('input', function () {
            // Remove non-numeric characters
            contactNoInput.value = contactNoInput.value.replace(/[^0-9]/g, '');

            // Check if it starts with '09' and has exactly 11 digits
            if (!/^09\d{9}$/.test(contactNoInput.value)) {
                contactNoInput.style.borderColor = 'red';
                mobileError.style.display = 'block';
            } else {
                contactNoInput.style.borderColor = '';
                mobileError.style.display = 'none';
            }
        });

        document.getElementById('profileForm').addEventListener('submit', function (e) {
            if (!/^09\d{9}$/.test(contactNoInput.value)) {
                e.preventDefault(); // Prevent form submission if invalid
                alert('Please enter a valid mobile number starting with 09 and having 11 digits.');
                contactNoInput.focus();
            }
        });
    });
</script>
<script>
    document.querySelector('input[name="profile_picture"]').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type) || file.size > 2 * 1024 * 1024) {
            alert('Invalid file. Please upload a valid image (JPG, PNG, GIF) under 2MB.');
            this.value = '';
        }
    }
});

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
