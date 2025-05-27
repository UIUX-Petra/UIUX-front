@extends('layout')

@section('content')
@include('partials.nav')

<style>
    /* Main styling consistent with home page */
    .main-content {
        background-color: var(--bg-secondary);
    }

    .leaderboard-card {
        border: 1px solid var(--border-color);
        background-color: var(--bg-card);
        transition: all 0.3s ease;
    }

    .leaderboard-card:hover {
        border-color: var(--accent-tertiary);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* User profile card styling */
    .user-profile-card {
        background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-card-hover) 100%);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .user-profile-card.loading {
        background: linear-gradient(135deg, 
            rgba(128, 237, 153, 0.1) 0%, 
            rgba(56, 163, 165, 0.1) 100%);
        border-color: var(--accent-primary);
    }

    .user-profile-card.no-user {
        background: linear-gradient(135deg, 
            rgba(156, 163, 175, 0.1) 0%, 
            rgba(107, 114, 128, 0.1) 100%);
        border-color: var(--text-muted);
    }

    .user-profile-card.best-user {
        background: linear-gradient(135deg, 
            rgba(147, 51, 234, 0.1) 0%, 
            rgba(168, 85, 247, 0.1) 100%);
        border-color: #9333ea;
        box-shadow: 0 0 20px rgba(147, 51, 234, 0.2);
    }

    /* Section heading styling from home page */
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

    /* Custom select styling */
    .custom-select {
        background-color: var(--bg-card);
        border: 2px solid var(--border-color);
        color: var(--text-primary);
        transition: all 0.3s ease;
    }

    .custom-select:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 3px rgba(56, 163, 165, 0.1);
        outline: none;
    }

    .custom-select option {
        background-color: var(--bg-card);
        color: var(--text-primary);
    }

    /* Flip card animation */
    .flip-card {
        perspective: 1000px;
    }

    .flip-card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.8s;
        transform-style: preserve-3d;
        cursor: pointer;
    }

    .flip-card.flipped .flip-card-inner {
        transform: rotateY(180deg);
    }

    .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .flip-card-back {
        transform: rotateY(180deg);
    }

    /* Glowing text effect */
    .glowing-text {
        background: linear-gradient(45deg, #38A3A5, #80ED99, #57CC99);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 0 10px rgba(56, 163, 165, 0.3));
        animation: glow-pulse 2s ease-in-out infinite alternate;
    }

    @keyframes glow-pulse {
        from {
            filter: drop-shadow(0 0 10px rgba(56, 163, 165, 0.3));
        }
        to {
            filter: drop-shadow(0 0 20px rgba(56, 163, 165, 0.6));
        }
    }

    /* Loading animation */
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }

    .loading-shimmer {
        background: linear-gradient(90deg, transparent, rgba(56, 163, 165, 0.2), transparent);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    /* Decorative elements */
    .decorative-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.1;
        z-index: -1;
    }

    .decorative-blob-1 {
        top: 10%;
        right: 10%;
        width: 200px;
        height: 200px;
        background: linear-gradient(45deg, #38A3A5, #80ED99);
    }

    .decorative-blob-2 {
        bottom: 20%;
        left: 10%;
        width: 300px;
        height: 300px;
        background: linear-gradient(45deg, #80ED99, #57CC99);
    }
</style>

<!-- Decorative background element -->
<div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.2)] to-[rgba(128,237,153,0.2)] blur-2xl"></div>

<!-- Decorative blobs -->
<div class="decorative-blob decorative-blob-1"></div>
<div class="decorative-blob decorative-blob-2"></div>

<div class="max-w-5xl min-h-screen justify-start items-start z-10 p-8 relative">
    <!-- Page Title -->
    <div class="mb-12">
        <h1 class="cal-sans-regular text-4xl md:text-6xl font-bold mb-4 bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent">
            Leaderboard
        </h1>
        <p class="text-[var(--text-muted)] text-lg max-w-2xl">
            Discover the top contributors in each tag and find your special connection
        </p>
    </div>
    
    <!-- Best user in each tag section -->
    <div class="max-w-4xl justify-start items-start mb-16">
        <h2 class="section-heading text-2xl md:text-3xl text-[var(--text-primary)] font-semibold mb-8">
            Top Contributors by Tag
        </h2>
        
        <div class="flex flex-col space-y-6">
            <!-- Tag Selection -->
            <div class="w-full max-w-sm">
                <label for="tags" class="block text-sm font-medium text-[var(--text-secondary)] mb-4">
                    Select a tag to see the top contributor
                </label>
                <select name="tags" id="tags" class="custom-select w-full px-4 py-3 rounded-lg font-medium text-center">
                    <option value="" disabled selected>Choose a tag...</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag['id'] }}">{{ $tag['name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- User Profile Card -->
            <div class="user-profile-card w-full max-w-sm h-80 rounded-xl p-8 flex flex-col items-center justify-center relative overflow-hidden transition-all duration-500" id="user-profile-card">
                <!-- Default State -->
                <div id="default-state" class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--accent-primary)] to-[var(--accent-secondary)] flex items-center justify-center">
                        <i class="fa-solid fa-crown text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-2">Select a Tag</h3>
                    <p class="text-sm text-[var(--text-muted)]">Choose a tag above to see the top contributor</p>
                </div>
                
                <!-- Loading State -->
                <div id="loading-state" class="text-center hidden">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-[var(--bg-secondary)] loading-shimmer"></div>
                    <div class="w-32 h-4 mx-auto mb-2 bg-[var(--bg-secondary)] rounded loading-shimmer"></div>
                    <div class="w-20 h-3 mx-auto bg-[var(--bg-secondary)] rounded loading-shimmer"></div>
                </div>
                
                <!-- User Display -->
                <div id="user-display" class="text-center hidden">
                    <img src="" class="w-24 h-24 mx-auto mb-4 object-cover rounded-full border-4 border-white shadow-lg" id="best-user-image" alt="User Avatar">
                    <h3 class="text-xl font-bold text-[var(--text-primary)] mb-1" id="best-user-name"></h3>
                    <p class="text-sm text-[var(--text-secondary)]">Top Contributor</p>
                </div>
                
                <!-- No User State -->
                <div id="no-user-state" class="text-center hidden">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-[var(--text-muted)] opacity-50 flex items-center justify-center">
                        <i class="fa-solid fa-user-slash text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[var(--text-primary)] mb-2">No Contributors Yet</h3>
                    <p class="text-sm text-[var(--text-muted)]">Be the first to contribute to this tag!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Special person section -->
    <div class="max-w-4xljustify-start items-start">
        <h2 class="section-heading text-2xl md:text-3xl font-semibold mb-8 glowing-text">
            Your Special Person
        </h2>
        
        <div class="flex justify-start">
            <div class="flip-card w-full max-w-sm h-80" id="special-person-card">
                <div class="flip-card-inner">
                    <!-- Front of card -->
                    <div class="flip-card-front leaderboard-card">
                        <div class="absolute inset-0 bg-gradient-to-br from-[rgba(56,163,165,0.1)] to-[rgba(128,237,153,0.1)] rounded-xl"></div>
                        <div class="relative z-10 p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-gradient-to-br from-[var(--accent-primary)] to-[var(--accent-secondary)] flex items-center justify-center animate-pulse">
                                <i class="fa-solid fa-heart text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[var(--text-primary)] mb-4">Discover Your Match</h3>
                            <p class="text-sm text-[var(--text-muted)] mb-6">Someone special is waiting to be revealed</p>
                            <div class="inline-flex items-center text-xs text-[var(--accent-primary)] font-medium">
                                <i class="fa-solid fa-mouse-pointer mr-2"></i>
                                Click to reveal
                            </div>
                        </div>
                    </div>
                    
                    <!-- Back of card -->
                    <div class="flip-card-back leaderboard-card">
                        <div class="absolute inset-0 bg-gradient-to-br from-[rgba(147,51,234,0.1)] to-[rgba(168,85,247,0.1)] rounded-xl"></div>
                        <div class="relative z-10 p-8 text-center">
                            @if ($mostViewed)
                                @if ($mostViewed['image'])
                                    <img src="{{ asset('storage/' . $mostViewed['image']) }}" alt="Special Person" 
                                         class="w-24 h-24 mx-auto mb-4 object-cover rounded-full border-4 border-white shadow-lg">
                                @else
                                    <img src="{{ asset('assets/empty.jpg') }}" alt="Special Person" 
                                         class="w-24 h-24 mx-auto mb-4 object-cover rounded-full border-4 border-white shadow-lg">
                                @endif
                                <h3 class="text-xl font-bold text-[var(--text-primary)] mb-2">{{ $mostViewed['username'] }}</h3>
                                <p class="text-sm text-[var(--accent-secondary)] font-medium">Your Special Person</p>
                                <div class="mt-4 flex items-center justify-center text-xs text-[var(--text-muted)]">
                                    <i class="fa-solid fa-eye mr-1"></i>
                                    Most viewed by you
                                </div>
                            @else
                                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-[var(--text-muted)] to-[var(--bg-secondary)] flex items-center justify-center opacity-50">
                                    <i class="fa-solid fa-heart-crack text-2xl text-[var(--text-muted)]"></i>
                                </div>
                                <h3 class="text-xl font-bold text-[var(--text-primary)] mb-2">No Special Person Yet</h3>
                                <p class="text-sm text-[var(--text-muted)] text-center leading-relaxed">
                                    Start exploring profiles to find your special match!
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('tags');
        const userProfileCard = document.getElementById('user-profile-card');
        const defaultState = document.getElementById('default-state');
        const loadingState = document.getElementById('loading-state');
        const userDisplay = document.getElementById('user-display');
        const noUserState = document.getElementById('no-user-state');
        const bestUserImage = document.getElementById('best-user-image');
        const bestUserName = document.getElementById('best-user-name');

        function showState(state) {
            // Hide all states
            defaultState.classList.add('hidden');
            loadingState.classList.add('hidden');
            userDisplay.classList.add('hidden');
            noUserState.classList.add('hidden');
            
            // Reset card classes
            userProfileCard.className = 'user-profile-card w-full max-w-sm h-80 rounded-xl p-8 flex flex-col items-center justify-center relative overflow-hidden transition-all duration-500';
            
            // Show selected state
            switch(state) {
                case 'default':
                    defaultState.classList.remove('hidden');
                    break;
                case 'loading':
                    loadingState.classList.remove('hidden');
                    userProfileCard.classList.add('loading');
                    break;
                case 'user':
                    userDisplay.classList.remove('hidden');
                    userProfileCard.classList.add('best-user');
                    break;
                case 'no-user':
                    noUserState.classList.remove('hidden');
                    userProfileCard.classList.add('no-user');
                    break;
            }
        }

        selectElement.addEventListener('change', function() {
            const tagId = this.value;

            if (tagId) {
                showState('loading');

                fetch(`/getTagLeaderboard/${tagId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        setTimeout(() => { // Add slight delay for better UX
                            if (Array.isArray(data) && data.length > 0) {
                                const topUser = data[0];
                                if(topUser.profile_picture){
                                    bestUserImage.src = topUser.profile_picture;
                                } else {
                                    bestUserImage.src = "{{ asset('assets/empty.jpg') }}";
                                }
                                bestUserName.textContent = topUser.username || 'Top Contributor';
                                showState('user');
                            } else {
                                showState('no-user');
                            }
                        }, 500);
                    })
                    .catch(error => {
                        console.error('Error fetching leaderboard:', error);
                        setTimeout(() => {
                            showState('no-user');
                        }, 500);
                    });
            } else {
                showState('default');
            }
        });

        // Special person card flip functionality
        const specialPersonCard = document.getElementById('special-person-card');
        if (specialPersonCard) {
            specialPersonCard.addEventListener('click', function() {
                this.classList.toggle('flipped');
            });
        }
    });
</script>

@endsection