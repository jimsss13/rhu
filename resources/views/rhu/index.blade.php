<!DOCTYPE html>
<html lang="zxx">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  	<meta name="description" content="Orbitor,business,company,agency,modern,bootstrap4,tech,software">
  	<meta name="author" content="themefisher.com">

  <title>Homepage</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />

  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Icon Font Css -->
  <link rel="stylesheet" href="plugins/icofont/icofont.min.css">
  <!-- Slick Slider  CSS -->
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css">
  <link rel="stylesheet" href="plugins/slick-carousel/slick/slick-theme.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="icon" href="../images/img/rhu-logo.png" type="image/x-icon" />
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

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
 <!-- Include SweetAlert2 CSS -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 @livewireStyles
</head>

<body class="flex flex-col min-h-screen">
    <header class="bg-blue-700 text-white p-4" id="rhu-homepage" style="position: sticky; top: 0; z-index: 1;">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex-none">
                <a href="/"><img src="images\ehealth.png" alt="EHEALTH LUCBAN Logo" class="h-6 w-auto sm:h-8"></a>
                <button class="hamburger" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                  </button>
            </div>
            <nav class="nav">
                <ul class="flex space-x-2">
                    <li><a href="#" class="nav">Home</a></li>
                    <li><a href="#rhu-services" class="nav">Services</a></li>
                    <li><a href="#rhu-about" class="nav">About</a></li>
                    <li><a href="#rhu-contact" class="nav">Contacts</a></li>

                    <!-- Guest (Not Authenticated) -->
                    @guest
                    <li>
                        <a href="/login" class="login-button px-4 py-2 rounded-full" style="color:white; background-color:#226bf1;"
                        onmouseover="this.style.backgroundColor='#e12454'"
                        onmouseout="this.style.backgroundColor='#226bf1'"
                        >Login</a>
                    </li>
                    @endguest

                    <!-- Authenticated User -->
                    @auth
                    <li class="relative">
                        <a href="#">
                            <img 
                                id="profile-menu-button" 
                                style="width:auto; height:40px; padding: 0; border-radius:50%; margin-top:-10px;" 
                                class="profile-pic img-thumbnail"
                                src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/img/user.png') }}"
                                alt="Profile Picture"
                            >
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


<!-- Slider Start -->
<section class="banner " >
	<div class="container" >
		<div class="row">
			<div class="col-lg-6 col-md-12 col-xl-7">
				<div class="block">
					<div class="divider mb-5"></div>
					<span class="text-uppercase text-sm letter-spacing text-white"><br>EHEALTH LUCBAN</span>
					<h1 class="mb-3 mt-3">Your most trusted health partner in Lucban</h1>

					<p class="mb-4 pr-5 text-white">Healthier Lucban, one click at a time.</p>
					<div class="btn-container">
                        <!-- For Guests -->
                        @guest
                        <a href="/login" class="btn btn-main-2 btn-icon btn-round-full">
                            Make Appointment <i class="icofont-simple-right ml-2"></i>
                        </a>
                        @endguest

                        <!-- For Authenticated Users -->
                        @auth
                        <a href="#rhu-appointment" class="btn btn-main-2 btn-icon btn-round-full">
                            Make Appointment <i class="icofont-simple-right ml-2"></i>
                        </a>
                        @endauth
                    </div>

				</div>
			</div>
		</div>
	</div>
</section>

<section class="features">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="feature-block d-lg-flex">
					<div class="feature-item mb-5 mb-lg-0">
						<div class="feature-icon mb-4">
							<i class="icofont-surgeon-alt"></i>
						</div>
						<span>Online Appointment</span>
						<h4 class="mb-3">How It works!</h4>
						<p class="mb-4">Register, Login, Book, Approve and Experience Online Appointment effortlessly with our user-friendly Appointment Website</p>
                        @guest

                        <li>
                            <a href="/register" class="btn btn-main btn-round-full">Register</a>
                        </li>
                        @endguest
					</div>

					<div class="feature-item mb-5 mb-lg-0">
						<div class="feature-icon mb-4">
							<i class="icofont-ui-clock"></i>
						</div>
						<span>Timing schedule</span>
						<h4 class="mb-3">Working Hours</h4>
						<ul class="w-hours list-unstyled">
		                    <li class="d-flex justify-content-between">Mon - Fri (Morning): <span>9:00 - 11:30</span></li>
		                    <li class="d-flex justify-content-between">Mon - Fri (Afternoon): <span>1:00 - 3:30</span></li>
		                    <li class="d-flex justify-content-between">Weekends and Holidays : <span>CLOSED</span></li>
		                </ul>
					</div>

					<div class="feature-item mb-5 mb-lg-0">
						<div class="feature-icon mb-4">
							<i class="icofont-support"></i>
						</div>
						<span>Lucban Emegency Hotlines</span>
						<h4 class="mb-3">RHU: (420)-540-3382</h4>
						<ul class="w-hours list-unstyled">
		                    <li class="d-flex justify-content-between">Bureau of Fire Protection(BFP): <span>0932 603 1222</span></li>
		                    <li class="d-flex justify-content-between">Philippine National Police(PNP): <span>0998 520 4211</span></li>
		                    <li class="d-flex justify-content-between">(DRRMO) / (MDRRMO) : <span>0917 520 4211</span></li>
		                </ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="section about bg-white" style="margin-top:2rem; margin-bottom:2rem;">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-4 col-sm-6">
				<div class="about-img">
					<img src="../images/img/home1.png" alt="" class="img-fluid">
					<img src="../images/img/home2.png" alt="" class="img-fluid mt-4">
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="about-img mt-4 mt-lg-0">
					<img src="../images/img/home3.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="about-content pl-4 mt-4 mt-lg-0">
					<h2 class="title-color">Personal care <br>& healthy living</h2>
					<p class="mt-4 mb-5">We provide a medical service for the residence on the town of Lucban, Quezon.</p>

					{{-- <a href="service.html" class="btn btn-main-2 btn-round-full btn-icon">Services<i class="icofont-simple-right ml-3"></i></a> --}}
				</div>
			</div>
		</div>
	</div>
</section>
<section class="cta-section bg-white" style="margin-top:-3rem; margin-bottom:-10rem;">
	<div class="container mb-8">
		<div class="cta position-relative">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-doctor"></i>
						<span class="h3">32</span>
						<p>Barangays</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-flag"></i>
						<span class="h3">53,091</span>
						<p>Residents</p>
					</div>
				</div>

				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-badge"></i>
						<span class="h3">3</span>
						<p>Available Online Services</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-globe"></i>
						<span class="h3">39</span>
						<p>Healthcare Staff</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section service" id="rhu-services">
    <!-- Title Section -->
    <section class="page-title bg-1">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block text-center">
                        <span class="text-white">Our Services</span>
                        <h1 class="text-capitalize mb-5 text-lg">Services Offered and Fee's</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section service-2 bg-white mt-0">
        <div class="container">
            <div class="row py-4">
                <!-- Laboratory Service -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-block mb-5 service-laboratory">
                        <img src="../images/img/lab.png" alt="Laboratory" class="img-fluid">
                        <div class="content">
                            <h4 class="mt-4 mb-2 title-color">Laboratory</h4>
                            <p class="mb-4">Comprehensive laboratory services for accurate diagnostics.</p>
                        </div>
                    </div>
                </div>

                <!-- Consultation Service -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-block mb-5 service-consultation">
                        <img src="../images/img/consultation.png" alt="Consultation" class="img-fluid">
                        <div class="content">
                            <h4 class="mt-4 mb-2 title-color">Consultation</h4>
                            <p class="mb-4">Professional medical consultation for personalized care.</p>
                        </div>
                    </div>
                </div>

                <!-- Vaccination Service -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-block mb-5 service-vaccination">
                        <img src="../images/img/vax.png" alt="Vaccination" class="img-fluid">
                        <div class="content">
                            <h4 class="mt-4 mb-2 title-color">Vaccination</h4>
                            <p class="mb-4">Comprehensive vaccination services for all ages.</p>
                        </div>
                    </div>
                </div>

                <!-- Other Services -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="service-block mb-5 service-other">
                        <img src="../images/img/others.png" alt="Other" class="img-fluid">
                        <div class="content">
                            <h4 class="mt-4 mb-2 title-color">Other Services</h4>
                            <p class="mb-4">Services that have fees to consider.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

            
        @auth

<section class="py-16 bg-white" style="margin-bottom:6rem; margin-top:-17rem" id="rhu-appointment">
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
                <!-- Dropdown added here -->
                <div class="mt-4">
                    <select id="serviceSelect" class="form-select form-select-lg mb-3 mt-4" aria-label="Service Selection" onchange="showForm()">
                        <option selected>Select a Service</option>
                        <option value="Laboratory">Laboratory</option>
                        <option value="Consultation">Consultation</option>
                        <option value="Vaccination">Vaccination</option>
                        <option value="Blood">Blood</option>
                    </select>
                </div>
            </div>
            <div class="text-center w-1/3">
                <div class="bg-blue-500 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 text-2xl font-bold">3</div>
                <h3 class="text-xl font-bold mb-2">Get Services</h3>
                <p>Receive your healthcare services at RHU Lucban</p>
            </div>
        </div>

        <x-rhu-appointment :appointments="$appointments" :tests="$tests" :vaccines="$vaccines" />



</section>


@endauth

        <!-- Modal Overlay -->
        <div id="serviceModal" class="overlay-modal">
            <div class="content-modal">
                <div class="modal-header">
                    <h2 id="modalTitle">Service Details</h2>
                    <span class="modal-close">&times;</span>
                </div>
                <div id="modalBody">
                    <!-- Modal content will be dynamically inserted here -->
                </div>
            </div>
        </div>

	</div>
</section>
{{-- <section class="section appoinment">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 ">
				<div class="appoinment-content">
					<img src="images/about/Front.jpg" alt="" class="img-fluid">
					<div class="emergency">
						<h2 class="text-lg"><i class="icofont-phone-circle text-lg"></i>+63 93634 23105</h2>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-10 ">
				<div class="appoinment-wrap mt-5 mt-lg-0">
					<h2 class="mb-2 title-color">Book appoinment</h2>
					<p class="mb-4">The online booking system enhances convenience for residents of Lucban accessing healthcare by reducing travel hassle, improving patient satisfaction, promoting regular check-ups, and benefiting community well-being.</p>
					     <form id="#" class="appoinment-form" method="post" action="#">
                    <div class="row">
                         <div class="col-lg-6">
                            <div class="form-group">
                                <select class="form-control" id="exampleFormControlSelect1">
                                  <option>Choose Department Services</option>
                                  <option>Consultation</option>
                                  <option>Blood Donation</option>
								  <option>Laboratory</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <select class="form-control" id="exampleFormControlSelect2">
                                  <option>Select Time</option>
								  <option>9:00 am</option>
                                  <option>9:30 am</option>
                                  <option>10:00 am</option>
                                  <option>10:30 am</option>
                                  <option>11:00 am</option>
                                  <option>11:30 am</option>
                                  <option>1:00 pm</option>
                                  <option>1:30 pm</option>
								  <option>2:00 pm</option>
                                  <option>2:30 pm</option>
                                  <option>3:00 pm</option>
                                  <option>3:30 pm</option>
                                  <option>4:00 pm</option>
								  <option>4:30 pm</option>
                                </select>
                            </div>
                        </div>

                         <div class="col-lg-6">
                            <div class="form-group">
                                <input name="date" id="date" type="text" class="form-control" placeholder="dd/mm/yyyy">
                            </div>
                        </div>

						<div class="col-lg-6">
                            <div class="form-group">
                                <select class="form-control" id="exampleFormControlSelect2">
                                  <option>Select Barangay</option>
								  <option>Barangay 1</option>
                                  <option>Barangay 2</option>
                                  <option>Barangay 3</option>
                                  <option>Barangay 4</option>
                                  <option>Barangay 5</option>
                                  <option>Barangay 6</option>
                                  <option>Barangay 7</option>
                                  <option>Barangay 8</option>
								  <option>Barangay 9</option>
                                  <option>Barangay Abang</option>
                                  <option>Barangay Aliliw</option>
                                  <option>Barangay Atolinao</option>
								  <option>Barangay Ayuti</option>
								  <option>Barangay Igang</option>
								  <option>Barangay Kabatete</option>
								  <option>Barangay Kakawit</option>
								  <option>Barangay Kalangay</option>
								  <option>Barangay Kalyaat</option>
								  <option>Barangay Kilib</option>
								  <option>Barangay Kulapi</option>
								  <option>Barangay Mahabang Parang</option>
								  <option>Barangay Malupak</option>
								  <option>Barangay Manasa</option>
								  <option>Barangay May-it</option>
								  <option>Barangay Nagsinamo</option>
								  <option>Barangay Nalunao</option>
								  <option>Barangay Palola</option>
								  <option>Barangay Piis</option>
								  <option>Barangay Samil</option>
								  <option>Barangay Tiawe</option>
								  <option>Barangay Tinamnan</option>
                                </select>
                            </div>
                        </div>

                         <div class="col-lg-6">
                            <div class="form-group">
                                <input name="name" id="name" type="text" class="form-control" placeholder="Full Name">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <input name="phone" id="phone" type="Number" class="form-control" placeholder="Phone Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group-2 mb-4">
                        <textarea name="message" id="message" class="form-control" rows="6" placeholder="Your Message / Feelings right now"></textarea>
                    </div>

                    <a class="btn btn-main btn-round-full" href="login1.php" >Make Appoinment <i class="icofont-simple-right ml-2  "></i></a>
                </form>
            </div>
			</div>
		</div>
	</div>
</section> --}}


<section class="page-title bg-1" style="margin-top: -4rem" id="rhu-about">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="block text-center">
            <span class="text-white">RHU</span>
            <h1 class="text-capitalize mb-5 text-lg">About Us</h1>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section about-page bg-white">
      <div class="container">
          <div class="row">
              <div class="col-lg-4">
                  <h2 class="title-color">History of RHU Lucban, Quezon</h2>
                  <div class="divider mr-2 my-1"></div>
              </div>
              <div class="col-lg-8">
                  <p>RHU Lucban is constructed under the IBRD_POP.1 Project through the Ministry of Health in Coordination with Mayor Armando M. Racelis and the Members of the Sangguniang Bayan of Lucban Quezon as per Resolution NO.50, Series of 1975.<br>

                      The Rural Health Unit (RHU) of Lucban has a rich history deeply rooted in the community's commitment to improving public health. Established in the mid-20th century, the RHU was founded as part of a nationwide initiative to provide accessible healthcare services to rural areas in the Philippines. The unit was created to address the unique health challenges faced by the residents of Lucban, a picturesque town nestled at the foot of Mount Banahaw in Quezon Province.<br>

                      In its early years, the RHU of Lucban focused on basic healthcare services, maternal and child health, and the control of communicable diseases. The primary objective was to reduce infant mortality rates, improve maternal health, and combat prevalent diseases such as tuberculosis and malaria. Health workers, including doctors, nurses, and midwives, were trained and deployed to serve the local population. These efforts were supported by the local government and various non-governmental organizations.</p>
              </div>
          </div>
      </div>
  </section>

  <section class="fetaure-page bg-white">
      <div class="container">
          <div class="row">
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item mb-5 mb-lg-0">
                      <img src="images/about/about-1.jpg" alt="" class="img-fluid w-100">
                      <h4 class="mt-3">Mid-20th century.</h4>
                      <p>Established in the mid-20th century, the RHU was founded as part of a nationwide initiative to provide accessible healthcare services to rural areas in the Philippines.</p>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item mb-5 mb-lg-0">
                      <img src="images/about/about-2.jpg" alt="" class="img-fluid w-100">
                      <h4 style="margin-top:30px;">Mayor Armando M. Racelis</h4>
                      <p> Project through the Ministry of Health in Coordination with Mayor Armando M. Racelis and the Members of the Sangguniang Bayan of Lucban Quezon as per Resolution NO.50, Series of 1975.</p>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item mb-5 mb-lg-0">
                      <img src="images/about/about-3.png" alt="" class="img-fluid w-100">
                      <h4 class="mt-3">Municipality of Lucban</h4>
                      <p>The Lucban, officially the Municipality of Lucban, is popularly known as the Art Capital of Quezon province. RHU was built for the residents of Lucban, Quezon that has 32 barangays.</p>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item">
                      <img src="../images/img/rhu-workers.png" alt="" class="img-fluid w-100">
                      <h4 style="margin-top:45px;">RHU Health Workers</h4>
                      <p>Including doctors, nurses, and midwives, were trained and deployed to serve the local population.</p>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <section class="section awards bg-white" style="margin-top:2rem; margin-bottom:2rem;">
      <div class="container">
          <div class="row">
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item mb-5 mb-lg-0">
                  <h2 class="title-color">Our Healthcare Achievements </h2>
                  <div class="divider mt-4 mb-5 mb-lg-0"></div>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item">
                      <img src="images/about/1.jpg" alt="" class="img-fluid">
                      <h4 class="mt-3">July 3, 2023</h4>
                      <p>Most Promising Municipal Nutrition Council, Most Promising MNAO, and Most Promising BNS- RHU Lucban.</p>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item">
                      <img src="images/about/2.jpg" alt="" class="img-fluid">
                      <h4 class="mt-3">August 16, 2023</h4>
                      <p>TB Top performer Treatment Success Rate in Calabarzon</p>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6">
                  <div class="about-block-item">
                      <img src="images/about/3.jpg" alt="" class="img-fluid">
                      <h4 class="mt-3">December 6, 2023</h4>
                      <p>Best Performing LGU Environmental and Occupational</p>
                  </div>
              </div>
          </div>
      </div>
  </section>

  <section class="section team bg-white">
      <div class="container">
          <div class="row justify-content-center">
              <div class="col-lg-6">
                  <div class="section-title text-center">
                      <h2 class="mb-4">Meet Our Office of Health Services 2024</h2>
                      <div class="divider mx-auto my-4"></div>
                  </div>
              </div>
          </div>

          <div class="row" id="teamContainer">
              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="team-block mb-5 mb-lg-0"
                       data-name="Hon. Agustin Villaverde"
                       data-position="Municipal Mayor"
                       data-work-position="Provides overall municipal leadership and strategic direction"
                       data-location="Municipal Mayor's Office, Main Building, 2nd Floor"
                       data-schedule="Monday-Friday, 8:00 AM - 5:00 PM, By Appointment">
                      <img src="images/people/mayor.png" alt="Hon. Agustin Villaverde" class="img-fluid w-100">
                      <div class="content">
                          <h4 class="mt-4 mb-0">Hon. Agustin Villaverde</h4>
                          <p>Municipal Mayor</p>
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="team-block mb-5 mb-lg-0"
                       data-name="Hon. Kaka Abcede"
                       data-position="Municipal Vice Mayor"
                       data-work-position="Supports municipal operations and community health initiatives"
                       data-location="Vice Mayor's Office, Municipal Hall"
                       data-schedule="Monday-Friday, 9:00 AM - 4:00 PM">
                      <img src="images/people/kaka.jpg" alt="Hon. Kaka Abcede" class="img-fluid w-100">
                      <div class="content">
                          <h4 class="mt-4 mb-0">Hon. Kaka Abcede</h4>
                          <p>Municipal Vice Mayor</p>
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="team-block mb-5 mb-lg-0"
                       data-name="Hon. Mariano (Jun) Ver"
                       data-position="Committee on Health and Sanitation"
                       data-work-position="Monitor health and sanitation policies and implementation"
                       data-location="Councilor's Office, Municipal Hall"
                       data-schedule="Monday-Friday, 9:00 AM - 4:00 PM">
                      <img src="images/people/aven.jpg" alt="Hon. Mariano (Jun) Ver" class="img-fluid w-100">
                      <div class="content">
                          <h4 class="mt-4 mb-0">Hon. Aven Rada</h4>
                          <p>Committee on Health and Sanitation</p>
                      </div>
                  </div>
              </div>

              <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="team-block mb-5 mb-lg-0"
                       data-name="Rishat Ahmed"
                       data-position="Lab Manager"
                       data-work-position="Manages laboratory operations and medical testing services"
                       data-location="RHU Health Laboratory"
                       data-schedule="Monday-Friday, 7:00 AM - 5:00 PM">
                      <img src="images/people/nikki.jpg" alt="Rishat Ahmed" class="img-fluid w-100">
                      <div class="content">
                          <h4 class="mt-4 mb-0">Hon. Nikki Deveza</h4>
                          <p>Lab Manager</p>
                      </div>
                  </div>
              </div>

          </div>
      </div>
  </section>

  <!-- Modal Overlay -->
  <div id="teamModal" class="modal">
      <div class="modal-content">
          <span class="close-modal">&times;</span>
          <h3 id="modalName">Member Name</h3>
          <p id="modalPosition">Position</p>

          <div class="modal-info">
              <h4>Position on Work</h4>
              <p id="modalWorkPosition">Details about work responsibilities</p>

              <h4>Where to Find</h4>
              <p id="modalLocation">Location or contact information</p>

              <h4>Available Schedule</h4>
              <p id="modalSchedule">Working hours or availability</p>
          </div>
      </div>
  </div>

  <section class="section contact-info pb-0 bg-white" style="margin-top:2rem; margin-bottom:2rem" id="rhu-contact">
      <div class="container">
          <div class="section-title text-center">
              <h2 class="text-md mb-2">Contact Us</h2>
              <div class="divider mx-auto my-2"></div> <br><br>
          </div>
           <div class="row">
              <div class="col-lg-4 col-sm-6 col-md-6">
                  <div class="contact-block mb-4 mb-lg-0">
                      <i class="icofont-live-support"></i>
                      <h5>Call Us</h5>
                      (420)-540-3382
                  </div>
              </div>
              <div class="col-lg-4 col-sm-6 col-md-6">
                  <div class="contact-block mb-4 mb-lg-0">
                      <i class="icofont-support-faq"></i>
                      <h5>Message Us</h5>
                       Rhu Lucban@facebook.com
                  </div>
              </div>
              <div class="col-lg-4 col-sm-6 col-md-6">
                  <div class="contact-block mb-4 mb-lg-0">
                      <i class="icofont-location-pin"></i>
                      <h5>Location</h5>
                      Barangay Abang, Lucban, Quezon
                  </div>
              </div>
          </div>
          <br><br><br>
      </div>
  </section>
















 <!-- NEWS -->
 <section class="bg-white" id="news" data-stellar-background-ratio="2.5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <!-- SECTION TITLE -->
                <div class="section-title wow fadeInUp" data-wow-delay="0.1s">
                    <h2 class="text-md mb-2">Latest News</h2>
                    <div class="divider mx-auto my-1"></div><br><br>
                </div>
            </div>

            @foreach ($announcements as $announcement)
                <div class="col-md-4 col-sm-6">
                    <!-- NEWS THUMB -->
                    <div class="news-thumb wow fadeInUp" data-wow-delay="0.4s">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#newsModal" 
                           onclick="openNewsModal('{{ $announcement->title }}', '{{ $announcement->description }}', '{{ $announcement->fullcontext }}', '{{ asset('storage/' . $announcement->image) }}')">
                            <img src="{{ asset('storage/' . $announcement->image) }}" class="img-fluid" alt="Announcement Image">
                        </a>
                        <div class="news-info">
                            <span>{{ \Carbon\Carbon::parse($announcement->date)->format('F d, Y') }}</span>
                            <h3>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#newsModal" 
                                   onclick="openNewsModal('{{ $announcement->title }}', '{{ $announcement->description }}', '{{ $announcement->fullcontext }}', '{{ asset('storage/' . $announcement->image) }}')">
                                    {{ $announcement->title }}
                                </a>
                            </h3>
                            <p>{{ Str::limit($announcement->description, 100) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newsModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="newsModalImage" class="img-fluid mb-3" alt="News Image">
                <p id="newsModalDescription"></p>
                <hr>
                <div id="newsModalFullContext" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function openNewsModal(title, description, fullcontext, imageUrl) {
        // Set modal content dynamically
        document.getElementById('newsModalLabel').innerText = title;
        document.getElementById('newsModalDescription').innerText = description;
        document.getElementById('newsModalImage').src = imageUrl;
        document.getElementById('newsModalFullContext').innerText = fullcontext;
    }
</script>


<section class="section clients bg-white" style="margin-top:2rem; margin-bottom:2rem;">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="section-title text-center">
					<h2>Frequently Ask Questions</h2>
					<div class="divider mx-auto my-1"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="faq-container">
                <div class="faq-grid">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item">
						<div class="faq-header">
                        <div class="faq-question">What services are provided by the Rural Health Management Unit in Lucban?</div>
                        <div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">The Rural Health Management Unit in Lucban provides various services including primary healthcare, maternal and child health, immunizations, family planning, health education programs, and chronic disease management.</div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">How can I schedule an appointment using the eHealth Lucban system?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">You can schedule an appointment by logging into your eHealth Lucban account, selecting the 'Book Appointment' option, choosing your preferred date and time, and selecting the type of service you need. You'll receive a confirmation email once your appointment is set.</div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">How do I create an account on the eHealth Lucban platform?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">To create an account, visit the eHealth Lucban website and click on the 'Register' button. Fill in your personal details, create a username and password, and verify your email address. Once completed, you can log in and access the system's features.</div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">Can I view my medical records through the eHealth Lucban system?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">Yes, once logged in, you can access your medical records under the 'My Health Records' section. This includes past consultations, prescriptions, lab results, and immunization records. However, some sensitive information may require additional verification.</div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">Is my personal health information secure on the eHealth Lucban platform?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">Yes, we take data security very seriously. The eHealth Lucban system uses state-of-the-art encryption and follows strict privacy regulations to ensure your personal health information is protected. Only authorized healthcare providers can access your medical records.</div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">How can I request a prescription refill through the eHealth Lucban system?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">To request a prescription refill, log into your account and navigate to the 'Prescriptions' section. Select the medication you need refilled and click on 'Request Refill'. Your request will be reviewed by a healthcare provider, and you'll be notified when it's approved and ready for pickup.</div>
                    </div>

                    <!-- FAQ Item 7 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">Can I use the eHealth Lucban system for telemedicine consultations?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">Yes, eHealth Lucban supports telemedicine consultations. You can schedule a video call with a healthcare provider for non-emergency issues. During your appointment time, log in and click on the 'Start Consultation' button to begin your video call.</div>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">How do I check my lab results on the eHealth Lucban platform?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">To view your lab results, log into your account and go to the 'Lab Results' section. Here you'll find a list of your recent tests. Click on a specific test to view the results. If you have questions about your results, you can request a follow-up consultation through the system.</div>
                    </div>

                    <!-- FAQ Item 9 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">What should I do if I encounter technical issues with the eHealth Lucban system?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">If you experience technical issues, first try refreshing the page or logging out and back in. If the problem persists, click on the 'Support' link at the bottom of the page to access our help center. You can also contact our technical support team via email or phone for assistance.</div>
                    </div>

                    <!-- FAQ Item 10 -->
                    <div class="faq-item">
						<div class="faq-header">
							<div class="faq-question">Can family members share an eHealth Lucban account?</div>
							<div class="faq-checkmark"></div>
						</div>
                        <div class="faq-answer">For privacy and security reasons, each individual should have their own eHealth Lucban account. However, parents or guardians can create and manage accounts for their minor children. There's also an option to grant access to family members for emergency situations, which can be set up in your account settings.</div>
                    </div>
                </div>
    </div>
</section>



<!-- footer Start -->
<footer class="footer section gray-bg">
	<div class="container" style="margin-top:-4rem;">
		<div class="row">
			<div class="col-lg-4 mr-auto col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<div class="logo mb-4">
						<img src="images/logo.png" alt="" class="img-fluid">
					</div>
					<p>In its early years, the RHU of Lucban focused on basic healthcare services, maternal and child health, and the control of communicable diseases. </p>
					<ul class="list-inline footer-socials mt-4">
						<li class="list-inline-item"><a href="https://www.facebook.com/profile.php?id=61559717789864"><i class="icofont-facebook"></i></a></li>
						<li class="list-inline-item"><a href="https://www.facebook.com/LGULucbanQuezon"><i class="icofont-twitter"></i></a></li>
						<li class="list-inline-item"><a href="https://healthcarephilippines.com/directory/lucban-rural-health-unit/"><i class="icofont-linkedin"></i></a></li>
					</ul>
					<p>Rural Health Unit 4HC4+X3X, Lucban-Sampaloc road
						Barangay Abang, Lucban, Quezon, 4328
						Philippines</p>
				</div>
			</div>

			<div class="col-lg-2 col-md-6 col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">Services</h4>
					<div class="divider mb-4"></div>

					<ul class="list-unstyled footer-menu lh-35">
						<li><a href="#">Blood Donation </a></li>
						<li><a href="#">Consultation</a></li>
						<li><a href="#">Laboratory</a></li>
						<li><a href="#">Operating Hours</a></li>
						<li><a href="#">Laboratory Quotation</a></li>
					</ul>
				</div>
			</div>

			<div class="col-lg-2 col-md-6 col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">Support</h4>
					<div class="divider mb-4"></div>

					<ul class="list-unstyled footer-menu lh-35">
						<li><a href="#">Terms & Conditions</a></li>
						<li><a href="#">Privacy Policy</a></li>
						<li><a href="#">Company Support </a></li>
						<li><a href="#">FAQuestions</a></li>
						<li><a href="#">Company Licence</a></li>
					</ul>
				</div>
			</div>

			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="widget widget-contact mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">Get in Touch</h4>
					<div class="divider mb-4"></div>

					<div class="footer-contact-block mb-4">
						<div class="icon d-flex align-items-center">
							<i class="icofont-email mr-3"></i>
							<span class="h6 mb-0">Support Available for 24/7</span>
						</div>
						<h4 class="mt-2"><a href="tel:+23-345-67890">JimuelHepana@gmail.com</a></h4>
					</div>

					<div class="footer-contact-block">
						<div class="icon d-flex align-items-center">
							<i class="icofont-support mr-3"></i>
							<span class="h6 mb-0">Mon to Fri : 09:00am - 5:00pm</span>
						</div>
						<h4 class="mt-2"><a href="tel:+23-345-67890">+63 9363 423105</a></h4>
					</div>
				</div>
			</div>

			<div class="col-lg-12 col-md-6 col-sm-6">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3869.270935568038!2d121.55486607417356!3d14.12015768631123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd540c892008ad%3A0xab8ec4ddd4130b68!2sRural%20Health%20Unit-%20Lucban%2C%20Quezon!5e0!3m2!1sen!2sph!4v1726574582136!5m2!1sen!2sph"
				width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
			</div>

		</div>

		<div class="footer-btm py-4 mt-5">
			<div class="row align-items-center justify-content-between">
				<div class="col-lg-6">
					<div class="copyright">
						&copy; Copyright Reserved to RHU Lucban by Hipana, Madrid, Pondivida @2024-2025
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<a class="backtop js-scroll-trigger" href="#top">
						<i class="icofont-long-arrow-up"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</footer>
<script>
    // Wait for the DOM to load
    document.addEventListener("DOMContentLoaded", function () {
      const teamBlocks = document.querySelectorAll(".team-block");
      const modal = document.getElementById("teamModal");
      const modalName = document.getElementById("modalName");
      const modalPosition = document.getElementById("modalPosition");
      const modalWorkPosition = document.getElementById("modalWorkPosition");
      const modalLocation = document.getElementById("modalLocation");
      const modalSchedule = document.getElementById("modalSchedule");
      const closeModal = document.querySelector(".close-modal");
  
      // Add click event to each team block
      teamBlocks.forEach((block) => {
        block.addEventListener("click", function () {
          // Get data attributes
          modalName.textContent = this.getAttribute("data-name");
          modalPosition.textContent = this.getAttribute("data-position");
          modalWorkPosition.textContent = this.getAttribute("data-work-position");
          modalLocation.textContent = this.getAttribute("data-location");
          modalSchedule.textContent = this.getAttribute("data-schedule");
  
          // Show the modal
          modal.style.display = "block";
        });
      });
  
      // Close the modal when the close button is clicked
      closeModal.addEventListener("click", function () {
        modal.style.display = "none";
      });
  
      // Close the modal when clicking outside of it
      window.addEventListener("click", function (event) {
        if (event.target === modal) {
          modal.style.display = "none";
        }
      });
    });
  </script>
  
<script>
 document.addEventListener("DOMContentLoaded", function () {
  const hamburger = document.querySelector(".hamburger");
  const nav = document.querySelector(".nav ul");

  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("active");
    nav.classList.toggle("active");
  });
});



</script>

  <script>
    document.addEventListener("scroll", function () {
    const backTopButton = document.querySelector(".backtop");
    if (window.scrollY > 100) {
        backTopButton.classList.add("show");
    } else {
        backTopButton.classList.remove("show");
    }
});

  </script>

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

@livewireScripts
</body>
</html>