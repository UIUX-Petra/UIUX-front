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

        @if (!empty($user['question']) && count($user['question']) > 0)
            <div class="space-y-6">
                @foreach ($user['question'] as $question)
                    <div class="bg-white shadow-xl rounded-lg p-6 hover:shadow-2xl transition-shadow duration-300">
                        <h2 class="text-xl font-semibold text-[#7494ec] mb-2">
                            <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}"
                                class="hover:underline">
                                {{ $question['title'] ?? 'No Title Provided' }}
                            </a>
                        </h2>

                        @if (isset($question['question']) && is_string($question['question']))
                            <p class="text-gray-700 mb-3">{{ Str::limit($question['question'], 200) }}</p>
                        @endif

                        <div class="text-sm text-gray-500">
                            @if (isset($question['created_at']))
                                <span>Posted on:
                                    {{ \Carbon\Carbon::parse($question['created_at'])->format('M d, Y') }}</span>
                            @endif
                            {{-- Jika ada view count atau vote count --}}
                            {{-- @if (isset($question['view'])) | <span>Views: {{ $question['view'] }}</span> @endif --}}
                            {{-- @if (isset($question['vote'])) | <span>Votes: {{ $question['vote'] }}</span> @endif --}}
                        </div>

                        {{-- <pre class="text-xs bg-gray-100 p-2 mt-2 rounded">{{ print_r($question, true) }}</pre> --}}
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
