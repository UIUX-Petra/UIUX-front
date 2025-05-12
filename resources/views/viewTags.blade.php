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

        .tag-card {
            background-color: var(--bg-card);
            color: var(--text-primary);
            transition: background-color var(--transition-speed);
        }

        .description-wrapper {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .description-wrapper.open {
            max-height: 500px; /* arbitrary large height */
        }

    </style>
@endsection

@section('content')
@include('partials.nav')
    {{-- @include('utils.background2') --}}

    <div class="w-full rounded-lg p-6 px-6 max-w-5xl items-start jsutify-start my-6 welcome-container">
        <h1 class="cal-sans-regular lg:text-3xl text-xl mb-3 welcome">Tags</h1>
        <p class="text-[var(--text-secondary)] text-md lg:text-lg pl-0.5 font-regular">
            Tags represent all the courses offered in the Informatics, Business Information Systems, and Data Science & Analytics programs at Petra Christian University.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-5xl items-start justify-start px-6">
        @foreach ($tags as $index => $tag)
            <div class="tag-card shadow-lg rounded-xl p-5 bg-[var(--bg-card)]">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-semibold capitalize text-[var(--text-primary)]">{{ $tag['name'] }}</h3>
                    {{-- <span
                        class="material-symbols-outlined text-[var(--text-primary)] text-2xl cursor-pointer toggle-btn hover:animate-wiggle"
                        data-target="description-{{ $index }}">
                        expand_more
                    </span> --}}


                </div>

                <div id="description-{{ $index }}" class="description-wrapper text-sm text-[var(--text-secondary)] my-2">
                    <p>{{ $tag['description'] }}</p>
                </div>

                <div class="text-sm text-[var(--text-muted)] mt-1">
                    <p><strong>{{ number_format($tag['questions']) }}</strong> questions</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('script')
    <script>
        // document.querySelectorAll('.toggle-btn').forEach(btn => {
        //     btn.addEventListener('click', function () {
        //         const targetId = this.getAttribute('data-target');
        //         const target = document.getElementById(targetId);

        //         if (target) {
        //             const isOpen = target.classList.toggle('open');
        //             target.style.maxHeight = isOpen ? target.scrollHeight + "px" : null;
        //             this.textContent = isOpen ? 'expand_less' : 'expand_more';
        //         }
        //     });
        // });
    </script>
@endsection
