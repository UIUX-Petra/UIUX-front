<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Verify Email' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body>
    <div class="w-screen h-screen flex items-center justify-center bg-white p-4">
        <div class="w-full max-w-lg md:h-auto h-full flex flex-col items-center justify-center">
            <svg width="120" height="120" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 171 171" class="mb-6">
                <g id="Layer_2" data-name="Layer 2">
                    <g id="Layer_1-2" data-name="Layer 1">
                        <circle cx="85.5" cy="85.5" r="85.5" fill="#ebecf0" />
                        <circle cx="85.5" cy="85.5" r="85.5" fill="#fcfbe6" fill-opacity="0.4" />
                        <path
                            d="M85.5,159A73.5,73.5,0,1,1,159,85.5,73.62,73.62,0,0,1,85.5,159Zm0-144A70.5,70.5,0,1,0,156,85.5,70.55,70.55,0,0,0,85.5,15Z"
                            fill="#333" />
                        <path
                            d="M49.2,53.9,78.8,87a8.94,8.94,0,0,0,6.7,3,9.1,9.1,0,0,0,6.7-3l29.1-32.6a1.56,1.56,0,0,1,.8-.6,10.57,10.57,0,0,0-4-.8H52.9a10.06,10.06,0,0,0-3.9.8A.35.35,0,0,0,49.2,53.9Z"
                            fill="#333" />
                        <path
                            d="M126.5,58a1.8,1.8,0,0,1-.6.9l-29,32.5a15.38,15.38,0,0,1-11.4,5.1,15.18,15.18,0,0,1-11.4-5.1l-29.5-33-.2-.2A9.75,9.75,0,0,0,43,63.3v44.8A9.94,9.94,0,0,0,53,118h65a9.94,9.94,0,0,0,10-9.9V63.3a10.27,10.27,0,0,0-1.5-5.3"
                            fill="#333" />
                    </g>
                </g>
            </svg>
            <div class="w-full flex flex-col items-center text-center">
                <h1 class="text-[#364252] w-full font-bold leading-none text-3xl sm:text-4xl md:text-5xl">Please Verify
                    Your Email</h1>

                {{-- Session Messages --}}
                @if (session('Success'))
                    <div class="mt-4 p-3 w-full max-w-md text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('Success') }}
                    </div>
                @endif
                @if (session('Error'))
                    <div class="mt-4 p-3 w-full max-w-md text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        {{ session('Error') }}
                    </div>
                @endif

                @if ($email)
                    <p class="mt-5 text-black text-lg sm:text-xl">You're almost there! <br id="break"
                            class="sm:hidden">We sent an email to</p>
                    <p class="text-[#364252] mt-2 font-bold text-xl sm:text-2xl">{{ $email }}</p>
                    <p class="text-black mt-8 text-base sm:text-lg max-w-md">Just click on the link in that email to
                        complete your registration. If you don't see it, you may need to <b class="text-[#364252]">check
                            your spam</b> folder.</p>

                    <div class="mt-10 flex flex-col items-center space-y-4 w-full max-w-xs">
                        <a href="https://mail.google.com/mail/u/0/" target="_blank"
                            class="w-full p-3 rounded-md font-bold text-lg text-white bg-[#364252] hover:bg-opacity-90 transition-colors">Go
                            To Your Mailbox</a>

                        <form method="POST" action="{{ route('verification.resend') }}" class="w-full">
                            @csrf
                            <input type="hidden" name="email_to_resend" value="{{ $email }}">
                            <button type="submit"
                                class="w-full p-3 rounded-md font-medium text-lg text-[#364252] bg-gray-200 hover:bg-gray-300 transition-colors">
                                Resend Verification Email
                            </button>
                        </form>
                    </div>
                @else
                    <p class="mt-5 text-black text-lg sm:text-xl max-w-md">
                        If you have recently registered, please check your email for a verification link.
                    </p>
                    <p class="mt-8">
                        <a href="{{ route('loginOrRegist') }}" class="text-[#364252] hover:underline font-medium">Return
                            to Login/Register</a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    <script>
        const breakElement = document.getElementById('break');

        function toggleBreak() {
            if (breakElement) {
                if (window.innerWidth <= 640) {
                    breakElement.classList.remove('hidden');
                } else {
                    breakElement.classList.add('hidden');
                }
            }
        }
        window.addEventListener('resize', toggleBreak);
        toggleBreak();
    </script>
</body>

</html>
