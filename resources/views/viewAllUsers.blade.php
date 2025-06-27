@extends('layout')
@section('content')

<style>
    /* User list header */
    .page-header h1 {
        background: -webkit-linear-gradient(120deg, #38A3A5, #80ED99);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .page-header .header-underline {
        height: 3px;
        width: 80px;
        background: linear-gradient(120deg, #38A3A5, #80ED99);
        border-radius: 99px;
    }

    /* Tab Interface (Reusing the modern style from homepage) */
    .tabs-container {
        border-bottom: 1px solid var(--border-color);
    }
    .tab-item {
        padding: 0.5rem 0.25rem;
        margin-bottom: -1px; /* Overlap the container border */
        border-bottom: 2px solid transparent; /* Placeholder for spacing */
        color: var(--text-secondary);
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }
    .tab-item:not(.active):hover {
        color: var(--text-primary);
        border-bottom-color: var(--border-color);
    }
    .tab-item.active {
        color: var(--text-primary);
        font-weight: 600;
        border-bottom-color: var(--accent-tertiary);
    }
</style>
@include('partials.nav')
    <div class="page-header relative p-6 px-8 max-w-5xl justify-start items-start mt-4 mb-2">
        <div class="flex flex-col space-y-3">
            <h1 class="cal-sans-regular text-4xl lg:text-5xl">Informates</h1>
            <div class="header-underline"></div>
            <p class="text-[var(--text-muted)] text-lg leading-relaxed max-w-3xl pt-2">
                Connect with fellow students from <span class="font-semibold text-[#6bce82]">Informatics</span>, 
                <span class="font-semibold text-[#57CC99]">Business Information Systems</span>, and 
                <span class="font-semibold text-[#38A3A5]">Data Science & Analytics</span> at 
                <span class="font-bold">Petra Christian University</span>.
            </p>
        </div>
    </div>

<div class="w-full rounded-lg px-6 max-w-5xl items-start justify-start mb-8">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div class="tabs-container flex flex-wrap gap-x-6">
            <button data-tab-target="reputations" class="tab-item active">
                <i class="fa-solid fa-trophy mr-2"></i>Reputation
            </button>
            <button data-tab-target="new-users" class="tab-item">
                <i class="fa-solid fa-user-plus mr-2"></i>New Users
            </button>
            <button data-tab-target="voters" class="tab-item">
                <i class="fa-solid fa-thumbs-up mr-2"></i>Top Voted
            </button>
        </div>
        {{-- <div class="search-bar ..."> ... </div> --}}
    </div>

    <div class="user-lists">
        <div id="reputations" class="tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($order_by_reputation as $user)
                    @include('partials.user_card', ['user' => $user, 'type' => 'reputation'])
                @endforeach
            </div>
        </div>
        <div id="new-users" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($order_by_newest as $user)
                     @include('partials.user_card', ['user' => $user, 'type' => 'newest'])
                @endforeach
            </div>
        </div>
        <div id="voters" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($order_by_vote as $user)
                    @include('partials.user_card', ['user' => $user, 'type' => 'voter'])
                @endforeach
            </div>
        </div>
    </div>
</div>

    {{-- @include('utils.trie') --}}

    <script>
    // let searchSwitch = 1; // Switch search based on selected tab

    // Data dari PHP (Laravel)
    // let byReputation = <?php echo json_encode($order_by_reputation ?? []); ?>;
    // let byNewest = <?php echo json_encode($order_by_newest ?? []); ?>;
    // let byVote = <?php echo json_encode($order_by_vote ?? []); ?>;

    // Inisialisasi Trie untuk setiap kategori
    // const reputationTrie = new Trie(); // Asumsikan Trie sudah didefinisikan
    // byReputation.forEach(user => reputationTrie.insert(user.username.toLowerCase()));

    // const newestUserTrie = new Trie();
    // byNewest.forEach(user => newestUserTrie.insert(user.username.toLowerCase()));

    // const voterTrie = new Trie();
    // byVote.forEach(user => voterTrie.insert(user.username.toLowerCase()));

    /**
     * Membuat HTML untuk satu kartu pengguna.
     * @param {object} user - Objek pengguna.
     * @param {string} baseUrl - URL dasar untuk profil pengguna.
     * @param {string} tabType - Tipe tab ('reputation', 'newest', 'voter').
     * @returns {string} HTML string untuk kartu pengguna.
     */
    // function createUserCardHTML(user, baseUrl, tabType) {
    //     let detailHTML = '';
    //     let fallbackImageBgColor = '2196F3'; // Default untuk reputasi
    //     let badges = ['php', 'java']; // Default badges

    //     if (tabType === 'reputation') {
    //         detailHTML = `
    //             <p class="text-sm flex items-center gap-1 mt-1">
    //                 <i class="fa-solid fa-star text-yellow-500"></i>
    //                 <span class="text-[var(--text-secondary)]">${user.reputation || 0}</span>
    //             </p>`;
    //     } else if (tabType === 'newest') {
    //         fallbackImageBgColor = '2196F3'; 
    //         detailHTML = `
    //             <p class="text-sm flex items-center gap-1 mt-1">
    //                 <i class="fa-solid fa-calendar-days text-[var(--primary)]"></i>
    //                    <span class="text-[var(--primary)]">Since ${user.created_at}</span>
    //             </p>`;
    //     } else if (tabType === 'voter') {
    //         fallbackImageBgColor = '2196F3'; 
    //         badges = ['react', 'vue']; // Badge berbeda untuk voter
    //         detailHTML = `
    //             <p class="text-sm flex items-center gap-1 mt-1">
    //                 <i class="fa-solid fa-thumbs-up text-[var(--primary)]"></i>
    //                 <span class="text-[var(--text-secondary)]">${user.vote_count || 0}</span>
    //             </p>`;
    //     }

    //     const imageUrl = user.image
    //         ? `storage/${user.image}`
    //         : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.username || 'User')}&background=${fallbackImageBgColor}&color=fff&size=128`;

    //     const badgeHTML = badges.map(badge => `<span class="badge">${badge}</span>`).join('');

    //     return `
    //         <div class="user-card border border-[var(--border-color)] bg-[var(--bg-card)] p-4 shadow-sm flex items-center gap-4">
    //             <img src="${imageUrl}"
    //                  alt="Profile Picture" class="w-14 h-14 rounded-full object-cover border border-[var(--border-color)]">
    //             <div class="flex-1">
    //                 <h3 class="font-semibold text-lg">
    //                     <a href="${baseUrl.replace(':email', user.email)}"
    //                        class="hover:underline text-[var(--text-primary)]">${user.username}</a>
    //                 </h3>
    //                 ${detailHTML}
    //                 <div class="flex flex-wrap gap-1 mt-2">
    //                     ${badgeHTML}
    //                 </div>
    //             </div>
    //         </div>`;
    // }

    /**
     * Melakukan pencarian dan memperbarui tampilan daftar pengguna.
     */
    // function searchInput() {
    //     const input = document.getElementById('searchInput').value.toLowerCase();
    //     const baseUrl = "{{ route('viewUser', ['email' => ':email']) }}";

    //     let usersToDisplay = [];
    //     let resultsDivId = '';
    //     let tabType = '';

    //     if (searchSwitch === 1) {
    //         resultsDivId = 'reputationResult';
    //         tabType = 'reputation';
    //         usersToDisplay = input.length > 0
    //             ? byReputation.filter(user => reputationTrie.search(input).includes(user.username.toLowerCase()))
    //             : byReputation;
    //     } else if (searchSwitch === 2) {
    //         resultsDivId = 'newestResult';
    //         tabType = 'newest';
    //         usersToDisplay = input.length > 0
    //             ? byNewest.filter(user => newestUserTrie.search(input).includes(user.username.toLowerCase()))
    //             : byNewest;
    //     } else if (searchSwitch === 3) {
    //         resultsDivId = 'voterResult';
    //         tabType = 'voter';
    //         usersToDisplay = input.length > 0
    //             ? byVote.filter(user => voterTrie.search(input).includes(user.username.toLowerCase()))
    //             : byVote;
    //     }

    //     const resultsDiv = document.getElementById(resultsDivId);
    //     if (resultsDiv) {
    //         if (usersToDisplay.length > 0) {
    //             resultsDiv.innerHTML = usersToDisplay.map(user => createUserCardHTML(user, baseUrl, tabType)).join('');
    //         } else {
    //             resultsDiv.innerHTML = '<p class="p-4 text-center text-[var(--text-secondary)]">No users found.</p>';
    //         }
    //     }
    // }

    /**
     * Menampilkan tab yang dipilih dan menyembunyikan yang lain.
     * @param {string} tabId - ID dari elemen KONTEN tab yang akan ditampilkan (e.g., 'reputations').
     */

document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.tab-item');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            tabs.forEach(item => item.classList.remove('active'));
            // Add active class to the clicked tab
            tab.classList.add('active');

            const targetId = tab.dataset.tabTarget;
            
            // Hide all tab content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show the target tab content
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });

    // Set the initial active tab (optional, defaults to first)
    const initialActiveTab = document.querySelector('.tab-item.active');
    if (initialActiveTab) {
        const initialContent = document.getElementById(initialActiveTab.dataset.tabTarget);
        if (initialContent) {
            tabContents.forEach(content => content.classList.add('hidden'));
            initialContent.classList.remove('hidden');
        }
    }
});
</script>
@endsection