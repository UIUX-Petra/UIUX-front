@extends('layoutNoSidebar')

@section('content')
    @if (session()->has('Error'))
        <script>
            Toastify({
                text: "{{ session('Error') }}" || "An unexpected error occurred from the server.",
                duration: 3000,
                style: {
                    background: "#e74c3c"
                }
            }).showToast();
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
            background: linear-gradient(135deg, var(--bg-c) 0%, var(--bg-primary) 100%);
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
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        #passwordRequirements li {
            display: flex;
            align-items: center;
        }

        #passwordRequirements i {
            width: 16px;
        }

        /* Add these to your existing animation styles */
        @keyframes fadeOutTooltip {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        .tooltip-is-hiding {
            animation: fadeOutTooltip 0.3s ease-out forwards;
        }

        @keyframes fadeInTooltip {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tooltip-is-showing {
            animation: fadeInTooltip 0.3s ease-out;
        }
    </style>

    <body>
        <div class="flex justify-center items-center h-screen w-full py-8 px-4">
            <div class="min-h-screen mx-auto cont relative w-full h-full flex justify-center items-center">
                <div
                    class="container form-container relative w-full m-[20px] max-w-[900px] h-[600px] bg-white rounded-[30px] shadow-xl overflow-hidden">
                    <!-- Login Form -->
                    <div
                        class="form-box login absolute right-0 w-[50%] h-full flex flex-col items-center justify-center text-black p-8 md:p-10">
                        <form class="w-full" id="manualLoginForm" action="{{ route('manualLogin') }}" method="POST" novalidate>
                            @csrf
                            <h1 class="form-title text-3xl md:text-4xl mb-8 text-slate-800 font-bold text-center">Login
                            </h1>

                            <div class="input-box relative w-full mb-5">
                                <input id="usernameOrEmail" type="text" aria-label="Username or Email"
                                    placeholder="Username or Email" required name="usernameOrEmail"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i
                                    class="fa-solid fa-user absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <div class="input-box relative w-full mb-7">
                                <input id="loginPassword" type="password" aria-label="Password" placeholder="Password"
                                    required name="loginPassword"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i
                                    class="fa-solid fa-eye password-toggle cursor-pointer absolute right-[1rem] top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <button type="submit"
                                class="form-btn w-full bg-gradient-to-r from-[#10b981] to-[#1ceaa5] text-white font-bold py-4 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 mb-4">
                                Login
                            </button>

                            <div class="relative text-center my-5">
                                <span class="bg-white px-4 text-gray-500 text-sm font-medium relative z-10">OR CONTINUE
                                    WITH</span>
                                <div class="absolute top-1/2 left-0 w-full h-px bg-gray-200 -z-1"></div>
                            </div>

                            <button type="button" onclick="window.location.href='{{ route('auth') }}'"
                                class="form-btn w-full bg-gradient-to-r from-[#F4AB24] to-[#FFD249] text-white font-bold py-4 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-300 ease-in-out transform hover:-translate-y-1 flex items-center justify-center">
                                <span
                                    class="inline-flex items-center justify-center p-1.5 bg-white rounded-full shadow-md align-middle mr-3">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="-3 0 262 262"
                                        preserveAspectRatio="xMidYMid" fill="currentColor">
                                        <path
                                            d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"
                                            fill="#4285F4" />
                                        <path
                                            d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"
                                            fill="#34A853" />
                                        <path
                                            d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"
                                            fill="#FBBC05" />
                                        <path
                                            d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"
                                            fill="#EB4335" />
                                    </svg>
                                </span> Petra Email
                            </button>
                        </form>
                    </div>

                    <!-- Registration Form -->
                    <div
                        class="form-box registration absolute right-0 w-[50%] h-full flex flex-col items-center justify-center text-black p-8 md:p-10">
                        <form class="w-full" id="submitRegisterForm" novalidate>
                            @csrf
                            <h1 class="form-title text-3xl md:text-4xl mb-8 text-slate-800 font-bold text-center">Create
                                Account</h1>

                            <div class="input-box relative w-full mb-4">
                                <input type="text" aria-label="Username" placeholder="Username" required id="username"
                                    name="username"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i
                                    class="fa-solid fa-user absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <div class="input-box relative w-full mb-4">
                                <input type="email" aria-label="Email" placeholder="Email" required id="email"
                                    name="email"
                                    class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                <i
                                    class="fa-solid fa-envelope absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                <small class="error-message"></small>
                            </div>

                            <div class="input-box relative w-full mb-4">
                                <div class="relative">
                                    <input type="password" aria-label="Password" placeholder="Password" required
                                        id="password" name="password" minlength="8"
                                        class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                    <i
                                        class="fa-solid fa-eye password-toggle cursor-pointer absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                </div>
                                <small class="error-message"></small>

                                {{-- Tooltip --}}
                                <div id="passwordStrengthContainer"
                                    class="absolute top-full left-0 right-0 mt-1 p-3 bg-white border border-gray-300 rounded-md shadow-lg z-20 hidden">

                                    <div id="passwordStrengthBar"
                                        class="w-full h-2 bg-gray-200 rounded-full overflow-hidden mb-1">
                                        <div id="passwordStrengthFill"
                                            class="h-full transition-all duration-300 ease-in-out"></div>
                                    </div>
                                    <p id="passwordStrengthText" class="text-xs font-medium text-gray-500 mb-1 text-right">
                                    </p>
                                    <ul id="passwordRequirements" class="text-xs text-gray-500 space-y-0.5">
                                        <li data-requirement="length"><i class="fas fa-times text-red-500 mx-1"></i> At
                                            least 8 characters</li>
                                        <li data-requirement="uppercase"><i class="fas fa-times text-red-500 mx-1"></i> An
                                            uppercase letter</li>
                                        <li data-requirement="lowercase"><i class="fas fa-times text-red-500 mx-1"></i> A
                                            lowercase letter</li>
                                        <li data-requirement="number"><i class="fas fa-times text-red-500 mx-1"></i> A
                                            number</li>
                                        <li data-requirement="special"><i class="fas fa-times text-red-500 mx-1"></i> A
                                            special character (e.g., !@#$%^&*)</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="input-box relative w-full mb-7">
                                <div class="relative">
                                    <input type="password" aria-label="Confirm Password" placeholder="Confirm Password"
                                        id="confirmPassword" name="confirmPassword" minlength="8" required
                                        class="w-full pr-[50px] pl-5 py-4 rounded-[12px] border-2 outline-none text-[16px] placeholder-gray-400 focus:bg-white">
                                    <i
                                        class="fa-solid fa-eye password-toggle cursor-pointer absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                                </div>
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
                        <div
                            class="toggle-panel toggle-left left-0 absolute w-[50%] h-[100%] flex flex-col justify-center items-center text-center text-white p-8">
                            <div class="mb-6 float-animation">
                                <img src="{{ asset('assets/p2p logo - white.svg') }}" alt="Peer-to-Peer Logo"
                                    class="h-20 hidden md:flex">
                            </div>
                            <h1 class="text-3xl lg:text-4xl font-extrabold mb-2">Hello, Informate!</h1>
                            <p class="mb-8 mt-2 text-[#e4e9fd] text-base lg:text-lg opacity-90">New to <span
                                    class="font-bold cal-sans-regular text-[#fff]">peer <span class="text-[#FFD249]">- to
                                        -
                                    </span> peer?</span><br>Join and explore our community today!</p>
                            {{-- <button
                                class="toggle-btn register-btn w-[180px] h-[54px] bg-transparent underline rounded-xl hover:text-[#FFD249] transition duration-300 font-bold text-xl">Make an account</button> --}}
                        </div>
                        <div
                            class="toggle-panel toggle-right right-[-50%] absolute w-[50%] h-[100%] flex flex-col justify-center items-center text-center text-white p-8">
                            <div class="mb-6 float-animation">
                                <img src="{{ asset('assets/p2p logo - white.svg') }}" alt="Peer-to-Peer Logo"
                                    class="h-20 hidden md:flex">
                            </div>
                            <h1 class="text-3xl lg:text-4xl font-extrabold mb-2">Already have an account?</h1>
                            <p class="mb-8 mt-2 text-[#e4e9fd] text-base lg:text-lg opacity-90">Already part of our
                                community? Login to continue your learning journey.</p>
                            <button
                                class="toggle-btn login-btn w-[180px] h-[54px] bg-transparent underline rounded-xl hover:text-[#FFD249] transition duration-300 font-bold text-lg">Sign
                                In</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const container = document.querySelector('.container');
            const registerBtn = document.querySelector('.register-btn');
            const loginBtn = document.querySelector('.login-btn');

            if (registerBtn) {
                registerBtn.addEventListener('click', () => {
                    clearAllFormErrors();
                    container.classList.add('active');
                    const registrationFormBox = document.querySelector('.form-box.registration');
                    if (registrationFormBox) {
                        registrationFormBox.classList.add('animate-fadeIn');
                    }
                });
            }

            if (loginBtn) {
                loginBtn.addEventListener('click', () => {
                    clearAllFormErrors();
                    container.classList.remove('active');
                    const loginFormBox = document.querySelector('.form-box.login');
                    if (loginFormBox) {
                        loginFormBox.classList.add('animate-fadeIn');
                    }
                });
            }

            // --- Input Validation ---
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            function showError(inputElement, message) {
                const inputBox = inputElement.closest('.input-box');
                if (!inputBox) return;
                const errorElement = inputBox.querySelector('.error-message');
                const decorativeIconElement = inputBox.querySelector('i.fa-solid:not(.password-toggle)');

                inputElement.classList.add('input-error');
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.add('visible');
                }
                if (decorativeIconElement) {
                    decorativeIconElement.classList.add('icon-hidden');
                }
            }

            function clearError(inputElement) {
                const inputBox = inputElement.closest('.input-box');
                if (!inputBox) return;
                const errorElement = inputBox.querySelector('.error-message');
                const decorativeIconElement = inputBox.querySelector('i.fa-solid:not(.password-toggle)');

                inputElement.classList.remove('input-error');
                if (errorElement) {
                    errorElement.classList.remove('visible');
                    errorElement.textContent = '';
                }
                if (decorativeIconElement) {
                    decorativeIconElement.classList.remove('icon-hidden');
                }
            }

            const passwordInputForStrength = document.getElementById('password');
            const strengthContainer = document.getElementById('passwordStrengthContainer');
            const strengthBarFill = document.getElementById('passwordStrengthFill');
            const strengthText = document.getElementById('passwordStrengthText');
            const requirementsList = document.getElementById('passwordRequirements');
            const itemAnimationTimeouts = {};
            const displayNoneTimeouts = {};
            const classRemovalTimeouts = {};

            const strengthLevels = [{
                    text: "Very Weak",
                    color: "bg-red-500",
                    width: "20%"
                },
                {
                    text: "Weak",
                    color: "bg-orange-500",
                    width: "40%"
                },
                {
                    text: "Medium",
                    color: "bg-yellow-500",
                    width: "60%"
                },
                {
                    text: "Good",
                    color: "bg-blue-500",
                    width: "80%"
                },
                {
                    text: "Strong",
                    color: "bg-green-500",
                    width: "100%"
                }
            ];

            const passwordCriteria = [{
                    id: 'length',
                    regex: /.{8,}/,
                    el: requirementsList?.querySelector('[data-requirement="length"]')
                },
                {
                    id: 'uppercase',
                    regex: /[A-Z]/,
                    el: requirementsList?.querySelector('[data-requirement="uppercase"]')
                },
                {
                    id: 'lowercase',
                    regex: /[a-z]/,
                    el: requirementsList?.querySelector('[data-requirement="lowercase"]')
                },
                {
                    id: 'number',
                    regex: /[0-9]/,
                    el: requirementsList?.querySelector('[data-requirement="number"]')
                },
                {
                    id: 'special',
                    regex: /[^A-Za-z0-9\s]/,
                    el: requirementsList?.querySelector('[data-requirement="special"]')
                }
            ];

            function slideUpAndFade(element, criterionId) {
                if (classRemovalTimeouts[criterionId]) {
                    clearTimeout(classRemovalTimeouts[criterionId]);
                    delete classRemovalTimeouts[criterionId];
                }
                element.classList.remove('requirement-entering');

                element.style.transition = 'all 0.4s ease-out';
                element.style.transform = 'translateY(-10px)';
                element.style.opacity = '0';
                element.style.maxHeight = '0';
                element.style.marginBottom = '0';

                if (displayNoneTimeouts[criterionId]) {
                    clearTimeout(displayNoneTimeouts[criterionId]);
                }
                displayNoneTimeouts[criterionId] = setTimeout(() => {
                    element.style.display = 'none';
                    delete displayNoneTimeouts[criterionId];
                }, 400);
            }

            function slideDownAndShow(element, criterionId) {
                if (displayNoneTimeouts[criterionId]) {
                    clearTimeout(displayNoneTimeouts[criterionId]);
                    delete displayNoneTimeouts[criterionId];
                }
                element.style.display = 'list-item';
                element.offsetHeight;

                element.style.transition = 'all 0.4s ease-out';

                element.style.maxHeight = '24px';
                element.style.marginBottom = '2px';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';

                element.classList.add('requirement-entering');

                if (classRemovalTimeouts[criterionId]) {
                    clearTimeout(classRemovalTimeouts[criterionId]);
                }
                classRemovalTimeouts[criterionId] = setTimeout(() => {
                    element.classList.remove('requirement-entering');
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                    delete classRemovalTimeouts[criterionId];
                }, 400);
            }

            function pulseCheck(icon) {
                icon.style.animation = 'pulse-check 0.6s ease-out';
                setTimeout(() => {
                    icon.style.animation = '';
                }, 600);
            }

            function addAnimationStyles() {
                if (!document.getElementById('password-strength-animations')) {
                    const style = document.createElement('style');
                    style.id = 'password-strength-animations';
                    style.textContent = `
            @keyframes pulse-check {
                0% { transform: scale(1); }
                30% { transform: scale(1.3); }
                60% { transform: scale(0.9); }
                100% { transform: scale(1); }
            }
            
            @keyframes slide-in-bounce {
                0% { 
                    transform: translateY(-20px); 
                    opacity: 0; 
                }
                60% { 
                    transform: translateY(2px); 
                    opacity: 0.8; 
                }
                100% { 
                    transform: translateY(0); 
                    opacity: 1; 
                }
            }
            
            .requirement-item {
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }
            
            .requirement-entering {
                animation: slide-in-bounce 0.4s ease-out;
            }
        `;
                    document.head.appendChild(style);
                }
            }

            let tooltipHideTimeoutId = null;

            function updatePasswordStrengthUI(password) {
                if (!strengthContainer || !strengthBarFill || !strengthText || !requirementsList) {
                    return;
                }

                if (tooltipHideTimeoutId) {
                    clearTimeout(tooltipHideTimeoutId);
                    tooltipHideTimeoutId = null;
                }
                strengthContainer.classList.remove('tooltip-is-hiding', 'tooltip-is-showing');


                let metCriteriaCount = 0;
                passwordCriteria.forEach((criterion, index) => {
                    const icon = criterion.el?.querySelector('i');
                    if (!icon || !criterion.el) return;
                    const criterionId = criterion.id;

                    const isMet = criterion.regex.test(password);

                    if (isMet) {
                        metCriteriaCount++;
                        if (itemAnimationTimeouts[criterionId]) clearTimeout(itemAnimationTimeouts[criterionId]);
                        if (classRemovalTimeouts[criterionId]) clearTimeout(classRemovalTimeouts[criterionId]);
                        if (displayNoneTimeouts[criterionId]) clearTimeout(displayNoneTimeouts[criterionId]);
                        criterion.el.classList.remove('requirement-entering');

                        icon.classList.remove('fa-times', 'text-red-500');
                        icon.classList.add('fa-check', 'text-green-500');
                        criterion.el.classList.remove('text-gray-500');
                        criterion.el.classList.add('text-green-700');
                        pulseCheck(icon);

                        itemAnimationTimeouts[criterionId] = setTimeout(() => {
                            if (criterion.regex.test(passwordInputForStrength.value)) {
                                slideUpAndFade(criterion.el, criterionId);
                            }
                            delete itemAnimationTimeouts[criterionId];
                        }, 800);


                    } else {
                        if (itemAnimationTimeouts[criterionId]) clearTimeout(itemAnimationTimeouts[criterionId]);
                        if (displayNoneTimeouts[criterionId]) clearTimeout(displayNoneTimeouts[criterionId]);

                        icon.classList.remove('fa-check', 'text-green-500');
                        icon.classList.add('fa-times', 'text-red-500');
                        criterion.el.classList.remove('text-green-700');
                        criterion.el.classList.add('text-gray-500');

                        const computedStyle = window.getComputedStyle(criterion.el);
                        const needsToShowAnimation = criterion.el.style.display === 'none' ||
                            computedStyle.opacity !== '1' ||
                            criterion.el.classList.contains('requirement-entering');
                        if (needsToShowAnimation) {
                            slideDownAndShow(criterion.el, criterionId);
                        } else {
                            criterion.el.classList.remove('requirement-entering');
                            if (classRemovalTimeouts[criterionId]) {
                                clearTimeout(classRemovalTimeouts[criterionId]);
                                delete classRemovalTimeouts[criterionId];
                            }
                            criterion.el.style.opacity = '1';
                            criterion.el.style.transform = 'translateY(0)';
                            criterion.el.style.maxHeight = '24px';
                            criterion.el.style.marginBottom = '2px';
                        }
                    }
                });


                const lengthMet = passwordCriteria.find(c => c.id === 'length')?.regex.test(password);
                let levelIndex = metCriteriaCount - 1;

                if (!lengthMet) {
                    levelIndex = Math.min(levelIndex, 0);
                    if (metCriteriaCount > 0 && password.length > 0) levelIndex = 0;
                }
                if (password.length === 0) {
                    levelIndex = -1;
                } else if (metCriteriaCount === 0 && password.length > 0) {
                    levelIndex = 0;
                }
                levelIndex = Math.max(0, Math.min(levelIndex, strengthLevels.length - 1));
                const currentStrength = (password.length === 0 || (metCriteriaCount === 0 && password.length > 0 && !
                        lengthMet)) ?
                    strengthLevels[0] : strengthLevels[levelIndex];

                if (password.length === 0) {
                    strengthBarFill.className = 'h-full transition-all duration-300 ease-in-out bg-gray-200';
                    strengthBarFill.style.width = '0%';
                    strengthText.textContent = '';
                } else {
                    strengthBarFill.className = `h-full transition-all duration-300 ease-in-out ${currentStrength.color}`;
                    strengthBarFill.style.width = currentStrength.width;
                    strengthText.textContent = currentStrength.text;
                    strengthText.className =
                        `text-xs font-medium mb-1 text-right ${currentStrength.color.replace('bg-', 'text-')}`;
                }


                const isStrong = currentStrength && currentStrength.text === "Strong" && password.length > 0;

                if (isStrong) {
                    if (!strengthContainer.classList.contains('hidden') && !strengthContainer.classList.contains(
                            'tooltip-is-hiding')) {
                        strengthContainer.classList.add('tooltip-is-hiding');
                        tooltipHideTimeoutId = setTimeout(() => {
                            strengthContainer.classList.add('hidden');
                            strengthContainer.classList.remove('tooltip-is-hiding');
                        }, 300);
                    }
                } else {
                    if (document.activeElement === passwordInputForStrength) {
                        if (strengthContainer.classList.contains('hidden') || strengthContainer.classList.contains(
                                'tooltip-is-hiding')) {
                            strengthContainer.classList.remove('hidden', 'tooltip-is-hiding');
                            strengthContainer.classList.add('tooltip-is-showing');
                            setTimeout(() => strengthContainer.classList.remove('tooltip-is-showing'), 300);
                        }
                    }
                }
            }

            function resetPasswordStrengthUI() {
                if (!strengthBarFill || !strengthText || !requirementsList) return;

                passwordCriteria.forEach(criterion => {
                    const icon = criterion.el?.querySelector('i');
                    if (!icon || !criterion.el) return;

                    // Reset icons
                    icon.classList.remove('fa-check', 'text-green-500');
                    icon.classList.add('fa-times', 'text-red-500');
                    criterion.el.classList.remove('text-green-700');
                    criterion.el.classList.add('text-gray-500');

                    // Reset element visibility and styles
                    criterion.el.style.display = 'list-item';
                    criterion.el.style.opacity = '1';
                    criterion.el.style.transform = 'translateY(0)';
                    criterion.el.style.maxHeight = '';
                    criterion.el.style.marginBottom = '';
                    criterion.el.style.paddingTop = '';
                    criterion.el.style.paddingBottom = '';
                    // criterion.el.style.transition = '';
                });

                strengthBarFill.className = 'h-full transition-all duration-300 ease-in-out bg-gray-200';
                strengthBarFill.style.width = '0%';
                strengthText.textContent = '';
            }

            // Initialize animations and event listeners
            if (passwordInputForStrength) {
                // Add animation styles
                addAnimationStyles();

                // Add requirement-item class to all requirements for smoother animations
                passwordCriteria.forEach(criterion => {
                    if (criterion.el) {
                        criterion.el.classList.add('requirement-item');
                    }
                });

                passwordInputForStrength.addEventListener('input', function() {
                    updatePasswordStrengthUI(this.value);
                });

                passwordInputForStrength.addEventListener('focus', function() {
                    if (strengthContainer) {
                        if (tooltipHideTimeoutId) {
                            clearTimeout(tooltipHideTimeoutId);
                            tooltipHideTimeoutId = null;
                        }
                        strengthContainer.classList.remove('tooltip-is-hiding');

                        updatePasswordStrengthUI(this.value);
                    }
                });

                passwordInputForStrength.addEventListener('blur', function() {
                    if (strengthContainer) {
                        // Clear any pending hide from strength logic because blur takes precedence
                        if (tooltipHideTimeoutId) {
                            clearTimeout(tooltipHideTimeoutId);
                            tooltipHideTimeoutId = null;
                        }
                        strengthContainer.classList.remove('tooltip-is-hiding', 'tooltip-is-showing');

                        // Standard blur behavior: hide after a short delay
                        setTimeout(() => {
                            if (document.activeElement !== this && document.activeElement !==
                                strengthContainer && !strengthContainer.contains(document.activeElement)) {
                                strengthContainer.classList.add('hidden');
                            }
                        }, 150);
                    }
                });
            }

            function clearAllFormErrors() {
                document.querySelectorAll('.input-box input').forEach(input => clearError(input));
                resetPasswordStrengthUI();
                if (strengthContainer && document.activeElement !== passwordInputForStrength) {
                    strengthContainer.classList.add('hidden');
                }
            }

            // --- Login Form Validation ---
            const manualLoginForm = document.getElementById('manualLoginForm');
            const usernameOrEmailInput = document.getElementById('usernameOrEmail');
            const loginPasswordInput = document.getElementById('loginPassword');

            if (manualLoginForm) {
                manualLoginForm.addEventListener('submit', function(event) {
                    let isValid = true;
                    manualLoginForm.querySelectorAll('.input-box input').forEach(input => clearError(input));

                    if (!usernameOrEmailInput.value.trim()) {
                        showError(usernameOrEmailInput, 'Username or Email is required.');
                        isValid = false;
                    } else {
                        clearError(usernameOrEmailInput);
                    }

                    if (!loginPasswordInput.value.trim()) {
                        showError(loginPasswordInput, 'Password is required.');
                        isValid = false;
                    } else {
                        clearError(loginPasswordInput);
                    }

                    if (!isValid) {
                        event.preventDefault();
                        manualLoginForm.classList.add('animate-shake');
                        setTimeout(() => {
                            manualLoginForm.classList.remove('animate-shake');
                        }, 500);
                    } else {
                        Swal.fire({
                            title: "Logging in...",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                });
            }

            // --- Registration Form Validation & Submission ---
            const submitRegisterForm = document.getElementById('submitRegisterForm');
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            // passwordInputForStrength is already defined as 'password' input
            const confirmPasswordInput = document.getElementById('confirmPassword');

            if (submitRegisterForm) {
                submitRegisterForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    let isValid = true;
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
                    const currentPassword = passwordInputForStrength.value;
                    if (!currentPassword) {
                        showError(passwordInputForStrength, 'Password is required.');
                        isValid = false;
                        if (strengthContainer) strengthContainer.classList.add(
                            'hidden');
                    } else {
                        let passwordIssues = [];
                        if (!passwordCriteria.find(c => c.id === 'length')?.regex.test(currentPassword))
                            passwordIssues.push("be at least 8 characters");
                        if (!passwordCriteria.find(c => c.id === 'uppercase')?.regex.test(currentPassword))
                            passwordIssues.push("contain an uppercase letter");
                        if (!passwordCriteria.find(c => c.id === 'lowercase')?.regex.test(currentPassword))
                            passwordIssues.push("contain a lowercase letter");
                        if (!passwordCriteria.find(c => c.id === 'number')?.regex.test(currentPassword))
                            passwordIssues.push("contain a number");
                        if (!passwordCriteria.find(c => c.id === 'special')?.regex.test(currentPassword))
                            passwordIssues.push("contain a special character");

                        if (passwordIssues.length > 0) {
                            let errorMsg = "Password " + passwordIssues.join(", ") + ".";
                            if (passwordIssues.length > 2) errorMsg =
                                "Password needs to meet all criteria shown below.";
                            else if (passwordIssues.length === 1 && passwordIssues[0] ===
                                "be at least 8 characters") errorMsg =
                                "Password must be at least 8 characters long.";


                            showError(passwordInputForStrength, errorMsg);
                            isValid = false;
                            if (strengthContainer) strengthContainer.classList.remove(
                                'hidden');
                        } else {
                            clearError(passwordInputForStrength);

                        }
                    }

                    // Validate Confirm Password
                    if (!confirmPasswordInput.value) {
                        showError(confirmPasswordInput, 'Please confirm your password.');
                        isValid = false;
                    } else if (currentPassword && confirmPasswordInput.value !== currentPassword) {
                        showError(confirmPasswordInput, 'Passwords do not match.');
                        isValid = false;
                    } else {
                        clearError(confirmPasswordInput);
                    }

                    if (!isValid) {
                        submitRegisterForm.classList.add('animate-shake');
                        setTimeout(() => {
                            submitRegisterForm.classList.remove('animate-shake');
                        }, 500);
                        return;
                    }

                    Swal.fire({
                        title: "Creating your account...",
                        text: "Just a moment while we set things up for you!",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const username = usernameInput.value.trim();
                        const email = emailInput.value.trim();
                        const password = currentPassword;

                        const response = await fetch("{{ route('submitRegister') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                username,
                                email,
                                password
                            }),
                        });

                        const data = await response.json();
                        Swal.close();
                        console.log(response, data);

                        if (response.ok && data.success) {
                            if (container) container.classList.remove('active');
                            submitRegisterForm.reset();
                            resetPasswordStrengthUI();
                            if (strengthContainer) strengthContainer.classList.add(
                                'hidden');
                            submitRegisterForm.querySelectorAll('.input-box input').forEach(input =>
                                clearError(input));
                            window.location.href = '{{ route('verification.notice') }}' + '?email=' +
                                encodeURIComponent(email);
                        } else {
                            let errorMessage = data.message || 'Registration failed.';
                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('\n');
                            }
                            Toastify({
                                text: errorMessage || 'Registration Error',
                                duration: 3000,
                                style: {
                                    background: "#e74c3c"
                                }
                            }).showToast();
                        }
                    } catch (error) {
                        Swal.close();
                        console.error('Registration Fetch Error:', error);
                        Toastify({
                            text: 'An unexpected error occurred. Please try again.',
                            duration: 3000,
                            style: {
                                background: "#e74c3c"
                            }
                        }).showToast();
                    }
                });
            }

            // Add live validation on blur and clear on input
            document.querySelectorAll('.input-box input').forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.input-box')?.classList.add('focus');
                });

                input.addEventListener('blur', function() {
                    this.closest('.input-box')?.classList.remove('focus');
                    const currentForm = this.closest('form');
                    if (!currentForm) return;

                    if (this.id === 'usernameOrEmail') {
                        if (!this.value.trim()) showError(this, 'Username or Email is required.');
                        else clearError(this);
                    } else if (this.id === 'loginPassword') {
                        if (!this.value.trim()) showError(this, 'Password is required.');
                        else clearError(this);
                    } else if (this.id === 'username') {
                        if (!this.value.trim()) showError(this, 'Username is required.');
                        else clearError(this);
                    } else if (this.id === 'email') {
                        if (!this.value.trim()) showError(this, 'Email is required.');
                        else if (!emailRegex.test(this.value.trim())) showError(this, 'Invalid email format.');
                        else clearError(this);
                    } else if (this.id === 'password') {
                        if (!this.value && document.activeElement !==
                            this) {
                            showError(this, 'Password is required.');
                        } else if (this.value && this.value.length < 8 && document.activeElement !== this) {
                            showError(this, 'Password must be at least 8 characters.');
                        } else if (this.classList.contains('input-error') && this.value.length >=
                            8) {
                            clearError(this);
                        }
                        if (confirmPasswordInput && confirmPasswordInput.value && this.value !==
                            confirmPasswordInput.value) {
                            showError(confirmPasswordInput, 'Passwords do not match.');
                        } else if (confirmPasswordInput && confirmPasswordInput.value) {
                            clearError(confirmPasswordInput);
                        }
                    } else if (this.id === 'confirmPassword') {
                        if (!this.value && document.activeElement !== this) showError(this,
                            'Please confirm password.');
                        else if (passwordInputForStrength && passwordInputForStrength.value && this.value !==
                            passwordInputForStrength.value) showError(this, 'Passwords do not match.');
                        else clearError(this);
                    }
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('input-error')) {
                        if (this.id === 'password') {
                            let passwordStillHasIssues = false;
                            if (!this.value) passwordStillHasIssues =
                                true;
                            else if (this.value.length < 8) passwordStillHasIssues =
                                true;

                            if (!
                                passwordStillHasIssues
                            ) { // Example: clear "too short" if it becomes long enough
                                const errorMsgElement = this.closest('.input-box').querySelector(
                                    '.error-message');
                                if (errorMsgElement && (errorMsgElement.textContent.includes('8 characters') ||
                                        errorMsgElement.textContent.includes('required'))) {
                                    clearError(this);
                                }
                            }
                        } else {
                            clearError(this);
                        }
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const mainContainer = document.querySelector('.container.form-container');
                if (mainContainer) mainContainer.classList.add('animate-fadeIn');

                // Initial state for password strength tooltip if password field exists
                if (passwordInputForStrength && strengthContainer) {
                    resetPasswordStrengthUI();
                    strengthContainer.classList.add('hidden');
                }
            });

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
            .animate-fadeIn { animation: fadeIn 0.5s ease forwards; }
            .animate-shake { animation: shake 0.5s ease-in-out; }
        `;
                document.head.appendChild(styleSheet);
            }

            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    if (input && (input.type === 'password' || input.type === 'text')) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            this.classList.remove('fa-eye');
                            this.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            this.classList.remove('fa-eye-slash');
                            this.classList.add('fa-eye');
                        }
                    }
                });
            });
        </script>
    </body>
@endsection
