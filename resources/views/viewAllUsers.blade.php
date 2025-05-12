@extends('layout')

@section('head')
    <style>
        @keyframes wiggle {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(5px);
            }
        }

        .animate-wiggle {
            animation: wiggle 0.5s ease-in-out infinite;
        }

        .titleTopUser {
            background: linear-gradient(90deg, #633F92, #7494ec, #5500a4, white, #633F92);
            background-size: 400%;
            font-weight: 900 !important;
            word-spacing: 5px;
            -webkit-text-fill-color: transparent;
            -webkit-background-clip: text;
            animation: animateText 30s linear infinite;
        }

        @keyframes animateText {
            0% {
                background-position: 0%;
            }
            100% {
                background-position: 500%;
            }
        }

        .tab-active {
            background-color: var(--primary);
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            transition: all var(--transition-speed);
        }

        .tab-inactive {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border-radius: 5px;
            border: 1px solid var(--primary);
            padding: 10px 20px;
            transition: all var(--transition-speed);
        }

        .user-card {
            background-color: var(--bg-card);
            color: var(--text-primary);
            transition: background-color var(--transition-speed);
            border: 1px solid var(--border-color);
        }

        .search-bar {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed);
        }

        .search-bar input {
            background-color: transparent;
            color: var(--text-primary);
        }

        .search-bar input::placeholder {
            color: var(--text-secondary);
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 5px #fffd44, 0 0 10px #fffd44, 0 0 15px #fffd44;
            }
            50% {
                box-shadow: 0 0 8px #fffd44, 0 0 15px #fffd44, 0 0 20px #fffd44;
            }
        }

        .glowing {
            animation: glow 2s infinite;
        }
    </style>
@endsection

@section('content')
@include('partials.nav')
    <div class="w-full rounded-lg p-6 px-6 max-w-5xl items-start justify-start my-6 welcome-container">
        <h1 class="cal-sans-regular lg:text-3xl text-xl mb-2 welcome">Informates</h1>
        <p class="text-[var(--text-secondary)] text-md pl-0.5 font-regular">
            Connect with fellow students from Informatics, Business Information Systems, and Data Science & Analytics at Petra Christian University.
        </p>
    </div>

    <div class="max-w-5xl items-start justify-start px-6">
        <!-- Recommended Users -->
        @if ($recommended)
            <div class="mb-12 items-start justidy-start">
                <h2 class="titleTopUser text-2xl font-semibold mb-4 text-start">Recommended For You</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                    @foreach ($recommended as $user)
                        <div class="bg-[var(--bg-card)] flex flex-col items-center justify-center rounded-xl py-6 px-4 shadow-lg glowing">
                            <i class="fa-solid fa-crown text-xl text-yellow-500 mb-2"></i>
                            <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                alt="Profile Picture" class="w-16 h-16 rounded-full object-cover mb-3">
                            <h3 class="font-semibold text-center">
                                <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                    class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                            </h3>
                            <p class="text-[0.70rem] text-[var(--text-secondary)]">Reputation: {{ $user['reputation'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Search and Tabs -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <!-- Search Bar -->
                <div class="search-bar flex items-center rounded-lg px-4 py-3 shadow-md w-full md:w-auto md:flex-1 max-w-md">
                    <input id="searchInput" type="text" placeholder="Search users..."
                        class="w-full outline-none" oninput="searchInput()">
                    <button class="text-[var(--primary)] ml-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-3">
                    <button onclick="showTab('reputations')" id="tab-reputations" class="tab-active">Reputations</button>
                    <button onclick="showTab('new-users')" id="tab-new-users" class="tab-inactive">New Users</button>
                    <button onclick="showTab('voters')" id="tab-voters" class="tab-inactive">Voters</button>
                </div>
            </div>

            <!-- User Lists -->
            <div class="user-lists">
                <!-- Reputations Tab -->
                <div id="reputations" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="reputationResult">
                        @foreach ($order_by_reputation as $user)
                            <div class="bg-[var(--bg-card)] user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm text-[var(--text-secondary)]">Reputation: {{ $user['reputation'] }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">php</span>
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- New Users Tab -->
                <div id="new-users" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="newestResult">
                        @foreach ($order_by_newest as $user)
                            <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm text-[var(--primary)]">Since {{ $user['created_at'] }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">php</span>
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Voters Tab -->
                <div id="voters" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="voterResult">
                        @foreach ($order_by_vote as $user)
                            <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm text-[var(--text-secondary)]">Voters: {{ $user['vote_count'] }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">react</span>
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">vue</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('utils.trie')

    <script>
        let searchSwitch = 1; // Switch search based on selected tab

        let byReputation = <?php echo json_encode($order_by_reputation); ?>;
        let byNewest = <?php echo json_encode($order_by_newest); ?>;
        let byVote = <?php echo json_encode($order_by_vote); ?>;

        const reputationTrie = new Trie(); // For Reputations tab
        for (let i = 0; i < byReputation.length; i++) {
            reputationTrie.insert(byReputation[i]['username'].toLowerCase())
        }

        const newestUserTrie = new Trie(); // For New Users tab
        for (let i = 0; i < byNewest.length; i++) {
            newestUserTrie.insert(byNewest[i]['username'].toLowerCase())
        }

        const voterTrie = new Trie(); // For Voters tab
        for (let i = 0; i < byVote.length; i++) {
            voterTrie.insert(byVote[i]['username'].toLowerCase())
        }

        function searchInput() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const baseUrl = "{{ route('viewUser', ['email' => ':email']) }}";

            if (input.length > 0) {
                if (searchSwitch === 1) {
                    const resultsDiv = document.getElementById('reputationResult');
                    const results = reputationTrie.search(input);
                    const matchingUsers = byReputation.filter(user => results.includes(user.username.toLowerCase()));

                    resultsDiv.innerHTML = matchingUsers.map(user => `
                        <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                            <img src="${user.image ? `storage/${user.image}` : 'https://via.placeholder.com/50'}" 
                                alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h3 class="font-semibold">
                                    <a href="${baseUrl.replace(':email', user.email)}" 
                                        class="hover:underline text-[var(--text-primary)]">${user.username}</a>
                                </h3>
                                <p class="text-sm text-[var(--text-secondary)]">Reputation: ${user.reputation}</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">php</span>
                                    <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">java</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else if (searchSwitch === 2) {
                    const resultsDiv = document.getElementById('newestResult');
                    const results = newestUserTrie.search(input);
                    const matchingUsers = byNewest.filter(user => results.includes(user.username.toLowerCase()));

                    resultsDiv.innerHTML = matchingUsers.map(user => `
                        <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                            <img src="${user.image ? `storage/${user.image}` : 'https://via.placeholder.com/50'}" 
                                alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h3 class="font-semibold">
                                    <a href="${baseUrl.replace(':email', user.email)}" 
                                        class="hover:underline text-[var(--text-primary)]">${user.username}</a>
                                </h3>
                                <p class="text-sm text-[var(--primary)]">Since ${user.created_at}</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">php</span>
                                    <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">java</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else if (searchSwitch === 3) {
                    const resultsDiv = document.getElementById('voterResult');
                    const results = voterTrie.search(input);
                    const matchingUsers = byVote.filter(user => results.includes(user.username.toLowerCase()));

                    resultsDiv.innerHTML = matchingUsers.map(user => `
                        <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                            <img src="${user.image ? `storage/${user.image}` : 'https://via.placeholder.com/50'}" 
                                alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-1">
                                <h3 class="font-semibold">
                                    <a href="${baseUrl.replace(':email', user.email)}" 
                                        class="hover:underline text-[var(--text-primary)]">${user.username}</a>
                                </h3>
                                <p class="text-sm text-[var(--text-secondary)]">Voters: ${user.vote_count}</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">react</span>
                                    <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">vue</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            } else {
                // Reset to default views when search input is empty
                if (searchSwitch === 1) {
                    document.getElementById('reputationResult').innerHTML = `
                        @foreach ($order_by_reputation as $user)
                            <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm text-[var(--text-secondary)]">Reputation: {{ $user['reputation'] }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">php</span>
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    `;
                } else if (searchSwitch === 2) {
                    document.getElementById('newestResult').innerHTML = `
                        @foreach ($order_by_newest as $user)
                            <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm text-[var(--primary)]">Since {{ $user['created_at'] }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">php</span>
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    `;
                } else if (searchSwitch === 3) {
                    document.getElementById('voterResult').innerHTML = `
                        @foreach ($order_by_vote as $user)
                            <div class="user-card rounded-xl p-4 shadow-md flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                                <div class="flex-1">
                                    <h3 class="font-semibold">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm text-[var(--text-secondary)]">Voters: {{ $user['vote_count'] }}</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">react</span>
                                        <span class="text-xs bg-[var(--bg-muted)] text-[var(--text-muted)] px-2 py-1 rounded-md">vue</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    `;
                }
            }
        }

        function showTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected tab
            document.getElementById(tab).classList.remove('hidden');

            // Update search switch based on active tab
            if (tab === 'reputations') {
                searchSwitch = 1;
            } else if (tab === 'new-users') {
                searchSwitch = 2;
            } else {
                searchSwitch = 3;
            }

            // Update tab styles
            document.querySelectorAll('[id^="tab-"]').forEach(tabBtn => {
                tabBtn.className = 'tab-inactive';
            });

            document.getElementById('tab-' + tab).className = 'tab-active';
            
            // Refresh search results
            searchInput();
        }
    </script>
@endsection