<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta
      name="description"
      content="Orbitor,business,company,agency,modern,bootstrap4,tech,software"
    />
    <meta name="author" content="themefisher.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Login</title>

    <!-- Favicon -->
    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="/images/favicon.ico"
    />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css" />

    <!-- Icon Font Css -->
    <link rel="stylesheet" href="plugins/icofont/icofont.min.css" />

    <!-- Slick Slider CSS -->
    <link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css" />
    <link
      rel="stylesheet"
      href="plugins/slick-carousel/slick/slick-theme.css"
    />

    <!-- App Icons -->
    <link
      rel="icon"
      href="../images/img/rhu-logo.png"
      type="image/x-icon"
    />

    <!-- Main Stylesheets -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/register.css" />
    <link rel="stylesheet" href="css/output.css" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tesseract (if needed) -->
    <script src="https://unpkg.com/tesseract.js@v2.1.0/dist/tesseract.min.js"></script>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- React (if needed) -->
    <script src="https://unpkg.com/react@17/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js"></script>
    <!-- Lucide Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.js"></script>

    <style>
      /* 
        Minimal custom CSS needed:
        - Control the eye icon for toggling password.
        - If you want additional custom breakpoints or overrides.
      */

      .password-container {
        position: relative;
      }
      .toggle-password {
        position: absolute;
        top: 70%;
        right: 1rem;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.25rem; /* Adjust icon size */
      }

      /* If you want to keep any existing .login-input styles from your CSS files, 
         be sure they don't conflict with these utility classes. */
      .login-input {
        /* You can keep or adjust these as needed */
      }
    </style>
  </head>

  <body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <!-- 
      Wrap your form in a container that allows it to be centered
      and scaled on different screen sizes. 
    -->
    <div class="w-full max-w-md p-6 bg-white shadow-md rounded-md">
      <!-- The login form -->
      <form id="login-form" class="login-form" method="POST" action="/login">
        @csrf

        <!-- Title/Logos -->
        <h1 class="flex items-center justify-center mb-6">
          <img
            src="images/img/Lucban Logo.jpg"
            alt="Logo Left"
            class="h-12 w-12 mr-4"
          />
          <span class="text-2xl font-bold">LOGIN</span>
          <img
            src="bootstrap-template/assets/img/rhu-logo.png"
            alt="Logo Right"
            class="h-12 w-12 ml-4"
          />
        </h1>

        <!-- Username -->
        <div class="mb-4">
          <label class="block font-medium mb-1" for="username"
            >Username:</label
          >
          <input
            type="text"
            name="username"
            id="username"
            placeholder="Enter your username"
            required
            class="login-input form-control @error('username') is-invalid @enderror w-full"
          />
          @error('username')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Password -->
        <div class="password-container mb-2">
          <label class="block font-medium mb-1" for="password"
            >Password:</label
          >
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter your password"
            required
            class="login-input form-control @error('password') is-invalid @enderror w-full"
          />
          <i
            class="icofont-eye toggle-password"
            id="toggle-password"
            data-target="password"
          ></i>
          @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Forgot Password link -->
        <p class="text-right text-sm mb-4">
          <a href="/forgot-password" class="text-blue-600 hover:underline"
            >Forgot Password?</a
          >
        </p>

        <!-- Login Button -->
        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md mb-4"
        >
          LOGIN
        </button>

        <!-- Sign Up Link -->
        <p class="text-center text-sm">
          Don't have an account?
          <a href="/register" class="text-blue-600 hover:underline"
            >Sign Up</a
          >
        </p>
      </form>
    </div>

    <!-- Alert if login fails -->
    @if(session('status'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Access Denied',
        text: '{{ session('status') }}',
      });
    </script>
    @endif

    <!-- Toggle password visibility script -->
    <script>
      document.querySelectorAll('.toggle-password').forEach((eyeIcon) => {
        eyeIcon.addEventListener('click', function () {
          const targetId = this.getAttribute('data-target');
          const targetInput = document.getElementById(targetId);

          if (targetInput.type === 'password') {
            targetInput.type = 'text';
            this.classList.remove('icofont-eye');
            this.classList.add('icofont-eye-blocked');
          } else {
            targetInput.type = 'password';
            this.classList.remove('icofont-eye-blocked');
            this.classList.add('icofont-eye');
          }
        });
      });
    </script>

    <!-- Additional Scripts -->
    <script src="js/Registration.js"></script>
    <script src="plugins/jquery/jquery.js"></script>
    <script src="plugins/bootstrap/js/popper.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/counterup/jquery.easing.js"></script>
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <script src="plugins/counterup/jquery.waypoints.min.js"></script>
    <script src="plugins/shuffle/shuffle.min.js"></script>
    <script src="plugins/counterup/jquery.counterup.min.js"></script>
    <script src="js/script.js"></script>
  </body>
</html>
