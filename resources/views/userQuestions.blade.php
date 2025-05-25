{{-- resources/views/users/questions_list.blade.php --}}
@extends('layout')

@section('content')
    @include('partials.nav')
    @include('utils.background')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex space-x-3 text-white">
            <img class="size-8 rounded-full"
                src="{{ $image ? asset('storage/' . $image) : 'https://via.placeholder.com/150' }}" alt="User avatar">
            @if (session('email') != $user['email'])
                <h1 class="text-3xl font-bold text-white mb-8">
                    <a href="{{ route('viewUser', ['email' => $user['email']]) }}">My Questions</a>
                </h1>
            @else
                <h1 class="text-3xl font-bold text-white mb-8">
                    <a href="{{ route('viewUser', ['email' => $user['email']]) }}">{{ $user['username'] }}'s Questions</a>
                </h1>
            @endif

        </div>
        {{-- <h1 class="text-3xl font-bold text-white mb-8">
            Questions
        </h1> --}}
        {{-- @dd($user['question']) --}}

        @if (!empty($user['question']) && count($user['question']) > 0)
            <div class="space-y-6">
                @foreach ($user['question'] as $question)
                    <div class="bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300">
                        <h2 class="text-xl font-semibold text-[#7494ec] mb-2 flex justify-between items-center">
                            {{-- Tambahkan flexbox untuk penempatan tombol --}}
                            <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}"
                                class="hover:underline">
                                {{ $question['title'] ?? 'No Title Provided' }}
                            </a>
                            <div class="flex space-x-2"> {{-- Wadah untuk tombol edit dan delete --}}
                                @if (session('email') == $user['email']) {{-- Cek apakah user yang login adalah pemilik pertanyaan --}}
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('editQuestionPage', ['id' => $question['id']]) }}"
                                        class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</a>
                                    {{-- Tombol Delete --}}
                                    <button type="button"
                                        onclick="confirmDelete({{ $question['id'] }})"
                                        class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                                @endif
                            </div>
                        </h2>

                        @if (isset($question['question']) && is_string($question['question']))
                            <p class="text-gray-700 mb-3">{{ Str::limit($question['question'], 200) }}</p>
                        @endif

                        <div class="text-sm text-gray-500">
                            @if (isset($question['created_at']))
                                <span>Posted on:
                                    {{ \Carbon\Carbon::parse($question['created_at'])->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg p-6 text-center">
                <p class="text-gray-600 text-lg">{{ $user['username'] }} has not posted any questions yet.</p>
            </div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('viewUser', ['email' => $user['email']]) }}">
                Back
            </a>
        </div>
    </div>
@endsection
@section('script')  
    <script>
        function confirmDelete(questionId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleting...",
                        text: "Please wait while we delete your question.",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`{{ url('questions') }}/${questionId}/delete`, { // Sesuaikan dengan route POST delete di web.php
                            method: 'POST', // Menggunakan POST untuk method spoofing di Laravel
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                _method: 'DELETE', // Method spoofing untuk Laravel
                                email: '{{ session('email') }}' // Kirim email untuk otorisasi di API
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close(); // Tutup loading SweetAlert

                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    // Muat ulang halaman atau hapus elemen pertanyaan dari DOM
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Failed!',
                                    data.message || 'An error occurred during deletion.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.close(); // Tutup loading SweetAlert
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                'Failed to delete question. Please try again later.',
                                'error'
                            );
                        });
                }
            });
        }
    </script>
@endsection
