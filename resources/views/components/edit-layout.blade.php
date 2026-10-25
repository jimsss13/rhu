<!DOCTYPE html>
<html lang="zxx">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  	<meta name="description" content="Orbitor,business,company,agency,modern,bootstrap4,tech,software">
  	<meta name="author" content="themefisher.com">

  <title>Homepage</title>

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />

  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Icon Font Css -->
  <link rel="stylesheet" href="plugins/icofont/icofont.min.css">
  <!-- Slick Slider  CSS -->
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick-theme.css">

  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="css/style.css">
    <style>
        .hidden {
            display: none;
        }

        .date-time-picker {
        display: flex;
        flex-direction: column;
        width: 300px;
        margin: auto;
        }

        .time-button {
            display: inline-block;
            margin: 5px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .time-button:hover {
            background-color: #0056b3;
        }



    </style>
     @livewireStyles
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 <!-- Include SweetAlert2 CSS -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>


<body>


    <header class="bg-blue-700 text-white p-4" id="rhu-homepage" style="position: sticky; top: 0; z-index: 1;">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex-none">
                <a href="/"><img src="images\ehealth.png" alt="EHEALTH LUCBAN Logo" class="h-6 w-auto sm:h-8"></a>
            </div>
            <nav>
                <ul class="flex space-x-2">
                    <li><a href="/" class="nav-button">Home</a></li>
                    <li><a href="/#rhu-services" class="nav-button">Services</a></li>
                    <li><a href="/#rhu-about" class="nav-button">About</a></li>
                    <li><a href="/#rhu-contact" class="nav-button">Contacts</a></li>

                    <!-- Guest (Not Authenticated) -->
                    @guest
                    <li>
                        <a href="/login" class="bg-red-500 px-4 py-2 rounded-full hover:bg-green-600 transition duration-300">Login</a>
                    </li>
                    @endguest

                    <!-- Authenticated User -->
                    @auth
                    <li class="relative">
                        <a href="#">
                            {{-- <img id="profile-menu-button" src="images/img/user.png" alt="Profile Picture" class="profile-pic"
                                 style="width:2rem; height:2rem; padding: 0; border-radius:50%"> --}}

                                 <img  id="profile-menu-button" style="width:auto; height:40px; padding: 0; border-radius:50%; margin-top:-10px;" class=" profile-pic img-thumbnail"
                                 src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('../images/img/user.png') }}" 
                                 alt="Profile Picture">
                        </a>
                        <span class="text-white ml-1">{{ Auth::user()->f_name }}</span>
                        <div id="profile-menu" style="display: none; position: absolute; bottom: -4.5rem;"
                        class="mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                       
                       <a href="{{ route('edits') }}"
                       style="color: black; text-decoration: none; display: block; padding: 10px 20px; width: 100%; text-align: left;"
                       onmouseover="this.style.color='white'; this.style.backgroundColor='blue';" 
                       onmouseout="this.style.color='black'; this.style.backgroundColor='transparent';">
                           Profile
                       </a>
                       <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="color: black; text-decoration: none; display: block; padding: 10px 20px; width: 100%; text-align: left; background: transparent; border: none;"
                        onmouseover="this.style.color='white'; this.style.backgroundColor='red';" 
                        onmouseout="this.style.color='black'; this.style.backgroundColor='transparent';">
                            Logout
                        </button>
                    </form>
                   </div>

                    </li>
                    @endauth
                </ul>


            </nav>
        </div>
    </header>








    <div class="container overflow-auto auto-refresh" style="height: 100%; ">
        {{ $slot }}
    </div>




<script>
    // JavaScript to toggle between display: none and display: block
    document.getElementById('profile-menu-button').addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default anchor behavior
        const profileMenu = document.getElementById('profile-menu');
        if (profileMenu.style.display === 'none' || profileMenu.style.display === '') {
            profileMenu.style.display = 'block';
        } else {
            profileMenu.style.display = 'none';
        }
    });

    // Optional: Close the menu when clicking outside
    document.addEventListener('click', function (e) {
        const profileMenu = document.getElementById('profile-menu');
        const profileButton = document.getElementById('profile-menu-button');
        if (!profileMenu.contains(e.target) && e.target !== profileButton) {
            profileMenu.style.display = 'none';
        }
    });
</script>
<!-- Main jQuery -->
<script src="plugins/jquery/jquery.js"></script>
<!-- Bootstrap 4.3.2 -->
<script src="plugins/bootstrap/js/popper.js"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/counterup/jquery.easing.js"></script>
<!-- Slick Slider -->
<script src="plugins/slick-carousel/slick/slick.min.js"></script>
<!-- Counterup -->
<script src="plugins/counterup/jquery.waypoints.min.js"></script>
<script src="plugins/shuffle/shuffle.min.js"></script>
<script src="plugins/counterup/jquery.counterup.min.js"></script>
<!-- Google Map -->
<script src="plugins/google-map/map.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&callback=initMap"></script>
<script src="js/script.js"></script>
<script src="js/contact.js"></script>
<script src="js/faq.js"></script>
<script src="js/service.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

</body>