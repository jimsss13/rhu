<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Edit Profile</h2>

                <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" name="profile_picture" accept="image/*">
                            <small class="text-muted">Allowed formats: JPG, PNG, GIF (Max: 2MB)</small>

                            @if ($user->profile_picture)
                            <img  class="img-thumbnail mt-2" width="150"  src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">

                                {{-- <img src="{{ asset('storage/profile_pictures/' . $user->profile_picture) }}" class="img-thumbnail mt-2" width="150" alt="Profile Picture"> --}}
                            @endif

                            @error('profile_picture')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control capitalize-input" name="f_name" value="{{ old('f_name', $user->f_name) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control capitalize-input" name="m_name" value="{{ old('m_name', $user->m_name) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control capitalize-input" name="l_name" value="{{ old('l_name', $user->l_name) }}" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="bday" value="{{ old('bday', \Carbon\Carbon::parse($user->bday)->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" required>
                                <option value="Male" {{ $user->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ $user->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Prefer not to say" {{ $user->gender == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="contactNo" name="contactNo"
                                   value="{{ old('contactNo', $user->contactNo) }}"
                                   pattern="^09\d{9}$"
                                   maxlength="11"
                                   required
                                   placeholder="09XXXXXXXXX">
                            <small id="mobileError" class="text-danger" style="display:none;">Enter a valid 11-digit number starting with 09.</small>
                        </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                            <small id="emailError" class="text-danger" style="display:none;">Please enter a valid Gmail address.</small>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Update</button>
                        <a href="/" class="btn btn-danger px-4">Close</a>
                    </div>
                </form>
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
</body>
</html>
