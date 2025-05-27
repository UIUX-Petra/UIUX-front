@extends('layout')

@section('content')
@include('partials.nav')

    <!-- Decorative background element -->
    <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.2)] to-[rgba(128,237,153,0.2)] blur-2xl">
    </div>

    <div class="max-w-7xl min-h-screen mx-auto z-50 p-8">
        <h1 class="text-center text-[var(--text-primary)] font-bold text-2xl md:text-4xl py-4 md:py-8 uppercase cal-sans-regular">Leaderboard</h1>
        
        <!-- Best user in each tag section -->
        <div class="flex flex-col items-center justify-center">
            <h2 class="text-2xl text-[var(--text-primary)] font-semibold mb-4">BEST USER IN EACH TAGS</h2>
            <select name="tags" id="tags"
                class="my-4 appearance-none border-0 border-b-2 border-current font-bold tracking-widest bg-[var(--bg-card)] focus:outline-none text-[--purple] rounded-lg p-2">
                <option value="" disabled selected class="text-[var(--text-muted)]">Choose one tag you want</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag['id'] }}" class="text-[var(--text-muted)]">{{ $tag['name'] }}</option>
                @endforeach
            </select>
            
            <!-- Card for best user in tag -->
            <div class="card1 mt-4 w-[274px] h-[431px] mx-auto">
                <div class="reveal flex flex-col items-center justify-center p-8" id="reveal-card">
                    <img src="" class="mb-2 w-40 h-40 object-cover rounded-full"
                        id="best-user-image">
                    <h1 class="text-2xl font-semibold text-[var(--text-primary)] text-center" id="best-user-name"></h1>
                </div>
            </div>
        </div>

        <!-- Special person section -->
        <div class="mt-16 flex flex-col items-center justify-center">
            <h2 class="text-2xl font-semibold mb-4 text-[--purple] glowing-text">YOUR SPECIAL PERSON</h2>
            <div class="card-container">
                <div class="card transition-transform hover:scale-105">
                    <div class="front flex flex-col items-center justify-center">
                        <small class="absolute bottom-[10%] text-[var(--text-primary)]">click to open</small>
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
                            <h1 class="text-2xl font-semibold text-[var(--text-primary)]">{{ $mostViewed['username'] }}</h1>
                        @else
                            <h1 class="text-2xl font-semibold text-[var(--text-primary)] text-center">Your special person awaits!</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    <style>
        
        /* Title Styling (Keep the gradient effect) */
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
            0% { background-position: 0%; }
            100% { background-position: 500%; }
        }

        /* Card Styling */
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
            margin: 0 auto; /* Center the card */
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
            background-position: center; /* Center the background image */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
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

        /* Section styling adapted from home page */
        .section-heading {
            position: relative;
            padding-left: 1rem;
            margin-bottom: 1.5rem;
        }

        .section-heading::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: linear-gradient(to bottom, #38A3A5, #80ED99);
            border-radius: 2px;
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

                    fetch(`{{ route('tag.leaderboard',['id'=>'aaa']) }}`.replace('aaa', tagId))
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