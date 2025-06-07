@extends('layout')
@section('content')
    @if (session()->has('Error'))
        <Script>
            Toastify({
                text: "{{ session('Error') }}" || "An unexpected error occurred from the server.",
                duration: 3000,
                style: {
                    background: "#e74c3c"
                }
            }).showToast();
        </Script>
    @endif
    <div class="flex justify-center items-center min-h-screen">
        <div
            class="max-w-sm w-full rounded-lg shadow-lg bg-slate-900 p-6 space-y-6 border border-gray-200 dark:border-gray-700">
            <div class="space-y-2 text-center">
                <h1 class="text-center text-3xl text-gray-300 mb-4 font-bold" style="text-shadow: 0 0 3px white">Login</h1>
            </div>
            <div class="space-y-4">
                <div class="space-y-2">
                    <label
                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-300"
                        style="text-shadow: 0 0 2px white" for="emailOrUsername">Email or Username</label>
                    <input
                        class="flex h-10 w-full rounded-md border border-gray-300 border-input bg-slate-900 px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 focus:ring-offset-gray-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        type="text" id="emailOrUsername" placeholder="manish@example.com" required="" />
                </div>
                <div class="space-y-2">
                    <label
                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-gray-300"
                        style="text-shadow: 0 0 2px white" for="password">Password</label>
                    <input
                        class="flex h-10 w-full rounded-md border border-gray-300 border-input bg-slate-900 px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 focus:ring-offset-gray-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        type="password" id="password" placeholder="" required="" />
                </div>
                <div class="flex justify-center items-center space-x-7">
                    <button
                        class="w-[45%] inline-block cursor-pointer rounded-md bg-gray-700 px-4 py-3.5 text-center text-sm font-semibold uppercase text-white transition duration-200 ease-in-out hover:bg-gray-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-700 focus-visible:ring-offset-2 active:scale-95">Login</button>
                    <button
                        class="w-[45%] inline-block cursor-pointer rounded-md bg-gray-700 px-4 py-3.5 text-center text-sm font-semibold uppercase text-white transition duration-200 ease-in-out hover:bg-gray-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-700 focus-visible:ring-offset-2 active:scale-95">Register</button>
                </div>
                <div class="flex items-center space-x-2">
                    <hr class="flex-grow border-zinc-200 dark:border-zinc-700" />
                    <span class="text-zinc-400 dark:text-zinc-300 text-sm">OR</span>
                    <hr class="flex-grow border-zinc-200 dark:border-zinc-700" />
                </div>
                <button onclick="window.location.href='{{ route('auth') }}'"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full bg-[#4285F4] text-white">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="w-5 h-5 mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="4"></circle>
                            <line x1="21.17" x2="12" y1="8" y2="8"></line>
                            <line x1="3.95" x2="8.54" y1="6.06" y2="14"></line>
                            <line x1="10.88" x2="15.46" y1="21.94" y2="14"></line>
                        </svg>
                        Login with Google
                    </div>
                </button>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
