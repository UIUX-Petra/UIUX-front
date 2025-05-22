<style>
    .ham {
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: transform 400ms;
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .hamRotate.active {
        transform: rotate(45deg);
    }

    .hamRotate180.active {
        transform: rotate(180deg);
    }

    .line {
        fill: none;
        transition: stroke-dasharray 400ms, stroke-dashoffset 400ms;
        stroke: #000;
        stroke-width: 5.5;
        stroke-linecap: round;
    }

    .ham4 .top {
        stroke-dasharray: 40 121;
    }

    .ham4 .bottom {
        stroke-dasharray: 40 121;
    }

    .ham4.active .top {
        stroke-dashoffset: -68px;
    }

    .ham4.active .bottom {
        stroke-dashoffset: -68px;
    }

</style>
<nav class="bg-white border-gray-200 relative w-full h-auto z-50 shadow-md">
    <div class="max-w-screen-xl relative flex flex-wrap items-center justify-between mx-auto p-4">
        <!-- Hamburger Button -->
        <svg id="hamburger-svg" class="ham hamRotate ham4 md:hidden" viewBox="0 0 100 100" width="50">
            <path class="line top"
                d="m 70,33 h -40 c 0,0 -8.5,-0.149796 -8.5,8.5 0,8.649796 8.5,8.5 8.5,8.5 h 20 v -20" />
            <path class="line middle" d="m 70,50 h -40" />
            <path class="line bottom"
                d="m 30,67 h 40 c 0,0 8.5,0.149796 8.5,-8.5 0,-8.649796 -8.5,-8.5 -8.5,-8.5 h -20 v 20" />
        </svg>

        <!-- Logo Section -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="self-center text-wrap lg:text-2xl text-lg font-semibold whitespace-nowrap text-gray-900 lg:w-auto w-40 text-center">INFORMATICS CONNECT</span>
        </a>


        <!-- Navbar Links -->
        <!-- Ganti route -->
        <div class="hidden md:flex items-center space-x-8 rtl:space-x-reverse">
            <a href="{{route('home')}}" class="text-gray-900 hover:text-gray-700">Home</a>
            <a href="{{route('popular')}}" class="text-gray-900 hover:text-gray-700">Popular</a>
            <a href="{{route('viewAllUsers')}}" class="text-gray-900 hover:text-gray-700">Users</a>
            <a href="{{route('viewAllTags')}}" class="text-gray-900 hover:text-gray-700">Tags</a>
            <a href="{{route('askPage')}}" class="text-gray-900 hover:text-gray-700">Ask a Question</a>
            <a href="{{route('user.leaderboard')}}" class="block text-gray-900">Leaderboard</a>
        </div>


        <!-- Search Input  -->
        <div class="hidden md:flex items-center">
            <input type="text" placeholder="Search..."
                class="px-4 py-2 rounded-s-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-[#485d93]-500 text-sm">
            <button type="submit"
                class="text-gray-900 bg-gray-100 hover:bg-gray-200  px-3 py-2 rounded-e-lg focus:outline-none">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>

        @if (!session()->has('email'))
            <!-- Sign Up Button -->
            <a href="{{ route('loginOrRegist') }}"
                class="text-white hover:text-[#485d93] hover:bg-[#a8bcf3] bg-[#7494ec] font-medium rounded-lg lg:text-sm lg:px-4 lg:py-2 text-xs px-2 py-2">
                Regist / Login
            </a>
        @else

            <!-- User Action -->
            <div class="relative ml-3">
                <div>
                    <button type="button"
                        class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <img class="size-8 rounded-full" src="{{ $image ? asset('storage/' . $image) : asset('assets/empty.jpg') }}" alt="User avatar">
                    </button>
                </div>

                <!-- Dropdown Menu -->
                <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                    id="user-menu" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                    <div class="py-1" role="none">
                        <!-- Ganti route -->
                        <a href="{{route('seeProfile')}}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-300 ease transition duration-150" role="menuitem">Profile</a>
                        <a href="{{route('editProfile')}}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-300 ease transition duration-150" role="menuitem">Settings</a>
                        <a href="{{route('logout')}}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-300 ease transition duration-150" role="menuitem">Sign
                            out</a>
                    </div>
                </div>
            </div>
        @endif
        <!-- Mobile Links Ganti route -->
        <div id="mobile-menu"
            class="hidden flex-col space-y-4 my-4 md:hidden w-full bg-gray-100 rounded-lg p-4 border border-gray-200">
            <a href="{{route('home')}}" class="block text-gray-900">Home</a>
            <a href="{{route('popular')}}" class="block text-gray-900">Popular</a>
            <a href="{{route('viewAllUsers')}}" class="block text-gray-900">Users</a>
            <a href="{{route('viewAllTags')}}" class="block text-gray-900">Tags</a>
            <a href="{{route('user.leaderboard')}}" class="block text-gray-900">Leaderboard</a>
            <a href="{{route('askPage')}}" class="block text-gray-900">Ask a Question</a>

            <!-- Search Input -->
            <div class="flex items-center space-x-2">
                <input type="text" placeholder="Search..."
                    class="w-full px-4 py-2 rounded-s-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-[#485d93]-500 text-sm">
                <button type="submit"
                    class="text-gray-900 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-e-lg focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    const hamburgerSvg = document.getElementById('hamburger-svg');
    const mobileMenu = document.getElementById('mobile-menu');

    // Toggle menu and icon animation
    hamburgerSvg.addEventListener('click', () => {
        hamburgerSvg.classList.toggle('active');
        mobileMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('flex');
    });



    const userMenuButton = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    userMenuButton.addEventListener('mouseenter', () => {
        userMenu.classList.remove('hidden');
    });
    userMenu.addEventListener('mouseleave', () => {
        userMenu.classList.add('hidden');
    });

</script>