@extends('layout')

@section('content')
    @include('partials.nav')
    {{-- masi full AI ki info e :) --}}

    {{-- Custom styles for the FAQ page --}}
    <style>
        .faq-hero {
            background: linear-gradient(135deg, rgba(56, 163, 165, 0.08), rgba(128, 237, 153, 0.08));
            border: 1px solid rgba(56, 163, 165, 0.15);
            position: relative;
            overflow: hidden;
        }

        .faq-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(56, 163, 165, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .faq-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(128, 237, 153, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .faq-title {
            background: linear-gradient(135deg, #38A3A5, #57CC99, #80ED99);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .faq-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 1024px) {
            .faq-grid {
                grid-template-columns: 1fr 320px;
            }
        }

        .faq-category {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .faq-category:hover {
            border-color: rgba(56, 163, 165, 0.3);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .category-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .category-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .category-icon.reputation {
            background: linear-gradient(135deg, rgba(56, 163, 165, 0.15), rgba(87, 204, 153, 0.15));
            color: var(--accent-tertiary);
        }

        .category-icon.qa {
            background: linear-gradient(135deg, rgba(128, 237, 153, 0.15), rgba(56, 163, 165, 0.15));
            color: var(--accent-secondary);
        }

        .category-icon.voting {
            background: linear-gradient(135deg, rgba(87, 204, 153, 0.15), rgba(128, 237, 153, 0.15));
            color: var(--accent-primary);
        }

        .category-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .faq-item {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .faq-item:last-child {
            margin-bottom: 0;
        }

        .faq-item:hover {
            border-color: rgba(56, 163, 165, 0.2);
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .faq-question {
            width: 100%;
            padding: 1.25rem 1.5rem;
            background: none;
            border: none;
            text-align: left;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-highlight);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .faq-question:hover {
            color: var(--accent-tertiary);
        }

        .faq-question:focus {
            outline: none;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s ease;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.02), rgba(56, 163, 165, 0.02));
        }

        .faq-answer.active {
            max-height: 600px;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .faq-answer-content {
            padding: 0 1.5rem 1.5rem 1.5rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .faq-answer-content p {
            margin-bottom: 1rem;
        }

        .faq-answer-content p:last-child {
            margin-bottom: 0;
        }

        .faq-answer-content ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 1rem;
        }

        .faq-answer-content li {
            padding: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
        }

        .faq-answer-content li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: var(--accent-tertiary);
            font-weight: bold;
        }

        .faq-answer-content strong {
            color: var(--text-highlight);
            font-weight: 700;
        }

        .faq-answer-content code {
            background: var(--bg-tertiary);
            color: var(--accent-tertiary);
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9em;
            border: 1px solid var(--border-color);
        }

        .faq-icon {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-muted);
            font-size: 1rem;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
            color: var(--accent-tertiary);
        }

        .faq-sidebar {
            position: sticky;
            top: 6rem;
            height: fit-content;
        }

        .sidebar-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .sidebar-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(56, 163, 165, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .quick-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .quick-links li {
            margin-bottom: 0.75rem;
        }

        .quick-links li:last-child {
            margin-bottom: 0;
        }

        .quick-links a {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .quick-links a:hover {
            background: var(--bg-tertiary);
            color: var(--text-highlight);
            border-color: var(--border-color);
            transform: translateX(4px);
        }

        .quick-links i {
            margin-right: 0.75rem;
            width: 16px;
            color: var(--accent-tertiary);
        }

        .help-card {
            text-align: center;
        }

        .help-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(56, 163, 165, 0.15), rgba(128, 237, 153, 0.15));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: var(--accent-tertiary);
        }

        .help-btn {
            background: linear-gradient(135deg, #38A3A5, #57CC99, #80ED99);
            color: #000;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(56, 163, 165, 0.2);
        }

        .help-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(56, 163, 165, 0.3);
            background: linear-gradient(135deg, #80ED99, #57CC99, #38A3A5);
        }

        .help-btn i {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .faq-hero {
                padding: 1.5rem;
            }
            
            .faq-title {
                font-size: 2rem;
            }
            
            .category-header {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            
            .category-icon {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }
    </style>

    {{-- Hero Section --}}
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="faq-hero rounded-2xl p-8 mb-8">
            <div class="flex items-center space-x-6 relative z-10">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[rgba(56,163,165,0.2)] to-[rgba(128,237,153,0.2)] flex items-center justify-center">
                        <i class="fa-solid fa-circle-question text-2xl text-[var(--accent-tertiary)]"></i>
                    </div>
                </div>
                <div>
                    <h1 class="faq-title text-4xl md:text-5xl font-bold mb-3 cal-sans-regular">
                        Frequently Asked Questions
                    </h1>
                    <p class="text-[var(--text-secondary)] text-lg leading-relaxed max-w-3xl">
                        Everything you need to know about our community platform, from earning reputation to getting your questions answered.
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="faq-grid">
            {{-- FAQ Content --}}
            <div class="faq-content">
                {{-- Reputation & Privileges Category --}}
                <div class="faq-category">
                    <div class="category-header">
                        <div class="category-icon reputation">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <h2 class="category-title">Reputation & Privileges</h2>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What is Reputation?</span>
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <p>Reputation is a measure of the community's trust in you. It's earned by contributing high-quality content that fellow Petranesians find valuable. The more reputation you earn, the more credible you are as a user on this platform.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How do I earn Reputation?</span>
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <p>Reputation is awarded based on a vote threshold system. It is not tied to every single vote, but rather to achieving a significant level of community approval for your content.</p>
                                <ul>
                                    <li><strong>Gaining Reputation:</strong> You gain <strong>+1 reputation</strong> when a question or answer you posted receives <strong>10 or more</strong> upvotes. This is a one-time bonus per post for reaching a milestone of community validation.</li>
                                    <li><strong>Losing Reputation:</strong> You lose <strong>-1 reputation</strong> if your post's vote score, after having reached the 10-vote threshold, drops back down below 10 votes.</li>
                                </ul>
                                <p>This system is designed to reward consistently helpful content that the community agrees is valuable.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Asking & Answering Category --}}
                <div class="faq-category">
                    <div class="category-header">
                        <div class="category-icon qa">
                            <i class="fa-solid fa-question"></i>
                        </div>
                        <h2 class="category-title">Asking & Answering</h2>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How can I edit or delete my question?</span>
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <p>You can edit or delete your question at any time, <strong>as long as it has not received any answers or votes</strong>. Once another user interacts with your question by voting on it or providing an answer, the content is locked to preserve the context for future readers.</p>
                                <p>If your question is locked but you need to make a crucial correction, you can add a comment to your own question to provide clarification.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>How can I edit or delete my answer?</span>
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <p>Similar to questions, you can edit or delete your own answer freely, provided it meets certain conditions. To maintain integrity, you cannot edit or delete your answer if:</p>
                                <ul>
                                    <li>It has received any upvotes or downvotes.</li>
                                    <li>The original question asker has marked it as a "Verified Answer".</li>
                                </ul>
                                <p>If your answer is locked, you can leave comments on it to add corrections or further details.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What are "Subjects" and how should I use them?</span>
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <p>Subjects (or tags) are keywords that categorize your question with other, similar questions. Using accurate and relevant subjects is one of the best ways to get your question seen by users who have expertise in that area.</p>
                                <ul>
                                    <li>Try to use existing subjects whenever possible.</li>
                                    <li>Be specific. For example, a question about a specific function in Python is better tagged with <code>python</code> and <code>functions</code> rather than just <code>code</code>.</li>
                                    <li>You can add multiple subjects to a single question to cover all its aspects.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Voting & Verification Category --}}
                <div class="faq-category">
                    <div class="category-header">
                        <div class="category-icon voting">
                            <i class="fa-solid fa-thumbs-up"></i>
                        </div>
                        <h2 class="category-title">Voting & Verification</h2>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>What is a "Verified Answer"?</span>
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <p>A "Verified Answer" is an answer that the original asker has marked as the best solution to their problem. It's a signal to the rest of the community that this particular answer was the most helpful.</p>
                                <p>Only the person who asked the question can verify an answer. Verified answers are highlighted with a green border and a special checkmark, and they provide the largest reputation boost to the answer's author.</p>
                            </div>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Why should I vote?</span>
                            <i class="fa-solid fa-chevron-down faq-icon"></i>
                        </button>
                        <div class="faq-answer">
                            <div class="faq-answer-content">
                                <p>Voting is your way of giving back to the community and showing appreciation for helpful content. It's a primary driver of reputation.</p>
                                <ul>
                                    <li><strong>Upvoting</strong> indicates that a question is well-researched and clear, or that an answer is correct and useful.</li>
                                    <li><strong>Downvoting</strong> indicates that a post is unclear, poorly researched, or incorrect. It's a tool to help keep content quality high.</li>
                                </ul>
                                <p>Your votes help fellow Petranesians gain reputation and credibility.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="faq-sidebar">
                {{-- Quick Navigation --}}
                <div class="sidebar-card">
                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-4 relative z-10">Quick Navigation</h3>
                    <ul class="quick-links relative z-10">
                        <li><a href="#reputation"><i class="fa-solid fa-star"></i>Reputation & Privileges</a></li>
                        <li><a href="#qa"><i class="fa-solid fa-question"></i>Asking & Answering</a></li>
                        <li><a href="#voting"><i class="fa-solid fa-thumbs-up"></i>Voting & Verification</a></li>
                    </ul>
                </div>

                {{-- Need More Help --}}
                <div class="sidebar-card help-card">
                    <div class="help-icon">
                        <i class="fa-solid fa-life-ring"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-2 relative z-10">Need More Help?</h3>
                    <p class="text-[var(--text-secondary)] text-sm mb-4 relative z-10">Can't find what you're looking for? Ask the community!</p>
                    <a href="{{ route('askPage') }}" class="help-btn relative z-10">
                        <i class="fa-solid fa-plus"></i>
                        Ask a Question
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');

            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');

                // Close all other items (optional - remove if you want multiple items open)
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-answer').classList.remove('active');
                    }
                });

                // Toggle current item
                if (isActive) {
                    item.classList.remove('active');
                    answer.classList.remove('active');
                } else {
                    item.classList.add('active');
                    answer.classList.add('active');
                }
            });
        });

        // Smooth scroll for quick navigation
        document.querySelectorAll('.quick-links a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>
@endsection