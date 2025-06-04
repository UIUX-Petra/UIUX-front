@extends('layout')

@section('content')
    <style>
        /* Keep only complex animations and effects that can't be done with Tailwind */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .tag-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(56, 163, 165, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .tag-card:hover::before {
            left: 100%;
        }

        .tag-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, #38A3A5, #80ED99);
            transition: width 0.3s ease;
        }

        .tag-link:hover::after {
            width: 100%;
        }

        /* Staggered animation for cards */
        .tag-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .tag-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .tag-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .tag-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .tag-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .tag-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .tag-card:nth-child(7) {
            animation-delay: 0.7s;
        }

        .tag-card:nth-child(8) {
            animation-delay: 0.8s;
        }
    </style>

    @include('partials.nav')

    <!-- Decorative background elements -->
    <div
        class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.2)] to-[rgba(128,237,153,0.2)] blur-2xl pointer-events-none">
    </div>
    <div
        class="absolute -left-32 top-96 w-48 h-48 rounded-full bg-gradient-to-br from-[rgba(128,237,153,0.15)] to-[rgba(56,163,165,0.15)] blur-xl pointer-events-none">
    </div>

    <!-- Welcome Section -->
    <div class="w-full rounded-lg p-6 px-8 max-w-5xl items-start justify-start my-6 relative z-10">
        <div class="relative z-10">
            <h1 class="cal-sans-regular lg:text-4xl text-3xl mb-4 text-[var(--text-primary)] ">
                Academic Subjects
            </h1>
            <p class="text-[var(--text-secondary)] text-md lg:text-lg pl-0.5 font-regular leading-relaxed max-w-4xl">
                Explore comprehensive subjects from
                <span class="font-semibold text-[var(--accent-tertiary)]">Informatics</span>,
                <span class="font-semibold text-[var(--accent-secondary)]">Business Information Systems</span>, and
                <span class="font-semibold text-[var(--accent-primary)]">Data Science & Analytics</span>
                programs at Petra Christian University. Connect with fellow students and dive deep into your academic
                interests.
            </p>
        </div>
    </div>

    <!-- Subjects Grid -->
    <div
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-5xl items-start justify-start px-6">
        @if (isset($tags) && count($tags) > 0)
            @foreach ($tags as $index => $tag)
                <a href="{{ route('popular', ['filter_tag' => $tag['name'], 'sort_by' => 'latest', 'page' => 1]) }}"
                    class="tag-link hover:no-underline focus:no-underline text-xl font-bold text-[var(--text-primary)] hover:text-[var(--accent-primary)] transition-all duration-300 relative focus:outline-2 focus:outline-[var(--accent-primary)] focus:outline-offset-2 focus:rounded"
                    aria-label="View questions for {{ $tag['name'] }}">
                    <div
                        class="tag-card fade-in shadow-lg rounded-xl p-6 bg-[var(--bg-card)] border border-[var(--border-color)] relative overflow-hidden transition-all duration-300 hover:transform hover:-translate-y-2 hover:scale-105 hover:shadow-xl hover:border-[var(--accent-tertiary)] hover:bg-[var(--bg-card-hover)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-1">
                                <div class="mb-1">

                                    {{ $tag['abbreviation'] }}
                                </div>
                                <div class="text-sm text-[var(--text-secondary)] font-medium">
                                    {{ $tag['name'] }}
                                </div>
                            </div>
                        </div>

                        <div class="text-sm my-4 text-[var(--text-muted)] leading-relaxed">
                            <p>{{ $tag['description'] ?? 'Explore questions and discussions related to this subject area.' }}
                            </p>
                        </div>

                        <div
                            class="bg-gradient-to-r from-[rgba(56,163,165,0.08)] to-[rgba(128,237,153,0.08)] border border-[rgba(56,163,165,0.2)] rounded-lg px-3 py-2 inline-flex items-center gap-2">
                            <i class="fa-solid fa-comment-dots text-[var(--accent-secondary)] text-sm"></i>
                            <span class="text-sm font-semibold text-[var(--text-primary)]">
                                {{ isset($tag['questions']) ? number_format($tag['questions']) : '0' }}
                                {{ isset($tag['questions']) && $tag['questions'] == 1 ? 'question' : 'questions' }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <div class="col-span-full">
                <div
                    class="text-center py-16 px-8 bg-gradient-to-br from-[rgba(56,163,165,0.05)] to-[rgba(128,237,153,0.05)] border-2 border-dashed border-[var(--border-color)] rounded-2xl my-8">
                    <i class="fa-solid fa-book-open text-6xl text-[var(--text-muted)] mb-4"></i>
                    <h3 class="text-xl font-semibold text-[var(--text-primary)] mb-2">No Subjects Available</h3>
                    <p class="text-[var(--text-secondary)] text-lg">
                        We're currently updating our subject catalog. Please check back soon for exciting academic
                        discussions!
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Bottom spacing -->
    <div class="h-16"></div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize fade-in animations
            const cards = document.querySelectorAll('.tag-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.offsetHeight; // Trigger reflow
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Enhanced hover effects
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px) scale(1.02)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Accessibility improvements
            cards.forEach(card => {
                card.setAttribute('tabindex', '0');
                card.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const link = this.querySelector('.tag-link');
                        if (link) {
                            link.click();
                        }
                    }
                });
            });
        });

        // Optional: Add intersection observer for better performance
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            document.querySelectorAll('.tag-card').forEach(card => {
                observer.observe(card);
            });
        }
    </script>
@endsection
