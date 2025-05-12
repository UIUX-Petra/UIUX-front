@extends('layoutNoSidebar')

@php
    $hasSidebar = false;
@endphp

@section('content')
    @include('utils.background')
    @if (session()->has('Error'))
        <Script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('Error') }}'
            });
        </Script>
    @endif
    <style>
        body {
            overflow: hidden;
        }

        .form-box {
            z-index: 1;
            transition: .6s ease-in-out 1.2s, visibility 0s 1s;
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



        .toggle-box::before {
            content: '';
            position: absolute;
            width: 300%;
            height: 100%;
            left: -250%;
            background: #7494ec;
            border-radius: 150px;
            z-index: 2;
            transition: 1.8s ease-in-out;
        }

        .toggle-panel {
            z-index: 2;
            transition: .6s ease-in-out;
        }

        .toggle-panel.toggle-left {
            left: 0;
            transition-delay: 1.2s;
        }

        .container.active .toggle-panel.toggle-left {
            left: -50%;
            transition-delay: .6s;
        }

        .toggle-panel.toggle-right {
            right: -50%;
            transition-delay: .6s;
        }

        .container.active .toggle-panel.toggle-right {
            right: 0;
            transition-delay: 1.2s;
        }

        .container.active .toggle-box::before {
            left: 50%;
        }


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


            /* tambahan */
            .cont .swing {
                margin-bottom: 40%;
                margin-top: 40%;
                margin-left: 10%;
                margin-right: 10%;
            }
        }


        /* tambahan decor login swing */
        .cont .swing {
            margin-bottom: 7%;
            margin-top: 7%;
            margin-left: 20%;
            margin-right: 20%;
            position: absolute;
            inset: 0;
            border: 6px solid #fff;
            transition: 0.5 ease;
            border: 6px solid var(--clr);
            filter: drop-shadow(0 0 20px var(--clr));
        }

        .cont .swing:nth-child(1) {
            border-radius: 43% 57% 74% 26% / 44% 30% 70% 56%;
            animation: animate 6s linear infinite;
        }

        .cont .swing:nth-child(2) {
            border-radius: 23% 77% 31% 69% / 71% 30% 70% 29%;
            animation: animate 4s linear infinite;
        }

        .cont .swing:nth-child(3) {
            border-radius: 44% 56% 16% 84% / 37% 65% 35% 63%;
            animation: animate2 10s linear infinite;
        }

        @keyframes animate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes animate2 {
            0% {
                transform: rotate(360deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }
    </style>

    <body
        class="flex justify-center items-center min-h-screen w-full bg-gradient-to-r from-gray-200 via-blue-200 to-blue-300">
        <div class="mx-auto cont relative w-[100%] h-[100%] flex justify-center items-center">
            <div class="">
                <i class="swing" style="--clr: #7494ec;"></i>
                <i class="swing" style="--clr: #633F92;"></i>
                <i class="swing" style="--clr: #fffd44;"></i>
            </div>
            <div
                class="container relative w-full m-[20px] max-w-[850px] h-[550px] bg-white rounded-[30px] shadow-lg overflow-hidden">
                <!-- Login Form -->
                <div
                    class="form-box login absolute right-0 w-[50%] h-full flex flex-col items-center justify-center text-black">
                    <form class="w-full px-8" id="manualLogin" action="{{ route('manualLogin') }}" method="POST">
                        @csrf
                        <h1 class="text-[36px] mb-6 text-black font-bold text-center">Login</h1>
                        <div class="input-box relative w-full mb-6">
                            <input id="usernameOrEmail" type="text" aria-label="Username or Email"
                                placeholder="Username/Email" required="" name="usernameOrEmail"
                                class="w-full pr-[50px] pl-5 py-3 bg-gray-200 rounded-[8px] border-none outline-none text-[16px] placeholder-gray-500 placeholder:font-semibold">
                            <i
                                class="fa-solid fa-user absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                        </div>
                        <div class="input-box relative w-full mb-6">
                            <input id="loginPassword" type="password" aria-label="Password" placeholder="Password"
                                required="" name="loginPassword" minlength="8"
                                class="w-full pr-[50px] pl-5 py-3 bg-gray-200 rounded-[8px] border-none outline-none text-[16px] placeholder-gray-500 placeholder:font-semibold">
                            <i
                                class="fa-solid fa-lock absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                        </div>
                        <button type="submit"
                            class="w-full bg-emerald-800 text-white font-bold py-2 px-4 rounded hover:bg-emerald-900">Login</button>
                        <div class="text-center font-semibold my-4 text-black">OR</div>
                        <button onclick="window.location.href='{{ route('auth') }}'"
                            class="w-full bg-[#7494ec] text-white font-bold py-2 px-4 rounded hover:bg-gray-400 p-[20px]">
                            <i class="fa-brands fa-google ml-"></i> Login with Petra Email
                        </button>
                    </form>
                </div>

                <!-- Registration Form -->
                <div
                    class="form-box registration absolute right-0 w-[50%] h-full flex flex-col items-center justify-center text-black">
                    <form class="w-full px-8" id="submitRegister">
                        @csrf
                        <h1 class="text-[36px] mb-6 text-black font-bold text-center">Registration</h1>
                        <div class="input-box relative w-full mb-6">
                            <input type="text" aria-label="Username" placeholder="Username" required="" id="username"
                                class="w-full pr-[50px] pl-5 py-3 bg-gray-200 rounded-[8px] border-none outline-none text-[16px] placeholder-gray-500 placeholder:font-semibold">
                            <i
                                class="fa-solid fa-user absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                        </div>
                        <div class="input-box relative w-full mb-6">
                            <input type="email" aria-label="Email" placeholder="Email" required="" id="email"
                                class="w-full pr-[50px] pl-5 py-3 bg-gray-200 rounded-[8px] border-none outline-none text-[16px] placeholder-gray-500 placeholder:font-semibold">
                            <i
                                class="fa-solid fa-envelope absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                        </div>
                        <div class="input-box relative w-full mb-6">
                            <input type="password" aria-label="Password" placeholder="Password" required=""
                                id="password" minlength="8"
                                class="w-full pr-[50px] pl-5 py-3 bg-gray-200 rounded-[8px] border-none outline-none text-[16px] placeholder-gray-500 placeholder:font-semibold">
                            <i
                                class="fa-solid fa-lock absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                        </div>
                        <div class="input-box relative w-full mb-6">
                            <input type="password" aria-label="Confirm Password" placeholder="Confirm Password"
                                id="confirmPassword" minlength="8" required=""
                                class="w-full pr-[50px] pl-5 py-3 bg-gray-200 rounded-[8px] border-none outline-none text-[16px] placeholder-gray-500 placeholder:font-semibold">
                            <i
                                class="fa-solid fa-lock absolute right-5 top-1/2 -translate-y-1/2 text-[20px] text-gray-400"></i>
                        </div>
                        <button type="submit"
                            class="w-full bg-[#7494ec] text-white font-bold py-2 px-4 rounded hover:bg-emerald-900">Register</button>
                    </form>
                </div>

                <div class="toggle-box absolute w-full h-full">
                    <div
                        class="toggle-panel toggle-left left-0 absolute w-[50%] h-[100%] flex flex-col justify-center items-center text-white">

                        <h1 class="text-2xl font-extrabold">Hello, Informates🤩</h1>
                        <p class="mb-[20px]">Don't have an account?</p>
                        <button
                            class="btn register-btn w-[160px] h-[46px] bg-transparent border-2 border-white rounded-lg">Register</button>
                    </div>
                    <div
                        class="toggle-panel toggle-right right-[-50%] absolute w-[50%] h-[100%] flex flex-col justify-center items-center text-white">
                        <h1 class="text-2xl font-extrabold">Welcome Back, Informates👋</h1>
                        <p class="mb-[20px]">Already have an account?</p>
                        <button
                            class="btn login-btn w-[160px] h-[46px] bg-transparent border-2 border-white rounded-lg">Login</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const container = document.querySelector('.container');
            const registerBtn = document.querySelector('.register-btn');
            const loginBtn = document.querySelector('.login-btn');

            registerBtn.addEventListener('click', () => {
                container.classList.add('active');
            });

            loginBtn.addEventListener('click', () => {
                container.classList.remove('active');
            });

            const submitRegister = document.getElementById('submitRegister');
            submitRegister.addEventListener('submit', async function(event) {
                event.preventDefault();
                Swal.fire({
                    title: "Registering...",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                try {
                    const username = document.getElementById('username').value;
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;

                    if (password !== confirmPassword) {
                        Swal.close();
                        Swal.fire({
                            title: 'Error',
                            text: "Password do not match!",
                            icon: 'error',
                            confirmButtonText: 'OK!',
                            didOpen: () => {
                                Swal.hideLoading();
                            }
                        })
                        return;
                    }

                    const response = await fetch("{{ route('submitRegister') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            username,
                            email,
                            password
                        }),
                    });
                    const data = await response.json();
                    console.log(data);

                    Swal.close();
                    if (data.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Registration successful!',
                        });
                        container.classList.remove('active');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Registration failed.',
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                    });
                }
            });
        </script>
    </body>
@endsection
