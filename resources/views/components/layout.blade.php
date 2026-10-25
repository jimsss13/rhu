<!DOCTYPE html>
<html lang="en">

<head>
    <title>RHU Lucban</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="bootstrap-template/assets/img/rhu-logo.png" type="image/x-icon" />
    <x-header-bootstrap-import />

</head>

<body class="overflow-hidden" style="height: 100%; background-color: #fcfcfc !important;">

    <div class="wrapper">
        <x-sidebar />
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            RHU-QMS
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                        <span class="luqms-title text-white">RHU Management System</span>
                    <div class="container-fluid">
                        {{--for notification --}}
                         <livewire:notifications />
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <span class="profile-username">
                                        @auth
                                            <span class="profile-username mr-4">
                                                <span class="op-7 text-white">Hi,</span>
                                                <span class="fw-bold text-white"> {{ \Illuminate\Support\Str::title(Auth::user()->f_name) }}</span>
                                            </span>
                                        @endauth
                                    </span>
                                    <div>
                                        <img style="width:4rem; height:4rem; margin-bottom:10px;" class="img-thumbnail mt-2 rounded-full"
                                        src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/img/user.png') }}"
                                        alt="Profile Picture">

                                        {{-- <img style="width:4rem; height:4rem;" src="images\img\user.png" alt="fsfds"> --}}
                                    </div>
 
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn" style="margin-right: 20px; margin-top:10px;">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">

                                                    <img style="width:4rem; height:4rem;" class="img-thumbnail mt-2 rounded-full"
                                                    src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/img/user.png') }}"
                                                    alt="Profile Picture">
                                                </div>
                                                <div class="u-text">

                                                    @auth
                                                    <h4>
                                                        {{ \Illuminate\Support\Str::title(Auth::user()->f_name) }}
                                                        {{ \Illuminate\Support\Str::title(Auth::user()->l_name) }}
                                                    </h4>
                                                 @endauth

                                                    <!-- Display the current department dynamically -->
                                                    <p class="text-muted current_department">
                                                        {{ session('currentDepartment') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>



                                        <a href="{{ route('edit') }}"
                                            class="w-full block text-left px-3 mt-2 text-gray-800 hover:bg-gray-200">
                                             Profile
                                         </a>



                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>

                                            @auth
                                                <form method="POST" action="/logout">
                                                    @csrf
                                                    <x-form-button class="dropdown-item ">Log Out</x-form-button>
                                                </form>
                                            @endauth


                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>


            </div>

            <div class="container overflow-auto auto-refresh" style="height: 100%; background-color: rgb(228 228 231 / var(--tw-bg-opacity, 1)); ">
                {{ $slot }}
            </div>

            {{-- javascript import --}}
            <x-js-bootstrap-down />



              {{-- validation for number --}}
                <script>
                    function validateQuantityInput(event) {
                        // Allow only numbers, no letters or symbols
                        event.target.value = event.target.value.replace(/[^0-9]/g, '');

                        // If the value is less than 1, clear the input or reset to 1
                        if (event.target.value < 1) {
                            event.target.value = 1;
                        }
                    }

                    // Apply the validation function to all elements with the 'quantity-input' class
                    document.querySelectorAll('.quantity-input').forEach(function(input) {
                        input.addEventListener('input', validateQuantityInput);
                    });
                </script>

                <script>
                    function capitalizeAndValidate(event) {
                        // Keep only letters and spaces
                        let inputValue = event.target.value.replace(/[^a-zA-Z\s]/g, '');

                        // Allow only one space between words by replacing multiple spaces with a single space
                        inputValue = inputValue.replace(/\s+/g, ' ');

                        // Capitalize the first letter of each word
                        inputValue = inputValue.replace(/\b\w/g, char => char.toUpperCase());

                        // Set the cleaned-up value back to the input field
                        event.target.value = inputValue;
                    }

                    // Apply the validation function to all elements with the 'capitalize-input' class
                    document.querySelectorAll('.capitalize-input').forEach(function(input) {
                        input.addEventListener('input', capitalizeAndValidate);
                    });
                </script>




</body>

</html>