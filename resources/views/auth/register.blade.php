<!DOCTYPE html>
<html lang="en" class="antialiased h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | RHU Lucban</title>
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />

    <!-- Main Stylesheet (Tailwind CSS via Vite) -->
    @vite('resources/css/app.css')

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        /* Custom scrollbar for a polished look */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans text-slate-800 bg-slate-100">

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('/images/img/rhu1.png');">

    <!-- Dark overlay to ensure the card stands out against the background image -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden relative z-10" x-data="registerForm()">

        <!-- Header -->
        <div class="bg-blue-600 px-6 py-8 sm:p-10 text-center">
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Create your account</h2>
            <p class="mt-2 text-blue-100">Join the Rural Health Unit of Lucban to easily manage your health appointments and records.</p>
        </div>

        <!-- Server-side errors -->
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-6 mt-6 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form action="/register" method="POST" enctype="multipart/form-data" class="px-6 py-8 sm:p-10 space-y-10" @submit="submit($event)">
            @csrf

            <!-- Section 1: Personal Information -->
            <div>
                <h3 class="text-lg leading-6 font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-6">

                    <div class="col-span-1">
                        <label for="f_name" class="block text-sm font-medium text-slate-700">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="f_name" id="f_name" required value="{{ old('f_name') }}"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                               placeholder="e.g. Juan">
                    </div>

                    <div class="col-span-1">
                        <label for="m_name" class="block text-sm font-medium text-slate-700">Middle Initial</label>
                        <input type="text" name="m_name" id="m_name" value="{{ old('m_name') }}"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                               placeholder="e.g. D.">
                    </div>

                    <div class="col-span-1">
                        <label for="l_name" class="block text-sm font-medium text-slate-700">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="l_name" id="l_name" required value="{{ old('l_name') }}"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                               placeholder="e.g. Dela Cruz">
                    </div>

                    <div class="col-span-1">
                        <label for="suffix" class="block text-sm font-medium text-slate-700">Suffix (Optional)</label>
                        <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                               placeholder="e.g. Jr., Sr., III">
                    </div>

                    <div class="col-span-1">
                        <label for="bday" class="block text-sm font-medium text-slate-700">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="bday" id="bday" required value="{{ old('bday') }}"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Gender <span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="gender" value="male" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-slate-300" required>
                                <span class="ml-2 text-sm text-slate-700">Male</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="gender" value="female" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-slate-300">
                                <span class="ml-2 text-sm text-slate-700">Female</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="gender" value="prefer-not-say" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-slate-300">
                                <span class="ml-2 text-sm text-slate-700">Prefer not to say</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="contactNo" class="block text-sm font-medium text-slate-700">Mobile Number <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="tel" name="contactNo" id="contactNo" required x-model="contactNo"
                                   @input="validateMobile"
                                   :class="{'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': mobileError, 'border-slate-300 focus:border-blue-500 focus:ring-blue-500': !mobileError}"
                                   class="block w-full rounded-md sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                                   placeholder="09123456789">

                            <!-- Error Icon -->
                            <div x-show="mobileError" x-cloak class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <p x-show="mobileError" x-cloak class="mt-2 text-sm text-red-600" id="mobile-error" x-text="mobileError"></p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Residential Information -->
            <div>
                <h3 class="text-lg leading-6 font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-4">Residential Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-6">

                    <div class="col-span-1">
                        <label for="street" class="block text-sm font-medium text-slate-700">Street/Sitio Address <span class="text-red-500">*</span></label>
                        <input type="text" name="street" id="street" required value="{{ old('street') }}"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                               placeholder="Street name, house number">
                    </div>

                    <div class="col-span-1">
                        <label for="brgy" class="block text-sm font-medium text-slate-700">Barangay <span class="text-red-500">*</span></label>
                        <select name="brgy" id="brgy" required
                                class="mt-1 block w-full rounded-md border-slate-300 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out">
                            <option value="">Select your barangay</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="Barangay {{ $i }}" {{ old('brgy') == 'Barangay '.$i ? 'selected' : '' }}>Barangay {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-span-1">
                        <label for="zip_code" class="block text-sm font-medium text-slate-700">Zip Code</label>
                        <input type="text" name="zip_code" id="zip_code" value="4328" readonly
                               class="mt-1 block w-full rounded-md border-slate-200 bg-slate-50 text-slate-500 shadow-sm sm:text-sm px-4 py-2 border cursor-not-allowed">
                    </div>

                    <div class="col-span-1">
                        <label for="municipality" class="block text-sm font-medium text-slate-700">Municipality</label>
                        <input type="text" name="municipality" id="municipality" value="Lucban" readonly
                               class="mt-1 block w-full rounded-md border-slate-200 bg-slate-50 text-slate-500 shadow-sm sm:text-sm px-4 py-2 border cursor-not-allowed">
                    </div>

                    <div class="col-span-1">
                        <label for="province" class="block text-sm font-medium text-slate-700">Province</label>
                        <input type="text" name="province" id="province" value="Quezon" readonly
                               class="mt-1 block w-full rounded-md border-slate-200 bg-slate-50 text-slate-500 shadow-sm sm:text-sm px-4 py-2 border cursor-not-allowed">
                    </div>

                    <div class="col-span-1">
                        <label for="upload_id" class="block text-sm font-medium text-slate-700">Upload Residential ID <span class="text-red-500">*</span></label>
                        <input type="file" name="upload_id" id="upload_id" required accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150 border border-slate-300 rounded-md">
                        <p class="mt-1 text-xs text-slate-500">Max size: 5MB (JPG, PNG, PDF)</p>
                    </div>

                </div>
            </div>

            <!-- Section 3: Account Information -->
            <div>
                <h3 class="text-lg leading-6 font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-4">Account Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-6">

                    <div class="col-span-1">
                        <label for="username" class="block text-sm font-medium text-slate-700">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" required value="{{ old('username') }}"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                               placeholder="Choose a username">
                    </div>

                    <div class="col-span-1">
                        <label for="email" class="block text-sm font-medium text-slate-700">Email Address <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="email" name="email" id="email" required x-model="email"
                                   @input="validateEmail" @blur="validateEmail"
                                   :class="{'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': emailError, 'border-slate-300 focus:border-blue-500 focus:ring-blue-500': !emailError}"
                                   class="block w-full rounded-md sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                                   placeholder="you@gmail.com">

                            <!-- Success/Error Icon -->
                            <div x-show="email.length > 0" x-cloak class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg x-show="!emailError" class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg x-show="emailError" class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <p x-show="emailError" x-cloak class="mt-2 text-sm text-red-600" x-text="emailError"></p>
                    </div>

                    <div class="col-span-1">
                        <label for="password" class="block text-sm font-medium text-slate-700">Password <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                                   x-model="password" @input="calculatePasswordStrength"
                                   class="block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                                   placeholder="Minimum 8 characters">

                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <!-- Eye off (hidden) -->
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.41-3.41" />
                                </svg>
                                <!-- Eye on (shown) -->
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="mt-2" x-show="password.length > 0" x-cloak>
                            <div class="flex items-center space-x-1">
                                <div class="h-1 flex-1 rounded transition-colors duration-300" :class="passwordStrength >= 1 ? strengthColor : 'bg-slate-200'"></div>
                                <div class="h-1 flex-1 rounded transition-colors duration-300" :class="passwordStrength >= 2 ? strengthColor : 'bg-slate-200'"></div>
                                <div class="h-1 flex-1 rounded transition-colors duration-300" :class="passwordStrength >= 3 ? strengthColor : 'bg-slate-200'"></div>
                                <div class="h-1 flex-1 rounded transition-colors duration-300" :class="passwordStrength >= 4 ? strengthColor : 'bg-slate-200'"></div>
                            </div>
                            <p class="text-xs mt-1 font-medium" :class="strengthTextColor" x-text="strengthText"></p>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                                   x-model="passwordConfirm" @input="validatePasswordMatch"
                                   :class="{'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500': passwordMatchError, 'border-slate-300 focus:border-blue-500 focus:ring-blue-500': !passwordMatchError}"
                                   class="block w-full rounded-md sm:text-sm px-4 py-2 border transition duration-150 ease-in-out"
                                   placeholder="Confirm your password">

                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <svg x-show="!showPasswordConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.41-3.41" />
                                </svg>
                                <svg x-show="showPasswordConfirm" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p x-show="passwordMatchError" x-cloak class="mt-2 text-sm text-red-600" x-text="passwordMatchError"></p>
                    </div>

                </div>
            </div>

            <!-- Submit & Links -->
            <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row-reverse sm:items-center sm:justify-between">

                <button type="submit"
                        :disabled="isSubmitting || hasErrors()"
                        :class="{'opacity-50 cursor-not-allowed': isSubmitting || hasErrors()}"
                        class="w-full sm:w-auto flex justify-center py-3 px-8 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">

                    <span x-show="!isSubmitting">Register</span>

                    <span x-show="isSubmitting" x-cloak class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Registering...
                    </span>
                </button>

                <p class="mt-6 sm:mt-0 text-center sm:text-left text-sm text-slate-600">
                    Already have an account?
                    <a href="/login" class="font-medium text-blue-600 hover:text-blue-500 hover:underline transition-colors">Log in here</a>
                </p>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('registerForm', () => ({
            email: @js(old('email', '')),
            emailError: '',

            contactNo: @js(old('contactNo', '')),
            mobileError: '',

            password: '',
            showPassword: false,
            passwordStrength: 0,
            strengthColor: 'bg-slate-200',
            strengthText: '',
            strengthTextColor: 'text-slate-500',

            passwordConfirm: '',
            showPasswordConfirm: false,
            passwordMatchError: '',

            isSubmitting: false,

            validateEmail() {
                if (this.email.length === 0) {
                    this.emailError = '';
                    return;
                }

                const domainMatch = this.email.match(/@(.+)$/);
                if (!domainMatch) {
                    this.emailError = 'Please enter a valid email address.';
                } else if (domainMatch[1].toLowerCase() !== 'gmail.com') {
                    this.emailError = 'Please use a valid @gmail.com address.';
                } else {
                    this.emailError = '';
                }
            },

            validateMobile() {
                if (this.contactNo.length === 0) {
                    this.mobileError = '';
                    return;
                }

                // Allow only numeric input visually
                this.contactNo = this.contactNo.replace(/[^0-9]/g, '');

                if (this.contactNo.length > 0 && !this.contactNo.startsWith('09')) {
                    this.mobileError = 'Mobile number must start with 09.';
                } else if (this.contactNo.length > 0 && this.contactNo.length !== 11) {
                    this.mobileError = 'Mobile number must be exactly 11 digits.';
                } else {
                    this.mobileError = '';
                }
            },

            calculatePasswordStrength() {
                this.validatePasswordMatch();

                if (this.password.length === 0) {
                    this.passwordStrength = 0;
                    this.strengthText = '';
                    return;
                }

                let strength = 0;

                if (this.password.length >= 8) strength += 1;
                if (this.password.match(/[a-z]/) && this.password.match(/[A-Z]/)) strength += 1;
                if (this.password.match(/\d/)) strength += 1;
                if (this.password.match(/[^a-zA-Z\d]/)) strength += 1;

                this.passwordStrength = strength;

                switch(strength) {
                    case 0:
                    case 1:
                        this.strengthColor = 'bg-red-500';
                        this.strengthText = 'Weak';
                        this.strengthTextColor = 'text-red-500';
                        break;
                    case 2:
                        this.strengthColor = 'bg-yellow-500';
                        this.strengthText = 'Fair';
                        this.strengthTextColor = 'text-yellow-600';
                        break;
                    case 3:
                        this.strengthColor = 'bg-blue-500';
                        this.strengthText = 'Good';
                        this.strengthTextColor = 'text-blue-600';
                        break;
                    case 4:
                        this.strengthColor = 'bg-green-500';
                        this.strengthText = 'Strong';
                        this.strengthTextColor = 'text-green-600';
                        break;
                }
            },

            validatePasswordMatch() {
                if (this.passwordConfirm.length > 0 && this.password !== this.passwordConfirm) {
                    this.passwordMatchError = 'Passwords do not match.';
                } else {
                    this.passwordMatchError = '';
                }
            },

            hasErrors() {
                return this.emailError !== '' ||
                       this.mobileError !== '' ||
                       this.passwordMatchError !== '' ||
                       (this.password.length > 0 && this.password.length < 8);
            },

            submit(event) {
                this.validateEmail();
                this.validateMobile();
                this.validatePasswordMatch();

                if (this.hasErrors()) {
                    event.preventDefault();

                    // Focus first field with error
                    if (this.emailError) document.getElementById('email').focus();
                    else if (this.mobileError) document.getElementById('contactNo').focus();
                    else if (this.passwordMatchError) document.getElementById('password_confirmation').focus();

                    return;
                }

                this.isSubmitting = true;
            }
        }));
    });
</script>

</body>
</html>
