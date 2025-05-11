@extends('layout')
@section('content')
    @include('partials.nav')
    @include('utils.background3')
    <div class="text-gray-900 min-h-screen p-6 sm:p-10 mx-auto">
        <!-- Main Content -->
        <div class="w-full bg-white rounded-lg p-6 shadow-lg">
            <!-- Profile Header -->
            <div class="flex flex-col sm:flex-row items-center sm:space-x-4">
                <img src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/100' }}"
                    alt="Profile Picture" class="w-20 h-20 rounded-full mb-4 sm:mb-0">
                <div>
                    <h2 class="text-[#7494ec] text-2xl font-bold text-center sm:text-left">{{ $user['username'] }}</h2>
                </div>
            </div>

            <!-- Edit Profile and Followers -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center mt-6">
                <!-- Followers Count -->
                <div class="text-center">
                    <h3 id="countFollowers" class="text-[#7494ec] text-lg font-bold">
                        {{ $countFollowers }}
                    </h3>
                    <p class="text-gray-500 text-sm">Followers</p>
                </div>
                <div class="text-center sm:text-right">
                    <button id="followBtn" onclick="follow()"
                        class="px-4 py-2 bg-[#7494ec] text-white rounded-lg hover:bg-[#5f83c8] transition">
                        @if ($isFollowing)
                            Unfollow
                        @else
                            Follow
                        @endif
                    </button>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-8">
                <!-- Stats Section -->
                <div class="col-span-1 lg:col-span-3">
                    <div class="space-y-4">
                        <div class="border border-[#7494ec] shadow-lg p-4 rounded-lg bg-white">
                            <h2 class="text-[#7494ec] text-lg font-bold">Stats</h2>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div class="text-[#7494ec]">Reputations</div>
                            </div>
                        </div>
                         <!-- Question Section -->
                         <div class="border border-[#7494ec] shadow-lg p-4 rounded-lg bg-white">
                            <h2 class="text-[#7494ec] text-lg font-bold hover:underline">
                                <a href="{{ route('user.questions.list', ['id' => $user['id']]) }}">Questions</a>
                            </h2>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div class="text-[#7494ec]">
                                    {{ $user['questions_count'] ?? (isset($user['question']) && is_array($user['question']) ? count($user['question']) : 0) }} Questions
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About and Other Sections -->
                <div class="col-span-1 lg:col-span-9">
                    <!-- About Section -->
                    <div>
                        <h3 class="text-[#7494ec] text-xl font-bold">About</h3>
                        <div class="mt-3 flex flex-wrap gap-2 text-white">
                            <span class="px-3 py-1 bg-[#7494ec] text-sm rounded">angular</span>
                            <span class="px-3 py-1 bg-[#7494ec] text-sm rounded">html</span>
                            <span class="px-3 py-1 bg-[#7494ec] text-sm rounded">css</span>
                            <span class="px-3 py-1 bg-[#7494ec] text-sm rounded">javascript</span>
                        </div>
                    </div>

                    <!-- Achievements Section -->
                    <div class="border border-[#7494ec] shadow-lg p-4 rounded-lg bg-white mt-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-[#7494ec] text-lg font-bold">Achievements</h2>
                            <a href="#" class="text-[#633F92] text-sm hover:underline">View All</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div class="bg-[#7494ec] rounded p-4 text-center">
                                <i class="fa-solid fa-medal text-4xl text-[#FEE101] mb-2"></i>
                                <h4 class="text-white text-lg font-bold">5</h4>
                                <p class="text-white text-xs">Gold Achievements</p>
                            </div>
                            <div class="bg-[#633F92] rounded p-4 text-center">
                                <i class="fa-solid fa-medal text-4xl text-[#D7D7D7] mb-2"></i>
                                <h4 class="text-white text-lg font-bold">5</h4>
                                <p class="text-white text-xs">Silver Achievements</p>
                            </div>
                            <div class="bg-[#7494ec] rounded p-4 text-center">
                                <i class="fa-solid fa-medal text-4xl text-[#824A02] mb-2"></i>
                                <h4 class="text-white text-lg font-bold">5</h4>
                                <p class="text-white text-xs">Bronze Achievements</p>
                            </div>
                        </div>
                    </div>

                    <!-- Top Tags Section -->
                    <div class="border-2 border-[#7494ec] shadow-lg rounded-lg p-4 mt-6">
                        <h3 class="text-[#7494ec] text-xl font-bold">Top Tags</h3>
                        <ul class="mt-4 space-y-2 text-sm sm:text-base">
                            <li class="flex justify-between">
                                <span>angular</span>
                                <span>1,850 score</span>
                            </li>
                            <li class="flex justify-between">
                                <span>javascript</span>
                                <span>631 score</span>
                            </li>
                            <li class="flex justify-between">
                                <span>typescript</span>
                                <span>483 score</span>
                            </li>
                            <li class="flex justify-between">
                                <span>css</span>
                                <span>467 score</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Top Posts Section -->
                    <h2 class="mt-6 mb-2 text-[#7494ec] text-xl font-bold">Top Posts</h2>
                    <div class="border border-[#7494ec] shadow-lg rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row items-start border-b border-[#7494ec] pb-4 mb-4">
                            <div class="flex-1">
                                <a href="#" class="text-[#633F92] font-medium hover:underline">
                                    How does JVM Clojure store captured environments of closures under the hood?
                                </a>
                                <div class="flex mt-3 items-center">
                                    <button
                                        class="px-3 py-1 bg-[#7494ec] text-white rounded-lg hover:bg-[#5f83c8] transition mr-3">
                                        Like
                                    </button>
                                    <span class="text-sm text-gray-400">12 Likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let user = <?php echo json_encode($user); ?>;

        async function follow() {
            try {
                const email = user['email'];
                const response = await fetch("{{ route('nembakFollow') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email
                    })
                });
                const data = await response.json();

                Swal.close();
                if (data.ok) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Success',
                    //     text: 'Success!',
                    // });

                    const isFollowing = data.data.user.following.some(follower => follower.email === email);

                    if (isFollowing) {
                        document.getElementById('followBtn').textContent = 'Unfollow';
                    } else {
                        document.getElementById('followBtn').textContent = 'Follow';
                    }
                    document.getElementById('countFollowers').textContent = data.data.countFollowers;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Something went wrong!',
                });
            }
        }
    </script>
@endsection
