<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="css/ange.css">
    <script src="script.js"></script>
    <title>Success Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .nav-button {
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
        }
        .nav-button:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .slideshow-container {
            position: relative;
            height: calc(100vh - 64px);
            width: 100vw;
            overflow: hidden;
            margin-top: 64px;
        }
        .slideshow-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            object-fit: cover;
        }
        .slideshow-image.active {
            opacity: 1;
        }
        .profile-pic {
            width: 30px;
            height: 30px;
            border-radius: 40%;
            object-fit: cover;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        /*appointment button*/
        .appointment-button {
            background-color: #00c853;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .appointment-button:hover {
            background-color: #00a846;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .appointment-button::after {
            content: "→";
            background-color: #1a237e;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .appointment-button:hover::after {
            transform: translateX(5px);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex-none">
                <a href="afterlogin.html"><img src="images/img/ehealth.png" alt="EHEALTH LUCBAN Logo" class="h-6 w-auto sm:h-8"></a>
            </div>
            <nav>
                <ul class="flex space-x-2">
                    {{-- <li><a href="afterlogin.html" class="nav-button  bg-blue-700">Home</a></li>
                    <li><a href="afterabout.html" class="nav-button">About</a></li>
                    <li><a href="afterservices.html" class="nav-button">Services</a></li>
                    <li><a href="afterfaqs.html" class="nav-button">FAQ'S</a></li> --}}
                    {{-- <li><a href="notifications.html" class="nav-button"><i class="fas fa-bell"></i></a></li> --}}
                    <li class="relative">
                        <a href="#" class="nav-button" id="profile-menu-button" >
                            <img src="images\img\user.png" alt="Profile Picture" class="profile-pic" style="border: solid 1px black; padding:0; position: absolute;">
                        </a>
                        <div id="profile-menu" style="position: absolute; bottom:-3rem;" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10" >
                           <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-200">Logout</button>
                            </form>
                        </div>
                    </li>



                </ul>
            </nav>
        </div>
    </header>

    <main class="flex-grow">
        <section class="slideshow-container">
            <img src="images/img/resbakuna.jpg" alt="Slideshow Image 1" class="slideshow-image active">
            <img src="images/img/chikiting.png" alt="Slideshow Image 2" class="slideshow-image">
            <img src="images/img/dietary.png" alt="Slideshow Image 3" class="slideshow-image">
        </section>

        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8 text-center">How It Works!</h2>
                <p class="text-xl text-center mb-12">Book, Approve and Experience Online Appointment effortlessly with our user-friendly Appointment Website</p>
                <div class="flex justify-between items-start">
                    <div class="text-center w-1/3">
                        <div class="bg-blue-500 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">1</div>
                        <h3 class="text-xl font-bold mb-2">Book Appointment</h3>
                        <p>Effortlessly book appointments at your convenience</p>
                    </div>
                    <div class="text-center w-1/3">
                        <div class="bg-blue-500 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">2</div>
                        <h3 class="text-xl font-bold mb-2">Select Services</h3>
                        <p>Choose services offered by the RHU Lucban with ease</p>
                    </div>
                    <div class="text-center w-1/3">
                        <div class="bg-blue-500 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                        <h3 class="text-xl font-bold mb-2">Get Services</h3>
                        <p>Receive your healthcare services at RHU Lucban</p>
                    </div>
                </div>
                <div class=" py-16 mb-1 text-center">
                    <div class="flex justify-between text-center items-center mb-4"></div>
                    <h2 class="text-xl font-bold mb-2">Click the button to book an appointment now!</h2><br>
                    <a href="schedule.html">
                        <button class="appointment-button mx-auto">
                            BOOK AN APPOINTMENT
                        </button>
                    </a>
                </div>
        </section>
    </main>
    <script>
        document.getElementById('profile-menu-button').addEventListener('click', function(event) {
            event.preventDefault();
            var menu = document.getElementById('profile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    <script>
        function startSlideshow() {
            const images = document.querySelectorAll('.slideshow-image');
            let currentIndex = 0;

            setInterval(() => {
                images[currentIndex].classList.remove('active');
                currentIndex = (currentIndex + 1) % images.length;
                images[currentIndex].classList.add('active');
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', startSlideshow);
    </script>
</body>
</html>
