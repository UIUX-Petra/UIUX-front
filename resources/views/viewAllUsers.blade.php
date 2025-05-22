@extends('layout')
@section('content')

<style>
    .titleGradient {
            background: linear-gradient(90deg, #633F92, #7494ec, #5500a4);
            background-size: 200%;
            font-weight: 700;
            -webkit-text-fill-color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .tab-active {
            background-color: var(--accent-tertiary);
            color: var(--text-dark);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .tab-inactive {
            background-color: var(--bg-card);
            color: var(--text-muted);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .tab-inactive:hover {
            border-color: var(--bg-primary);
            color: var(--accent-tertiary);
        }

        .user-card {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            transition: all 0.3s ease;
            border-radius: 25px;
        }

        .user-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            border-color: var(--accent-tertiary);
        }

        .search-bar {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .search-bar:focus-within {
            border-color: var(---bg-primary);
            box-shadow: 0 0 0 2px rgba(99, 63, 146, 0.15);
        }

        .search-bar input {
            background-color: transparent;
            color: var(--text-primary);
        }

        .search-bar input::placeholder {
            color: var(--text-secondary);
        }

        .recommended-user {
            background-color: var(--bg-card);
            transition: all 0.3s ease;
            border: 1px solid rgba(99, 63, 146, 0.3);
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }

        .recommended-user:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(99, 63, 146, 0.2);
            border-color: var(--primary);
        }

        .badge {
            background-color: var(--bg-shadow);
            color: var(--text-primary);
            font-weight: 500;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.7rem;
        }

        .welcome-container {
            border-radius: 16px;
            border-left: 4px solid var(--primary);
        }

        .crown-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #FFC107;
            color: #5a3e00;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
</style>
@include('partials.nav')
    <div class="w-full rounded-lg p-6 px-6 max-w-5xl items-start justify-start my-6 welcome-container">
        <h1 class="cal-sans-regular lg:text-3xl text-2xl mb-2 welcome">Informates</h1>
        <p class="text-[var(--text-secondary)] text-md pl-0.5 font-regular">
            Connect with fellow students from Informatics, Business Information Systems, and Data Science & Analytics at Petra Christian University.
        </p>
    </div>

    {{-- <div class="max-w-5xl items-start justify-start px-6">
        <!-- Recommended Users -->
        @if(isset($recommended) && count($recommended) > 0)
            <div class="mb-12 items-start justify-start">
                <h2 class="titleGradient text-2xl font-semibold mb-6 text-start">Recommended For You</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5">
                    @foreach ($recommended as $user)
                        <div class="recommended-user flex flex-col items-center justify-center rounded-xl py-6 px-4 relative">
                            <div class="crown-badge">
                                <i class="fa-solid fa-crown text-sm"></i>
                            </div>
                            <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                alt="Profile Picture" class="w-16 h-16 rounded-full object-cover mb-3 border-2 border-[var(--primary)]">
                            <h3 class="font-semibold text-center">
                                <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                    class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                            </h3>
                            <p class="text-[0.75rem] mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-star text-yellow-500"></i>
                                <span class="text-[var(--text-secondary)]">{{ $user['reputation'] }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif --}}

        <!-- Search and Tabs -->
        <div class="w-full rounded-lg p-6 px-6 max-w-5xl items-start justify-start mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <!-- Search Bar -->
                <div class="search-bar flex items-center px-4 py-3 shadow-sm w-full md:w-auto md:flex-1 max-w-md">
                    <i class="fa-solid fa-magnifying-glass text-[var(--text-secondary)] mr-3"></i>
                    <input id="searchInput" type="text" placeholder="Search users..."
                        class="w-full outline-none" oninput="searchInput()">
                </div>

                <!-- Tabs -->
                <div class="flex flex-wrap gap-3">
                    <button onclick="showTab('reputations')" id="tab-reputations" class="tab-active">
                        <i class="fa-solid fa-trophy mr-2"></i>Reputations
                    </button>
                    <button onclick="showTab('new-users')" id="tab-new-users" class="tab-inactive">
                        <i class="fa-solid fa-user-plus mr-2"></i>New Users
                    </button>
                    <button onclick="showTab('voters')" id="tab-voters" class="tab-inactive">
                        <i class="fa-solid fa-thumbs-up mr-2"></i>Voters
                    </button>
                </div>
            </div>

            <!-- User Lists -->
            <div class="user-lists">
                <!-- Reputations Tab -->
                <div id="reputations" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="reputationResult">
                        @foreach ($order_by_reputation as $user)
                            <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border-2 border-[var(--accent-tertiary)]">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-star text-yellow-500"></i>
                                        <span class="text-[var(--text-secondary)]">{{ $user['reputation'] }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <span class="badge">php</span>
                                        <span class="badge">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- New Users Tab -->
                <div id="new-users" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="newestResult">
                        @foreach ($order_by_newest as $user)
                            <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border-2 border-[var(--accent-tertiary)]">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-secondary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-calendar-days text-[var(--primary)]"></i>
                                        <span class="text-[var(--primary)]">Since {{ $user['created_at'] }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <span class="badge">php</span>
                                        <span class="badge">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Voters Tab -->
                <div id="voters" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="voterResult">
                        @foreach ($order_by_vote as $user)
                            <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border-2 border-[var(--accent-tertiary)]">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-thumbs-up text-[var(--primary)]"></i>
                                        <span class="text-[var(--text-secondary)]">{{ $user['vote_count'] }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <span class="badge">react</span>
                                        <span class="badge">vue</span>
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
                        <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                            <img src="${user.image ? `storage/${user.image}` : 'https://via.placeholder.com/50'}" 
                                alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">
                                    <a href="${baseUrl.replace(':email', user.email)}" 
                                        class="hover:underline text-[var(--text-primary)]">${user.username}</a>
                                </h3>
                                <p class="text-sm flex items-center gap-1 mt-1">
                                    <i class="fa-solid fa-star text-yellow-500"></i>
                                    <span class="text-[var(--text-secondary)]">${user.reputation}</span>
                                </p>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <span class="badge">php</span>
                                    <span class="badge">java</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else if (searchSwitch === 2) {
                    const resultsDiv = document.getElementById('newestResult');
                    const results = newestUserTrie.search(input);
                    const matchingUsers = byNewest.filter(user => results.includes(user.username.toLowerCase()));

                    resultsDiv.innerHTML = matchingUsers.map(user => `
                        <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                            <img src="${user.image ? `storage/${user.image}` : 'https://via.placeholder.com/50'}" 
                                alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">
                                    <a href="${baseUrl.replace(':email', user.email)}" 
                                        class="hover:underline text-[var(--text-primary)]">${user.username}</a>
                                </h3>
                                <p class="text-sm flex items-center gap-1 mt-1">
                                    <i class="fa-solid fa-calendar-days text-[var(--primary)]"></i>
                                    <span class="text-[var(--primary)]">Since ${user.created_at}</span>
                                </p>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <span class="badge">php</span>
                                    <span class="badge">java</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else if (searchSwitch === 3) {
                    const resultsDiv = document.getElementById('voterResult');
                    const results = voterTrie.search(input);
                    const matchingUsers = byVote.filter(user => results.includes(user.username.toLowerCase()));

                    resultsDiv.innerHTML = matchingUsers.map(user => `
                        <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                            <img src="${user.image ? `storage/${user.image}` : 'https://via.placeholder.com/50'}" 
                                alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg">
                                    <a href="${baseUrl.replace(':email', user.email)}" 
                                        class="hover:underline text-[var(--text-primary)]">${user.username}</a>
                                </h3>
                                <p class="text-sm flex items-center gap-1 mt-1">
                                    <i class="fa-solid fa-thumbs-up text-[var(--primary)]"></i>
                                    <span class="text-[var(--text-secondary)]">${user.vote_count}</span>
                                </p>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <span class="badge">react</span>
                                    <span class="badge">vue</span>
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
                            <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-star text-yellow-500"></i>
                                        <span class="text-[var(--text-secondary)]">{{ $user['reputation'] }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <span class="badge">php</span>
                                        <span class="badge">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    `;
                } else if (searchSwitch === 2) {
                    document.getElementById('newestResult').innerHTML = `
                        @foreach ($order_by_newest as $user)
                            <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-calendar-days text-[var(--primary)]"></i>
                                        <span class="text-[var(--primary)]">Since {{ $user['created_at'] }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <span class="badge">php</span>
                                        <span class="badge">java</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    `;
                } else if (searchSwitch === 3) {
                    document.getElementById('voterResult').innerHTML = `
                        @foreach ($order_by_vote as $user)
                            <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/50' }}"
                                    alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">
                                        <a href="{{ route('viewUser', ['email' => $user['email']]) }}"
                                            class="hover:underline text-[var(--text-primary)]">{{ $user['username'] }}</a>
                                    </h3>
                                    <p class="text-sm flex items-center gap-1 mt-1">
                                        <i class="fa-solid fa-thumbs-up text-[var(--primary)]"></i>
                                        <span class="text-[var(--text-secondary)]">{{ $user['vote_count'] }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        <span class="badge">react</span>
                                        <span class="badge">vue</span>
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