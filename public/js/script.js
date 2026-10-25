


;(function ($) {

	'use strict';
	
 // SCROLL TO TOP
  
  $(window).on('scroll', function () {
    if ($(window).scrollTop() > 70) {
        $('.backtop').addClass('reveal');
    } else {
        $('.backtop').removeClass('reveal');
    }
});
 
	$('.portfolio-single-slider').slick({
		infinite: true,
		arrows: false,
		autoplay: true,
		autoplaySpeed: 2000

	});

	$('.clients-logo').slick({
		infinite: true,
		arrows: false,
		autoplay: true,
		slidesToShow: 6,
		slidesToScroll: 6,
		autoplaySpeed: 6000,
		responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow:6,
		        slidesToScroll: 6,
		        infinite: true,
		        dots: true
		      }
		    },
		    {
		      breakpoint: 900,
		      settings: {
		        slidesToShow:4,
		        slidesToScroll: 4
		      }
		    },{
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 4,
		        slidesToScroll: 4
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    }
		  
  		]
	});

	$('.testimonial-wrap').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		infinite: true,
		dots: true,
		arrows: false,
		autoplay: true,
		vertical:true,
		verticalSwiping:true,
		autoplaySpeed: 6000,
		responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow:1,
		        slidesToScroll: 1,
		        infinite: true,
		        dots: true
		      }
		    },
		    {
		      breakpoint: 900,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    },{
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		  
  		]
	});

	$('.testimonial-wrap-2').slick({
		slidesToShow: 2,
		slidesToScroll: 2,
		infinite: true,
		dots: true,
		arrows:false,
		autoplay: true,
		autoplaySpeed: 6000,
		responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow:2,
		        slidesToScroll:2,
		        infinite: true,
		        dots: true
		      }
		    },
		    {
		      breakpoint: 900,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    },{
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		  
  		]
	});



	var map;

	function initialize() {
		var mapOptions = {
			zoom: 13,
			center: new google.maps.LatLng(50.97797382271958, -114.107718560791)
			// styles: style_array_here
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	}

	var google_map_canvas = $('#map-canvas');

	if (google_map_canvas.length) {
		google.maps.event.addDomListener(window, 'load', initialize);
	}

	// Counter

	$('.counter-stat span').counterUp({
	      delay: 10,
	      time: 1000
	  });

		
 // Shuffle js filter and masonry
    var Shuffle = window.Shuffle;
    var jQuery = window.jQuery;

    var myShuffle = new Shuffle(document.querySelector('.shuffle-wrapper'), {
        itemSelector: '.shuffle-item',
        buffer: 1
    });

    jQuery('input[name="shuffle-filter"]').on('change', function (evt) {
        var input = evt.currentTarget;
        if (input.checked) {
            myShuffle.filter(input.value);
        }
    });
})(jQuery);

	// SERVICES

function showModal(service) {
    const descriptions = {
        'Blood Donation': 'Blood donation is a voluntary process where individuals give a certain amount of their blood to help others in need. The donated blood is typically collected by blood banks or hospitals and is used for various medical purposes, such as surgeries, treating patients with blood disorders, accidents, or those undergoing cancer treatments. The donation process involves screening the donor’s health to ensure safety for both the donor and recipient. The actual donation takes around 10-15 minutes, followed by a brief recovery period. Blood donations are essential for maintaining a stable supply for emergencies and medical care, and one donation can save up to three lives.',
        'Consultation': 'Consultation refers to a meeting between a patient and a healthcare professional, such as a doctor, nurse, or specialist, to discuss the patient’s health concerns, symptoms, or medical conditions. During the consultation, the healthcare provider reviews the patients medical history, conducts physical examinations if necessary, and may order tests or procedures to diagnose or monitor health conditions. Consultations are essential for diagnosing health issues, providing medical advice, and determining appropriate treatment plans. They can take place in person or through telemedicine platforms for remote advice.',
        'Vaccination': 'Vaccination is the process of administering a vaccine to protect an individual from specific infectious diseases. Vaccines stimulate the immune system to recognize and fight pathogens like viruses and bacteria without causing the disease itself. Vaccinations are a key component of preventive healthcare and help in controlling or eradicating life-threatening diseases such as measles, polio, influenza, and COVID-19. Vaccination schedules are typically recommended for children, adults, and special populations (like the elderly) to maintain immunity over time. Some vaccines require multiple doses for full protection, and side effects are generally mild, like soreness at the injection site or a mild fever.',
        'Laboratory': 'Laboratory services in healthcare involve testing samples of blood, urine, tissues, or other substances from the body to diagnose diseases, monitor health conditions, or assess overall health. Laboratories perform a wide range of tests, including blood counts, cholesterol levels, glucose tests, and screenings for infections or chronic diseases. Laboratory tests are critical for accurate diagnosis, treatment planning, and disease prevention. Results from lab tests guide healthcare providers in making informed decisions regarding a patients treatment and care, and are often an integral part of regular checkups and health monitoring.'
    };
    document.getElementById('modal-text').innerText = descriptions[service];
    document.getElementById('modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

//IMAGE SLIDER

var slide_Index = 1;
        
        showSlide(slide_Index);
        
        
        function plusSlides(n){
        
        showSlide(slide_Index += n);
        
        }
        
        
        function currentSlide(n) {
        
        showSlide(slide_Index = n);
        
        }
        
        
        function showSlide(n){
        
        var i;
        
        var slides = document.getElementsByClassName("myslides");
        
        var dots = document.getElementsByClassName("dots");
        
        if (n > slides.length) { slide_Index = 1};
        
        if (n < 1) { slide_Index = slides.length};
        
        for (i=0;i<slides.length;i++) {
        
        slides[i].style.display = "none";
        
        };
        
        for (i=0;i<dots.length;i++) {
        
        dots[i].className = dots[i].className.replace(" active","");
        
        };
        
        slides[slide_Index-1].style.display = "block";
        
        dots[slide_Index-1].className += " active";
        
        }

//REGISTRATION

document.getElementById('registration-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent actual form submission

    // Hide the form and show the success message
    document.getElementById('registration-form').style.display = 'none';
    document.getElementById('success-message').classList.remove('hidden');
});


//Hide and show appointments

        // Get the heading and the subheadings list
        const appointmentsHeading = document.getElementById('appointments-heading');
        const appointmentsSubheadings = document.getElementById('appointments-subheadings');

        // Add an event listener for clicking the heading
        appointmentsHeading.addEventListener('click', function() {
            // Toggle the 'hidden' class on the subheadings list
            appointmentsSubheadings.classList.toggle('hidden');
        });
