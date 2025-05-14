@extends('layoutNoSidebar')

@section('content')
    @if (session()->has('Error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('Error') }}',
                confirmButtonColor: '#3085d6',
            });
        </script>
    @endif

    <style>
        /* Base styling */
        body {
            overflow-x: hidden;
            background: linear-gradient(135deg, #1C2245 0%, #30366A 100%);
        }

        /* Container and animations */
        .form-box {
            z-index: 1;
            transition: .6s ease-in-out 1.2s, visibility 0s 1s;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        }

        .container.active .form-box {
            right: 50%;
        }

        .form-box.registration {
            display: none;
        }

        .container.active .form-box.registration {
            display: flex;
        }

        .form-box.login {
            display: flex;
        }

        .container.active .form-box.login {
            display: none;
        }

        /* Toggle section styling */
        .toggle-box {
            position: relative;
            overflow: hidden;
        }
        
        .toggle-box::before {
            content: '';
            position: absolute;
            width: 300%;
            height: 100%;
            left: -250%;
            /* background: linear-gradient(135deg, #F4AB24 0%, #FFD249 100%);  */
            /* background: linear-gradient(135deg, #10b981 0%, #1ceaa5 100%);  */
            background: linear-gradient(135deg, #1C2245 0%, #30366A 100%);
            border-radius: 150px;
            z-index: 2;
            transition: 1.2s ease-in-out;
            box-shadow: 0 5px 15px rgba(116, 148, 236, 0.4);
        }

        .container.active .toggle-box::before {
            left: 50%;
        }

        .toggle-panel {
            z-index: 2;
            transition: .6s ease-in-out;
        }

        .toggle-panel.toggle-left {
            left: 0;
            transition-delay: 1s;
        }

        .container.active .toggle-panel.toggle-left {
            left: -50%;
            transition-delay: .4s;
        }

        .toggle-panel.toggle-right {
            right: -50%;
            transition-delay: .4s;
        }

        .container.active .toggle-panel.toggle-right {
            right: 0;
            transition-delay: 1s;
        }

        /* Form styling */
        .form-container {
            backdrop-filter: blur(5px); 
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-box input {
            transition: all 0.3s ease;
            background: rgba(245, 247, 250, 0.8);
            border: 2px solid transparent;
        }
        
        .input-box input:focus {
            border-color: #7494ec;
            box-shadow: 0 0 0 4px rgba(116, 148, 236, 0.15);
            background: white;
        }
        
        .input-error {
            border-color: #EF4444 !important;
            background-color: rgba(254, 226, 226, 0.5) !important;
        }
        
        .input-error::placeholder {
            color: #EF4444;
            opacity: 0.7;
        }
        
        .error-message {
            color: #EF4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
            font-weight: 500;
        }
        
        .error-message.visible {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .input-box .fa-solid.icon-hidden {
            display: none !important;
        }

        /* Buttons */
        .form-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .form-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }
        
        .form-btn:hover::before {
            left: 100%;
        }
        
        /* Toggle button styling */
        .toggle-btn {
            position: relative;
            overflow: hidden;
            z-index: 1;
            transition: all 0.3s ease;
        }
        
        .toggle-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            transition: all 0.4s ease;
            z-index: -1;
        }
        
        .toggle-btn:hover::after {
            left: 0;
        }
        
        .toggle-btn:hover {
            transform: translateY(-3px);
        }

        /* Hover effects */
        .input-box i {
            transition: all 0.3s ease;
        }
        
        .input-box:focus-within i {
            color: #7494ec !important;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-title {
            position: relative;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, #10b981, #1ceaa5);
            border-radius: 3px;
        }

        /* Responsive design */
        @media screen and (max-width: 650px) {
            .container {
                height: calc(100vh - 40px);
            }

            .container.active .toggle-box::before {
                top: 70%;
                left: 0;
            }

            .form-box {
                bottom: 0;
                width: 100%;
                height: 70%;
            }

            .container.active .form-box {
                right: 0;
                bottom: 30%;
            }

            .toggle-box::before {
                left: 0;
                width: 100%;
                height: 300%;
                top: -270%;
                border-radius: 20vw;
            }

            .toggle-panel {
                width: 100%;
                height: 30%;
            }

            .container.active .toggle-panel.toggle-left {
                left: 0;
                top: -30%;
            }

            .toggle-panel.toggle-left {
                top: 0;
            }

            .toggle-panel.toggle-right {
                right: 0;
                bottom: -30%;
            }

            .container.active .toggle-panel.toggle-right {
                bottom: 0;
            }
        }
    </style>

    <body>
        <div class="flex justify-center items-center h-screen w-full py-8 px-4">
            <div class="min-h-screen mx-auto cont relative w-full h-full flex justify-center items-center">
                <div class="container form-container relative w-full m-[20px] max-w-[900px] h-[600px] bg-white rounded-[30px] shadow-xl overflow-hidden">
                    <!-- Login Form -->
                    <div class="form-box login absolute right-0 w-[50%] h-full flex flex-col items-center justify-center text-black p-8 md:p-10">
                        <form class="w-full" id="manualLoginForm" action="{{ route('manualLogin') }}" method="POST" novalidate>
                            @csrf
                            <h1 class="form-title text-3xl md:text-4xl mb-8 text-slate-800 font-bold text-center">Sign in</h1>

                            <div class="input-box relative w-full mb-5">
                                <input id="usernameOrEmail" type="text" aria-label="Username or Email"
                                    placeholder="Username or Email" required name="usernameOrEmail"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i class="fa-solid fa-user absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <div class="input-box relative w-full mb-7">
                                <input id="loginPassword" type="password" aria-label="Password" placeholder="Password"
                                    required name="loginPassword"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i class="fa-solid fa-eye password-toggle cursor-pointer absolute right-[1rem] top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <button type="submit"
                                class="form-btn w-full bg-gradient-to-r from-[#10b981] to-[#1ceaa5] text-white font-bold py-4 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 mb-4">
                                Sign In
                            </button>
                            
                            <div class="relative text-center my-5">
                                <span class="bg-white px-4 text-gray-500 text-sm font-medium relative z-10">OR CONTINUE WITH</span>
                                <div class="absolute top-1/2 left-0 w-full h-px bg-gray-200 -z-1"></div>
                            </div>
                            
                            <button type="button" onclick="window.location.href='{{ route('auth') }}'"
                                class="form-btn w-full bg-gradient-to-r from-[#F4AB24] to-[#FFD249] text-white font-bold py-4 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 flex items-center justify-center">
                                <i class="fa-brands fa-google mr-2"></i> Petra Email Login
                            </button>
                        </form>
                    </div>

                    <!-- Registration Form -->
                    <div class="form-box registration absolute right-0 w-[50%] h-full flex flex-col items-center justify-center text-black p-8 md:p-10">
                        <form class="w-full" id="submitRegisterForm" novalidate>
                            @csrf
                            <h1 class="form-title text-3xl md:text-4xl mb-8 text-slate-800 font-bold text-center">Create Account</h1>

                            <div class="input-box relative w-full mb-4">
                                <input type="text" aria-label="Username" placeholder="Username" required id="username" name="username"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i class="fa-solid fa-user absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <div class="input-box relative w-full mb-4">
                                <input type="email" aria-label="Email" placeholder="Email" required id="email" name="email"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i class="fa-solid fa-envelope absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <div class="input-box relative w-full mb-4">
                                <input type="password" aria-label="Password" placeholder="Password" required
                                    id="password" name="password" minlength="8"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i class="fa-solid fa-eye password-toggle cursor-pointer absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <div class="input-box relative w-full mb-7">
                                <input type="password" aria-label="Confirm Password" placeholder="Confirm Password"
                                    id="confirmPassword" name="confirmPassword" minlength="8" required
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i class="fa-solid fa-eye password-toggle cursor-pointer absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <button type="submit"
                                class="form-btn w-full bg-gradient-to-r  from-[#10b981] to-[#1ceaa5] text-white font-bold py-4 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                                Create Account
                            </button>
                        </form>
                    </div>

                    <!-- Toggle Box -->
                    <div class="toggle-box absolute w-full h-full">
                        <div class="toggle-panel toggle-left left-0 absolute w-[50%] h-[100%] flex flex-col justify-center items-center text-center text-white p-8">
                            <div class="mb-6 float-animation">
                                <img src="{{ asset('assets/p2p logo - white.svg') }}" alt="Peer-to-Peer Logo" class="h-20 hidden md:flex">
                            </div>
                            <h1 class="text-3xl lg:text-4xl font-extrabold mb-2">Hello, Informate!</h1>
                            <p class="mb-8 mt-2 text-[#e4e9fd] text-base lg:text-lg opacity-90">New to <span class="font-bold cal-sans-regular text-[#fff]">peer <span class="text-[#FFD249]">- to - </span> peer?</span><br>Join and explore our community today!</p>
                            <button class="toggle-btn register-btn w-[180px] h-[54px] bg-transparent underline rounded-xl hover:text-[#FFD249] transition duration-300 font-bold text-lg">Get Started</button>
                        </div>
                        <div class="toggle-panel toggle-right right-[-50%] absolute w-[50%] h-[100%] flex flex-col justify-center items-center text-center text-white p-8">
                            <div class="mb-6 float-animation">
                                <img src="{{ asset('assets/p2p logo - white.svg') }}" alt="Peer-to-Peer Logo" class="h-20 hidden md:flex">
                            </div>
                            <h1 class="text-3xl lg:text-4xl font-extrabold mb-2">Welcome Back!</h1>
                            <p class="mb-8 mt-2 text-[#e4e9fd] text-base lg:text-lg opacity-90">Already part of our community? Sign in to continue your learning journey.</p>
                            <button class="toggle-btn login-btn w-[180px] h-[54px] bg-transparent underline rounded-xl hover:text-[#FFD249] transition duration-300 font-bold text-lg">Sign In</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const container = document.querySelector('.container');
            const registerBtn = document.querySelector('.register-btn');
            const loginBtn = document.querySelector('.login-btn');

            registerBtn.addEventListener('click', () => {
                clearAllFormErrors();
                container.classList.add('active');
                // Add animation class
                document.querySelector('.form-box.registration').classList.add('animate-fadeIn');
            });

            loginBtn.addEventListener('click', () => {
                clearAllFormErrors();
                container.classList.remove('active');
                // Add animation class
                document.querySelector('.form-box.login').classList.add('animate-fadeIn');
            });

            // --- Input Validation ---
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function showError(inputElement, message) {
                const inputBox = inputElement.closest('.input-box');
                const errorElement = inputBox.querySelector('.error-message');
                const iconElement = inputBox.querySelector('i.fa-solid'); // Get the icon

                inputElement.classList.add('input-error');
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.add('visible');
                }
                if (iconElement) { // If icon exists, hide it
                    iconElement.classList.add('icon-hidden');
                }
            }

            function clearError(inputElement) {
                const inputBox = inputElement.closest('.input-box');
                const errorElement = inputBox.querySelector('.error-message');
                const iconElement = inputBox.querySelector('i.fa-solid'); // Get the icon

                inputElement.classList.remove('input-error');
                if (errorElement) {
                    errorElement.classList.remove('visible');
                    errorElement.textContent = '';
                }
                if (iconElement) { // If icon exists, show it
                    iconElement.classList.remove('icon-hidden');
                }
            }

            function clearAllFormErrors() {
                document.querySelectorAll('.input-box input').forEach(input => clearError(input));
            }

            // Password strength indicator
            const passwordStrengthIndicator = document.createElement('div');
            passwordStrengthIndicator.className = 'w-full h-1 mt-1 rounded-full overflow-hidden hidden';
            
            if (document.getElementById('password')) {
                const passwordInput = document.getElementById('password');
                const passwordInputBox = passwordInput.closest('.input-box');
                passwordInputBox.appendChild(passwordStrengthIndicator);
                
                passwordInput.addEventListener('input', function() {
                    if (this.value) {
                        passwordStrengthIndicator.classList.remove('hidden');
                        
                        // Simple password strength calculation
                        let strength = 0;
                        if (this.value.length >= 8) strength += 1;
                        if (/[A-Z]/.test(this.value)) strength += 1;
                        if (/[0-9]/.test(this.value)) strength += 1;
                        if (/[^A-Za-z0-9]/.test(this.value)) strength += 1;
                        
                        let strengthClass = '';
                        if (strength === 1) strengthClass = 'bg-red-500';
                        else if (strength === 2) strengthClass = 'bg-yellow-500';
                        else if (strength === 3) strengthClass = 'bg-blue-500';
                        else if (strength === 4) strengthClass = 'bg-green-500';
                        
                        passwordStrengthIndicator.className = `w-full h-1 mt-1 rounded-full overflow-hidden ${strengthClass}`;
                    } else {
                        passwordStrengthIndicator.classList.add('hidden');
                    }
                });
            }

            // --- Login Form Validation ---
            const manualLoginForm = document.getElementById('manualLoginForm');
            const usernameOrEmailInput = document.getElementById('usernameOrEmail');
            const loginPasswordInput = document.getElementById('loginPassword');

            if (manualLoginForm) {
                manualLoginForm.addEventListener('submit', function(event) {
                    let isValid = true;
                    // Clear only errors related to this form before validating
                    manualLoginForm.querySelectorAll('.input-box input').forEach(input => clearError(input));

                    // Validate Username/Email
                    if (!usernameOrEmailInput.value.trim()) {
                        showError(usernameOrEmailInput, 'Username or Email is required.');
                        isValid = false;
                    } else {
                        clearError(usernameOrEmailInput); // Ensure icon is back if valid
                    }

                    // Validate Password
                    if (!loginPasswordInput.value.trim()) {
                        showError(loginPasswordInput, 'Password is required.');
                        isValid = false;
                    } else {
                        clearError(loginPasswordInput); // Ensure icon is back if valid
                    }

                    if (!isValid) {
                        event.preventDefault(); // Prevent form submission
                        // Shake animation for invalid form
                        manualLoginForm.classList.add('animate-shake');
                        setTimeout(() => {
                            manualLoginForm.classList.remove('animate-shake');
                        }, 500);
                    } else {
                        Swal.fire({
                            title: "Logging in...",
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                    }
                });
            }

            // --- Registration Form Validation & Submission ---
            const submitRegisterForm = document.getElementById('submitRegisterForm');
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');

            if (submitRegisterForm) {
                submitRegisterForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    let isValid = true;
                     // Clear only errors related to this form before validating
                    submitRegisterForm.querySelectorAll('.input-box input').forEach(input => clearError(input));

                    // Validate Username
                    if (!usernameInput.value.trim()) {
                        showError(usernameInput, 'Username is required.');
                        isValid = false;
                    } else {
                        clearError(usernameInput);
                    }

                    // Validate Email
                    if (!emailInput.value.trim()) {
                        showError(emailInput, 'Email is required.');
                        isValid = false;
                    } else if (!emailRegex.test(emailInput.value.trim())) {
                        showError(emailInput, 'Please enter a valid email address.');
                        isValid = false;
                    } else {
                        clearError(emailInput);
                    }

                    // Validate Password
                    if (!passwordInput.value) {
                        showError(passwordInput, 'Password is required.');
                        isValid = false;
                    } else if (passwordInput.value.length < 8) {
                        showError(passwordInput, 'Password must be at least 8 characters long.');
                        isValid = false;
                    } else {
                        clearError(passwordInput);
                    }

                    // Validate Confirm Password
                    if (!confirmPasswordInput.value) {
                        showError(confirmPasswordInput, 'Please confirm your password.');
                        isValid = false;
                    } else if (passwordInput.value && confirmPasswordInput.value !== passwordInput.value) {
                        showError(confirmPasswordInput, 'Passwords do not match.');
                        isValid = false;
                    } else {
                        clearError(confirmPasswordInput);
                    }

                    if (!isValid) {
                        // Shake animation for invalid form
                        submitRegisterForm.classList.add('animate-shake');
                        setTimeout(() => {
                            submitRegisterForm.classList.remove('animate-shake');
                        }, 500);
                        return; // Stop if validation fails
                    }

                    Swal.fire({
                        title: "Creating your account...",
                        text: "Just a moment while we set things up for you!",
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    try {
                        const username = usernameInput.value.trim();
                        const email = emailInput.value.trim();
                        const password = passwordInput.value;

                        const response = await fetch("{{ route('submitRegister') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ username, email, password }),
                        });

                        const data = await response.json();
                        Swal.close();

                        if (response.ok && data.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Account Created!',
                                text: data.message || 'Your account has been created successfully!',
                                confirmButtonColor: '#3085d6',
                            }).then(() => {
                                container.classList.remove('active');
                                submitRegisterForm.reset();
                                submitRegisterForm.querySelectorAll('.input-box input').forEach(input => clearError(input)); // Ensure all visual states are cleared
                            });
                        } else {
                            let errorMessage = data.message || 'Registration failed.';
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('\n');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Error',
                                text: errorMessage,
                                confirmButtonColor: '#d33',
                            });
                        }
                    } catch (error) {
                        Swal.close();
                        console.error('Registration Fetch Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'An unexpected error occurred. Please try again.',
                            confirmButtonColor: '#d33',
                        });
                    }
                });
            }

            // Add live validation on blur and clear on input
            document.querySelectorAll('.input-box input').forEach(input => {
                // Add focus effect
                input.addEventListener('focus', function() {
                    this.closest('.input-box').classList.add('focus');
                });
                
                input.addEventListener('blur', function() {
                    this.closest('.input-box').classList.remove('focus');
                    
                    const currentForm = this.closest('form');
                    if (!currentForm) return;

                    if (this.id === 'usernameOrEmail') {
                        if (!this.value.trim()) showError(this, 'Username or Email is required.');
                        else clearError(this);
                    } else if (this.id === 'loginPassword') {
                         if (!this.value.trim()) showError(this, 'Password is required.');
                         else clearError(this);
                    } else if (this.id === 'username') {
                         if (!this.value.trim()) showError(this, 'Username is required.'); else clearError(this);
                    } else if (this.id === 'email') {
                        if (!this.value.trim()) showError(this, 'Email is required.');
                        else if (!emailRegex.test(this.value.trim())) showError(this, 'Invalid email format.');
                        else clearError(this);
                    } else if (this.id === 'password') {
                        if (!this.value) showError(this, 'Password is required.');
                        else if (this.value.length < 8) showError(this, 'Password must be at least 8 characters.');
                        else clearError(this);
                         // Re-validate confirm password if password changes
                        if (confirmPasswordInput.value && this.value !== confirmPasswordInput.value) {
                            showError(confirmPasswordInput, 'Passwords do not match.');
                        } else if (confirmPasswordInput.value) {
                            clearError(confirmPasswordInput);
                        }
                    } else if (this.id === 'confirmPassword') {
                        if (!this.value) showError(this, 'Please confirm password.');
                        else if (passwordInput.value && this.value !== passwordInput.value) showError(this, 'Passwords do not match.');
                        else clearError(this);
                    }
                });
                
                input.addEventListener('input', function() {
                    // Only clear the error for the current input, don't affect others.
                    // The blur event will handle more comprehensive validation if needed.
                    if (this.classList.contains('input-error')) {
                        clearError(this);
                    }
                });
            });

            // Add animations
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.container').classList.add('animate-fadeIn');
            });

            // Define animations if not already defined by Tailwind
            if (!document.querySelector('style.animation-styles')) {
                const styleSheet = document.createElement('style');
                styleSheet.className = 'animation-styles';
                styleSheet.textContent = `
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(10px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    @keyframes shake {
                        0%, 100% { transform: translateX(0); }
                        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                        20%, 40%, 60%, 80% { transform: translateX(5px); }
                    }
                    .animate-fadeIn {
                        animation: fadeIn 0.5s ease forwards;
                    }
                    .animate-shake {
                        animation: shake 0.5s ease-in-out;
                    }
                `;
                document.head.appendChild(styleSheet);
            }
        </script>
        <script>
    // Set up password toggle functionality
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            
            // Toggle password visibility
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>
    </body>
@endsection     