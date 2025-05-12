@extends('layout')

@section('head')
    <style>
        .popular-title {
            background: -webkit-linear-gradient(#facc15, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .popular-container {
            background-color: var(--bg-card);
            border-radius: 1rem;
            transition: background-color var(--transition-speed);
        }
    </style>
@endsection

@section('content')
@include('partials.nav')
    @if (session()->has('Error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('Error') }}'
            });
        </script>
    @endif
    {{-- @include('utils.background2') --}}

    <!-- Header Section -->
    <div class="w-full bg-transparent rounded-lg p-6 px-6 max-w-7xl mx-auto mt-6 mb-2 flex items-center space-x-4 popular-container">
        <div class="text-3xl">
            <i class="fa-solid fa-fire text-[#f59e0b]"></i>
        </div>
        <div class="flex flex-col">
            <h1 class="cal-sans-regular popular-title lg:text-3xl text-xl mb-1">
                Popular Questions
            </h1>
            <p class="text-[var(--text-secondary)] text-lg pl-0.5 font-regular">
                Hottest discussions voted by the community.
            </p>
        </div>
    </div>

    <!-- Questions List -->
    <div class="w-full bg-transparent rounded-lg px-6 pb-6 shadow-lg max-w-3xl justify-start items-start">
        @foreach ($questions as $question)
            <div class="question-card rounded-lg mb-2 p-4 pb-8 transition-all duration-200 flex">
                <div class="flex flex-col items-end justify-end mr-4 pt-1 space-y-2 pl-6">
                    <div class="p-0 font-semibold inline-flex flex-row items-center space-x-1 cursor-auto">
                        <i class="text-sm fa-regular fa-thumbs-up bg-transparent pr-0.5"></i>
                        <span class="text-[0.70rem] question-interaction text-[var(--text-secondary)]">{{ $question['vote'] }}</span>
                    </div>
                    <div class="p-0 font-semibold inline-flex flex-row items-center space-x-1 cursor-auto">
                        <i class="text-sm fa-solid fa-eye bg-transparent pr-0.5"></i>
                        <span class="text-[0.70rem] text-[var(--text-secondary)]">{{ $question['view'] }}</span>
                    </div>
                    <div class="p-0 font-semibold inline-flex flex-row items-center space-x-1 cursor-auto">
                        <i class="text-sm fa-regular fa-comment bg-transparent pr-0.5"></i>
                        <span class="text-[0.70rem] text-[var(--text-secondary)]">{{ $question['comments_count'] }}</span>
                    </div>
                </div>

                <div class="flex-1">
                    <h2 class="text-xl question-title cursor-pointer transition-colors duration-200">
                        <a href="{{ route('user.viewQuestions', ['questionId' => $question['id']]) }}">{{ $question['title'] }}</a>
                    </h2>
                    <p class="text-[var(--text-muted)] text-md text-justify mt-0.5">{{ \Str::limit($question['question'], 150) }}</p>
                </div>
            </div>
        @endforeach

        {{ $questions->links() }}
    </div>
@endsection

@section('script')
<script>
    function updateIconColors() {
        const icons = document.querySelectorAll('.question-card i');
        const isLightMode = document.documentElement.classList.contains('light-mode');
        
        if (icons) {
            icons.forEach((icon, index) => {
                if (index % 3 === 0) {
                    icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                } else if (index % 3 === 1) {
                    icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                } else {
                    icon.style.color = isLightMode ? '#3b82f6' : '#909ed5';
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', updateIconColors);

    const themeObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                updateIconColors();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        themeObserver.observe(document.documentElement, { attributes: true });
    });
</script>
@endsection
