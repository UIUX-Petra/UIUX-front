@extends('layout')

@section('content')
@include('partials.nav')
{{-- @include('utils.background-overlay') --}}
    <style>
        /* Title Styling */
        .titleTopUser {
            background: linear-gradient(90deg, #633F92, #7494ec, #5500a4, white, #633F92);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: animateText 30s linear infinite;
            background-size: 400%;
            font-weight: 900;
            word-spacing: 5px;
        }

        @keyframes animateText {
            0% {
                background-position: 0%;
            }

            100% {
                background-position: 500%;
            }
        }

        /* Container for Top 5 Users */
        .topUser-grid {
            display: grid;
            /* grid-template-columns: repeat(auto-fit, minmax(220px, 4fr)); */
            gap: 1rem;
            justify-content: center;
            align-items: stretch;
        }

        /* Card Styling */
        .user-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            /* Make it responsive within the grid */
            width: 150px;
            /* Set a maximum width for cards */
            height: 250px;
            /* Uniform height */
            padding: 1rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        /* Hover Effects */
        .user-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        /* Profile Image */
        .user-card .user-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        /* Text Styling */
        .user-card h3 {
            font-size: 1rem;
            text-align: center;
            margin: 0.5rem 0;
        }

        .user-card p {
            font-size: 0.875rem;
            text-align: center;
            margin: 0;
        }

        .user-card i {
            font-size: 2rem;
            color: #f39c12;
            margin-bottom: 0.5rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .user-card {
                height: 280px;
            }
        }

        @media (max-width: 480px) {
            .user-card {
                height: 200px;
                width: 200px;
            }
        }

        /* Glowing Text Effect */
        .glowing-text {
            font-weight: bold;
            color: #fff;
            text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #633F92, 0 0 20px #633F92, 0 0 25px #633F92, 0 0 30px #633F92, 0 0 35px #633F92;
            animation: glow 1s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #633F92, 0 0 20px #633F92, 0 0 25px #633F92, 0 0 30px #633F92, 0 0 35px #633F92;
            }
            to {
                text-shadow: 0 0 10px #fff, 0 0 20px #633F92, 0 0 30px #633F92, 0 0 40px #633F92, 0 0 50px #633F92, 0 0 60px #633F92, 0 0 70px #633F92;
            }
        }

    </style>

    <style>
        div {
            margin: auto;
        }

        .card-container {
            perspective: 800px;
        }

        .card {
            position: relative;
            width: 262px;
            height: 431px;
            transform-style: preserve-3d;
            transition: transform 0.6s ease-in-out;
            cursor: pointer;
        }

        .card1 {
            position: relative;
            width: 274px;
            height: 431px;
            transition: transform 0.6s ease-in-out;
        }

        .card .front,
        .card .back,
        .card1 .reveal {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
        }

        .card1 .reveal {
            background-image: url("{{ asset('assets/reveal_card.png') }}");
            background-size: cover;
        }

        .card .front {
            background-image: url("{{ asset('assets/back_card.png') }}");
            background-size: cover;
        }

        .card .back {
            background-image: url("{{ asset('assets/front_card.png') }}");
            background-size: cover;
            transform: rotateY(180deg);
        }

        .card.flipped {
            transform: rotateY(180deg);
        }

        /* select {
                                            margin-bottom: 1em;
                                            padding: .25em;
                                            border: 0;
                                            border-bottom: 2px solid currentcolor;
                                            font-weight: bold;
                                            letter-spacing: .15em;
                                            border-radius: 0;

                                            &:focus,
                                            &:active {
                                                outline: 0;
                                                border-bottom-color: red;
                                            }
                                        } */
    </style>
    <div class="max-w-7xl min-h-screen mx-auto z-50 p-8">
        <h1 class="text-center text-white font-bold text-2xl md:text-4xl p-4 md:p-8 uppercase">Leaderboard</h1>
        {{-- each tags best user --}}
        <div class="flex flex-col items-center justify-center">
            <h1 class="text-2xl text-white font-semibold">BEST USER IN EACH TAGS</h1>
            <select name="tags" id="tags"
                class="my-4 appearance-none border-0 border-b-2 border-current font-bold tracking-widest bg-white !focus:outline-none text-[--purple] rounded-lg">
                <option value="" disabled selected class="text-gray-500">Choose one tag you want</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag['id'] }}" class="text-gray-500">{{ $tag['name'] }}</option>
                @endforeach
            </select>
            <div class="card1 mt-4">
                <div class="reveal flex flex-col items-center justify-center p-8" id="reveal-card">
                    <img src="" class="mb-2 w-40 h-40 object-cover rounded-full"
                        id="best-user-image">
                    <h1 class="text-2xl font-semibold text-white text-center" id="best-user-name"></h1>

                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col items-center justify-center">
            <h1 class="text-2xl font-semibold text-[--purple] mb-4 glowing-text">YOUR SPECIAL PERSON</h1>
            <div class="card-container">
                <div class="card">
                    <div class="front flex flex-col items-center justify-center">
                        <small class="absolute bottom-[10%] text-white">click to open</small>

                    </div>
                    <div class="back relative flex flex-col items-center justify-center">
                        @if ($mostViewed)
                            @if ($mostViewed['image'])
                                <img src="{{ asset('storage/' . $mostViewed['image']) }}" alt="front"
                                    class="mb-2 w-40 h-40 object-cover rounded-full">
                            @else
                                <img src="{{ asset('assets/empty.jpg') }}" alt="front"
                                    class="mb-2 w-40 h-40 object-cover rounded-full">
                            @endif
                            <h1 class="text-2xl font-semibold text-white">{{ $mostViewed['username'] }}</h1>
                        @else
                            <h1 class="text-2xl font-semibold text-white text-center">Your special person awaits!</h1>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('tags');
            const bestUserImage = document.getElementById('best-user-image');
            const bestUserName = document.getElementById('best-user-name');
            const revealCard = document.getElementById('reveal-card');

            selectElement.addEventListener('change', function() {
                const tagId = this.value;

                if (tagId) {
                    bestUserName.textContent = 'Loading...';
                    revealCard.style.backgroundImage = 'url("{{ asset('assets/loading.png') }}")';

                    fetch(`/getTagLeaderboard/${tagId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (Array.isArray(data) && data.length > 0) {
                                const topUser = data[0];
                                if(topUser.profile_picture){
                                    bestUserImage.src = topUser.profile_picture;
                                } else {
                                    bestUserImage.src = "{{ asset('assets/empty.jpg') }}";
                                }
                                bestUserName.textContent = topUser.username || 'Best User';
                                revealCard.style.backgroundImage =
                                    `url({{ asset('assets/purple_card.png') }})`;
                            } else {
                                bestUserImage.src = "{{ asset('assets/empty.jpg') }}";
                                bestUserName.textContent = 'There is no best user for this tag yet!';
                                revealCard.style.backgroundImage =
                                    `url({{ asset('assets/blue_card.png') }})`;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching leaderboard:', error);
                            bestUserImage.src = "{{ asset('assets/empty.jpg') }}";
                            bestUserName.textContent = 'Error Loading User';
                            revealCard.style.backgroundImage =
                                'url("{{ asset('assets/reveal_card.png') }}")';
                        });
                } else {
                    // Reset to default state if no tag is selected
                    bestUserImage.src = "{{ asset('assets/empty.jpg') }}";
                    bestUserName.textContent = '';
                    revealCard.style.backgroundImage = 'url("{{ asset('assets/reveal_card.png') }}")';
                }
            });

            const card = document.querySelector('.card');

            card.addEventListener('click', function() {
                card.classList.toggle('flipped');
            });
        });
    </script>
@endsection
