@extends('layout')
@section('content')

<style>
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
    <div class="relative bg-transparent rounded-lg p-6 px-8 max-w-5xl justify-start items-start mt-4 mb-2">
        <div class="flex flex-col space-y-6">
            <div class="flex flex-col space-y-4">
                <div class="flex items-center space-x-3">
                    {{-- <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#38A3A5] to-[#80ED99] flex items-center justify-center">
                        <i class="fa-solid fa-users text-white text-xl"></i>
                    </div> --}}
                    <div>
                        <h1 class="cal-sans-regular text-4xl lg:text-5xl bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent leading-tight">
                            Informates
                        </h1>
                        <div class="h-1 w-24 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] rounded-full mt-2"></div>
                    </div>
                </div>
                <p class="text-[var(--text-muted)] text-lg leading-relaxed max-w-3xl">
                    Connect with fellow students from <span class="font-semibold text-[#6bce82]">Informatics</span>, 
                    <span class="font-semibold text-[#57CC99]">Business Information Systems</span>, and 
                    <span class="font-semibold text-[#38A3A5]">Data Science & Analytics</span> at 
                    <span class="font-bold">Petra Christian University</span>.
                </p>
            </div>
        </div>
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
                        <i class="fa-solid fa-thumbs-up mr-2"></i>Votes
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
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=2196F3&color=fff&size=128' }}"
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
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=2196F3&color=fff&size=128' }}"
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
                                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username'] ?? 'User') . '&background=2196F3&color=fff&size=128' }}"
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

    // Data dari PHP (Laravel)
    let byReputation = <?php echo json_encode($order_by_reputation ?? []); ?>;
    let byNewest = <?php echo json_encode($order_by_newest ?? []); ?>;
    let byVote = <?php echo json_encode($order_by_vote ?? []); ?>;

    // Inisialisasi Trie untuk setiap kategori
    const reputationTrie = new Trie(); // Asumsikan Trie sudah didefinisikan
    byReputation.forEach(user => reputationTrie.insert(user.username.toLowerCase()));

    const newestUserTrie = new Trie();
    byNewest.forEach(user => newestUserTrie.insert(user.username.toLowerCase()));

    const voterTrie = new Trie();
    byVote.forEach(user => voterTrie.insert(user.username.toLowerCase()));

    /**
     * Membuat HTML untuk satu kartu pengguna.
     * @param {object} user - Objek pengguna.
     * @param {string} baseUrl - URL dasar untuk profil pengguna.
     * @param {string} tabType - Tipe tab ('reputation', 'newest', 'voter').
     * @returns {string} HTML string untuk kartu pengguna.
     */
    function createUserCardHTML(user, baseUrl, tabType) {
        let detailHTML = '';
        let fallbackImageBgColor = '2196F3'; // Default untuk reputasi
        let badges = ['php', 'java']; // Default badges

        if (tabType === 'reputation') {
            detailHTML = `
                <p class="text-sm flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-star text-yellow-500"></i>
                    <span class="text-[var(--text-secondary)]">${user.reputation || 0}</span>
                </p>`;
        } else if (tabType === 'newest') {
            fallbackImageBgColor = '2196F3'; 
            detailHTML = `
                <p class="text-sm flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-calendar-days text-[var(--primary)]"></i>
                       <span class="text-[var(--primary)]">Since ${user.created_at}</span>
                </p>`;
        } else if (tabType === 'voter') {
            fallbackImageBgColor = '2196F3'; 
            badges = ['react', 'vue']; // Badge berbeda untuk voter
            detailHTML = `
                <p class="text-sm flex items-center gap-1 mt-1">
                    <i class="fa-solid fa-thumbs-up text-[var(--primary)]"></i>
                    <span class="text-[var(--text-secondary)]">${user.vote_count || 0}</span>
                </p>`;
        }

        const imageUrl = user.image
            ? `storage/${user.image}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.username || 'User')}&background=${fallbackImageBgColor}&color=fff&size=128`;

        const badgeHTML = badges.map(badge => `<span class="badge">${badge}</span>`).join('');

        return `
            <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
                <img src="${imageUrl}"
                     alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
                <div class="flex-1">
                    <h3 class="font-semibold text-lg">
                        <a href="${baseUrl.replace(':email', user.email)}"
                           class="hover:underline text-[var(--text-primary)]">${user.username}</a>
                    </h3>
                    ${detailHTML}
                    <div class="flex flex-wrap gap-1 mt-2">
                        ${badgeHTML}
                    </div>
                </div>
            </div>`;
    }

    /**
     * Melakukan pencarian dan memperbarui tampilan daftar pengguna.
     */
    function searchInput() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const baseUrl = "{{ route('viewUser', ['email' => ':email']) }}";

        let usersToDisplay = [];
        let resultsDivId = '';
        let tabType = '';

        if (searchSwitch === 1) {
            resultsDivId = 'reputationResult';
            tabType = 'reputation';
            usersToDisplay = input.length > 0
                ? byReputation.filter(user => reputationTrie.search(input).includes(user.username.toLowerCase()))
                : byReputation;
        } else if (searchSwitch === 2) {
            resultsDivId = 'newestResult';
            tabType = 'newest';
            usersToDisplay = input.length > 0
                ? byNewest.filter(user => newestUserTrie.search(input).includes(user.username.toLowerCase()))
                : byNewest;
        } else if (searchSwitch === 3) {
            resultsDivId = 'voterResult';
            tabType = 'voter';
            usersToDisplay = input.length > 0
                ? byVote.filter(user => voterTrie.search(input).includes(user.username.toLowerCase()))
                : byVote;
        }

        const resultsDiv = document.getElementById(resultsDivId);
        if (resultsDiv) {
            if (usersToDisplay.length > 0) {
                resultsDiv.innerHTML = usersToDisplay.map(user => createUserCardHTML(user, baseUrl, tabType)).join('');
            } else {
                resultsDiv.innerHTML = '<p class="p-4 text-center text-[var(--text-secondary)]">No users found.</p>';
            }
        }
    }

    /**
     * Menampilkan tab yang dipilih dan menyembunyikan yang lain.
     * @param {string} tabId - ID dari elemen KONTEN tab yang akan ditampilkan (e.g., 'reputations').
     */
    function showTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        const selectedTabContent = document.getElementById(tabId);
        if (selectedTabContent) {
            selectedTabContent.classList.remove('hidden');
        }

        if (tabId === 'reputations') {
            searchSwitch = 1;
        } else if (tabId === 'new-users') {
            searchSwitch = 2;
        } else if (tabId === 'voters') { 
            searchSwitch = 3;
        }

        document.querySelectorAll('[id^="tab-"]').forEach(tabButtonElement => {
            if (tabButtonElement.id !== tabId) { 
                 tabButtonElement.className = 'tab-inactive';
            }
        });
        
        // Tombol aktif diasumsikan memiliki ID 'tab-' + ID konten tab.
        // Misalnya, jika tabId (konten) adalah 'reputations', ID tombolnya adalah 'tab-reputations'.
        const activeTabButton = document.getElementById('tab-' + tabId); 
        if (activeTabButton) {
            activeTabButton.className = 'tab-active';
        }
        
        searchInput(); // Muat ulang hasil untuk tab yang aktif
    }

    // Panggil showTab untuk tab default saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        // Ganti 'reputations' dengan ID KONTEN tab default Anda jika berbeda.
        // Ini akan secara otomatis mengaktifkan tombol dengan ID 'tab-reputations'.
        showTab('reputations'); 
    });
</script>
@endsection