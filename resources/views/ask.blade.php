@extends('layout')

@section('style')
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-primary);
            overflow-x: hidden;
        }

        .section-container {
            background-color: transparent;
            border-radius: 1rem;
        }

        .section-icon {
            color: var(--accent-tertiary);
            font-size: 1.5rem;
        }

        .form-section {
            background-color: var(--bg-card);
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            transition: box-shadow 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.05);
        }

        .input-label {
            font-weight: 600;
            color: var(--text-primary);
            display: block;
        }

        .input-field {
            width: 100%;
            border-radius: 0.5rem !important;
            border: 1px solid var(--border-color) !important;
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .input-field:focus {
            box-shadow: 0 0 0 2px rgba(99, 63, 146, 0.2);
            outline: none;
        }

        #editor {
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background-color: var(--bg-secondary);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--accent-secondary) var(--bg-light);
        }

        #editor::-webkit-scrollbar {
            width: 6px;
        }

        #editor::-webkit-scrollbar-thumb {
            background-color: var(--accent-secondary);
            border-radius: 10px;
        }

        #editor::-webkit-scrollbar-track {
            background-color: #e5e7eb;
        }

        .toolbar {
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .image-upload-button {
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .image-preview-item {
            position: relative;
            max-width: 150px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .image-preview img {
            width: 100%;
            object-fit: cover;
        }

        .delete-btn {
            width: 100%;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-right: 0.5rem !important;
            padding-left: 0.5rem !important;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .delete-btn:hover {
            background-color: rgba(138, 0, 0, 0.9);
        }

        .tag-section {
            border-radius: 0.5rem;
        }

        .category-title {
            font-weight: 600;
            color: var(--text-primary);
            padding-bottom: 0.25rem;
            border-bottom: 2px solid var(--accent-tertiary);
        }

        .tab-inactive {
            background-color: var(--bg-secondary);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-active {
            background: linear-gradient(to right, #38A3A5, #80ED99);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tab-inactive:hover {
            background-color: var(--bg-card-hover);
            transform: translateY(-2px);
        }

        .submit-button {
            background: linear-gradient(to right, #38A3A5, #80ED99);
            color: black;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            background: linear-gradient(to right, #80ED99, #38A3A5);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .page-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(to right, #38A3A5, #80ED99);
            color: white;
            font-size: 1.25rem;
        }

        .tips-section {
            background-color: var(--accent-tertiary);
            border-radius: 1rem;
            border: 1px solid var(--border-color);
        }

        .tips-title {
            color: var(--text-dark);
            font-weight: 600;
        }

        .tips-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .tips-list li {
            color: var(--text-dark);
        }

        .tips-list i {
            color: var(--text-dark);
        }

        .tag-item-modal {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: var(--bg-secondary);
        }

        .tag-item-modal:hover {
            border-color: var(--accent-tertiary);
            background-color: var(--bg-card);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .tag-item-modal.selected {
            background: linear-gradient(to right, rgba(56, 163, 165, 0.1), rgba(128, 237, 153, 0.1));
            border-color: var(--accent-secondary);
            color: var(--accent-tertiary);
        }

        .tag-item-modal.selected:hover {
            background: linear-gradient(to right, rgba(56, 163, 165, 0.15), rgba(128, 237, 153, 0.15));
        }

        .tag-checkbox-modal {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            position: relative;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .tag-checkbox-modal.checked {
            background: linear-gradient(to right, #38A3A5, #80ED99);
            border-color: var(--accent-secondary);
        }

        .tag-checkbox-modal.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .selected-tag-badge-new {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: var(--bg-light);
            color: var(--text-primary);
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            animation: tagAppear 0.2s ease-out;
        }

        .selected-tag-badge-new .remove-tag {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-size: 12px;
            margin-left: 4px;
        }

        .selected-tag-badge-new .remove-tag:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .tag-info {
            flex: 1;
            min-width: 0;
        }

        .tag-name {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .tag-count {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Modal animations */
        #tags-modal {
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        #tags-modal.show {
            opacity: 1;
            visibility: visible;
        }

        #tags-modal .bg-\[var\(--bg-card\)\] {
            transform: scale(0.95);
            transition: transform 0.2s ease;
        }

        #tags-modal.show .bg-\[var\(--bg-card\)\] {
            transform: scale(1);
        }

        /* Custom scrollbar for tags grid */
        #tags-grid::-webkit-scrollbar {
            width: 6px;
        }

        #tags-grid::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #38A3A5, #80ED99);
            border-radius: 10px;
        }

        #tags-grid::-webkit-scrollbar-track {
            background-color: var(--bg-secondary);
            border-radius: 10px;
        }

        @keyframes tagAppear {
            from {
                opacity: 0;
                transform: scale(0.8) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .form-section {
            background-color: var(--bg-card);
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
        }

        .form-section:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
            border-color: var(--accent-tertiary);
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(56, 163, 165, 0.02) 0%, rgba(128, 237, 153, 0.02) 100%);
            border-radius: 1rem;
            pointer-events: none;
        }
    </style>
@endsection
@section('content')
    @if (session()->has('Error'))
        <script>
            Toastify({
                text: "{{ session('Error') }}" || "An unexpected error occurred from the server.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "#e74c3c"
                }
            }).showToast();
        </script>
    @endif
    @include('partials.nav')

    @php
        $isEditMode = isset($questionToEdit) && $questionToEdit !== null;

        $formActionUrl = $isEditMode
            ? // call api dipindah dari sini ke updateQuestion, buat avoid error 'Unauthenticated'. ndapapa?
            // ? url(env('API_URL', '') . "/questions/{$questionToEdit['id']}/updatePartial")
            route('updateQuestion', ['id' => $questionToEdit['id']])
            : route('addQuestion');

        $formMethod = 'POST';
        $pageH1Title = $isEditMode ? 'Edit Your Question' : 'Ask a Question';
        $submitButtonText = $isEditMode ? 'Update Question' : 'Publish Question';
    @endphp
    <div class="max-w-5xl justify-start items-start px-4 py-8">
        <!-- Page Header Section -->
        <div
            class="w-full bg-transparent rounded-lg p-2 px-4 max-w-5xl justify-start mt-6 mb-6 flex items-start space-x-5 backdrop-blur-sm relative overflow-hidden">
            <div class="flex flex-col z-10">
                <h1
                    class="cal-sans-regular text-3xl lg:text-4xl bg-gradient-to-br from-[#38A3A5] via-[#57CC99] to-[#80ED99] bg-clip-text text-transparent leading-tight py-1">
                    {{ $pageH1Title }}
                </h1>
                <div class="h-1 w-24 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] rounded-full mt-2"></div>
                <p class="text-[var(--text-muted)] text-lg leading-relaxed max-w-3xl mt-2">
                    @if ($isEditMode)
                        Update the details of your question.
                    @else
                        Share your problem and get help from the community.
                    @endif
                </p>
            </div>
        </div>

        {{-- @if (!$isEditMode)
            <!-- Tips Section (Show only for new questions) -->
            <div class="tips-section p-6 my-8 relative overflow-hidden">
                <div class="tips-title flex items-center gap-2 mb-4">
                    <i class="fa-solid fa-lightbulb"></i>
                    <span>Tips for a Great Question</span>
                </div>
                <ul class="tips-list">
                    <li class="flex items-start gap-2 mb-2"><i class="fa-solid fa-check-circle mt-1"></i><span>Be specific
                            and clear</span></li>
                    <li class="flex items-start gap-2 mb-2"><i class="fa-solid fa-check-circle mt-1"></i><span>Include
                            relevant code</span></li>
                    <li class="flex items-start gap-2 mb-2"><i class="fa-solid fa-check-circle mt-1"></i><span>Explain what
                            you tried</span></li>
                    <li class="flex items-start gap-2 mb-2"><i class="fa-solid fa-check-circle mt-1"></i><span>Select
                            appropriate subjects</span></li>
                </ul>
            </div>
        @endif --}}

        <form id="post-form" enctype="multipart/form-data">
            @if ($isEditMode)
                <input type="hidden" id="question_id_for_js" value="{{ $questionToEdit['id'] }}">
            @endif

            <!-- Title Section -->
            <div class="form-section p-6 mb-6 relative overflow-hidden">
                <div
                    class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.08)] to-[rgba(128,237,153,0.08)]">
                </div>
                <div class="flex items-center mb-4 relative z-10">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-[#38A3A5] to-[#80ED99] flex items-center justify-center mr-3">
                        <i class="fa-solid fa-heading text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-[var(--text-primary)]">Question Title</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">A clear and specific title helps others understand your
                    question quickly</p>
                <input type="text" id="title" name="title"
                    class="placeholder-[var(--text-muted)] input-field p-3 text-black"
                    placeholder="What's your question about?"
                    value="{{ old('title', $isEditMode ? $questionToEdit['title'] ?? '' : '') }}" required>
            </div>

            <!-- Details Section -->
            <div class="form-section p-6 mb-6 relative overflow-hidden">
                <div
                    class="absolute -bottom-5 -left-5 w-20 h-20 rounded-full bg-gradient-to-tl from-[rgba(56,163,165,0.08)] to-[rgba(128,237,153,0.08)]">
                </div>
                <div class="flex items-center mb-4 relative z-10">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-[#38A3A5] to-[#80ED99] flex items-center justify-center mr-3">
                        <i class="fa-solid fa-align-left text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-[var(--text-primary)]">Question Details</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">Provide all relevant details</p>
                <div id="editor" class="w-auto">
                    <div class="p-4 pb-0 bg-[var(--bg-secondary)]">
                        <textarea id="question" name="question" rows="8"
                            class="block w-full px-0 text-[var(--text-primary)] placeholder-[var(--text-muted)] bg-transparent border-0 focus:ring-0"
                            placeholder="Describe your question in detail..." required>{{ old('question', $isEditMode ? $questionToEdit['question'] ?? '' : '') }}</textarea>
                        <div id="image-preview" class="flex flex-wrap gap-4 mt-4 p-2">
                            @if ($isEditMode && !empty($questionToEdit['image']))
                                <div class="image-preview-item existing-image max-w-[150px]">
                                    <img src="{{ env('IMAGE_PATH', 'http://localhost:8001/storage') . '/' . $questionToEdit['image'] }}"
                                        alt="Current question image">
                                    <button type="button" class="delete-btn delete-existing-image-btn"
                                        data-image-filename="{{ $questionToEdit['image'] }}">Delete</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="toolbar w-full flex flex-col items-end gap-4 p-3 pb-6 bg-transparent border-none">
                    <button type="button" id="upload-image-btn"
                        class="image-upload-button w-max text-[var(--text-dark)] bg-[var(--accent-tertiary)] flex items-center gap-2 py-2 px-4 relative group">
                        <i class="fa-solid fa-image"></i>
                        <span
                            class="absolute bottom-full mb-2 border border-[var(--border-color)] left-1/2 transform -translate-x-1/2 bg-[var(--bg-secondary)] text-[--text-muted] text-sm px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none">
                            Add Image
                        </span>
                    </button>
                </div>
            </div>

            <!-- Tags Section -->
            <div class="form-section p-6 mb-6 relative overflow-hidden">
                <div
                    class="absolute -top-5 -right-5 w-16 h-16 rounded-full bg-gradient-to-br from-[rgba(56,163,165,0.05)] to-[rgba(128,237,153,0.05)]">
                </div>
                <div class="flex items-center mb-4 relative z-10">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-[#38A3A5] to-[#80ED99] flex items-center justify-center mr-3">
                        <i class="fa-solid fa-tags text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-[var(--text-primary)]">Subjects</h2>
                </div>
                <div class="w-full flex justify-between">
                    <p class="text-[var(--text-secondary)] text-sm mb-4">Select relevant subjects to help others find your
                        question
                    </p>
                    <button type="button" id="ai-recommend-btn"
                        class="text-[var(--text-secondary)] text-sm mb-4 border border-[var(--border-color)] px-3 py-1 rounded-md hover:bg-[var(--bg-secondary)] transition-colors">
                        <i class="fa-solid fa-wand-magic-sparkles mr-2"></i>AI Recommendation
                    </button>
                </div>
                <!-- Selected Tags Display -->
                <div class="mb-4">
                    <div id="selected-tags-display"
                        class="flex flex-wrap gap-2 min-h-[40px] p-3 bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-lg relative items-end">
                        <div id="selected-tags-container" class="flex flex-wrap gap-2 flex-1">
                            <!-- Selected tags will appear here -->
                        </div>
                        <button type="button" id="open-tags-modal"
                            class="inline-flex items-center justify-center w-8 h-8 bg-[var(--accent-tertiary)] text-[var(--text-dark)] rounded-full hover:opacity-90 transition-opacity shrink-0">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </button>
                    </div>

                    <div class="flex items-center justify-between mt-2 mr-1 ml-0.5">
                        <span id="selected-count-badge"
                            class="text-xs bg-[var(--bg-shadow)] text-[var(--text-tag)] px-2 py-1 rounded-full min-w-[20px] text-center">0</span>
                        <button type="button" id="clear-all-tags" h3
                            class="text-[var(--text-muted)] text-xs uppercase tracking-wider mx-2 mb-3 hover:underline underline-offset-2">CLEAR
                            ALL</h3>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hidden input for form submission -->
            <select id="tags-multiselect" name="subject_id[]" multiple class="hidden">
                @if (isset($allTags) && is_array($allTags))
                    @php
                        $sortedTags = collect($allTags)->sortBy('name')->values()->all();
                    @endphp
                    @foreach ($sortedTags as $tag)
                        <option value="{{ $tag['id'] }}"
                            {{ in_array($tag['id'], $selectedTagIdsOnLoad ?? []) ? 'selected' : '' }}>
                            {{ $tag['name'] }}
                        </option>
                    @endforeach
                @endif
            </select>


            <!-- Tags Modal -->
            <div id="tags-modal"
                class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
                <div class="bg-[var(--bg-card)] rounded-xl shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-[var(--border-color)]">
                        <div>
                            <h3 class="text-xl font-semibold text-[var(--text-primary)]">Select Subjects</h3>
                            <p class="text-sm text-[var(--text-secondary)] mt-1">Choose subjects that best describe your
                                question</p>
                        </div>
                        <button type="button" id="close-tags-modal"
                            class="p-2 hover:bg-[var(--bg-secondary)] rounded-lg transition-colors">
                            <i class="fa-solid fa-times text-xl text-[var(--text-secondary)]"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-1 flex flex-col min-h-0">
                        <!-- Search Bar -->
                        <div class="p-6 pb-4 border-b border-[var(--border-color)]">
                            <div class="relative">
                                <i
                                    class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[var(--text-secondary)]"></i>
                                <input type="text" id="tags-search-input"
                                    class="w-full pl-10 pr-4 py-3 bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] placeholder-[var(--text-secondary)] focus:border-[var(--accent-tertiary)] focus:ring-2 focus:ring-[var(--accent-tertiary)] focus:ring-opacity-20 transition-all"
                                    placeholder="Search tags..." autocomplete="off">
                            </div>
                            <div class="flex items-center justify-between mt-3 text-sm text-[var(--text-secondary)]">
                                <span>
                                    <span id="showing-count">0</span> tags available
                                </span>
                                <span>
                                    <span id="selected-count-modal">0</span> selected
                                </span>
                            </div>
                        </div>

                        <!-- Tags Grid -->
                        <div class="flex-1 overflow-y-auto p-6">
                            <div id="tags-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Tags will be populated here -->
                            </div>
                            <div id="no-tags-found" class="text-center py-12 hidden">
                                <i class="fa-solid fa-search text-4xl text-[var(--text-secondary)] opacity-50 mb-4"></i>
                                <p class="text-[var(--text-secondary)] text-lg">No tags found</p>
                                <p class="text-[var(--text-secondary)] text-sm mt-1">Try a different search term</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-6 border-t border-[var(--border-color)] bg-[var(--bg-secondary)] rounded-b-xl">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-[var(--text-secondary)]">
                                <span id="selected-count-footer">0</span> tags selected
                            </div>
                            <div class="flex gap-3 font-semibold">
                                <button type="button" id="cancel-tags-modal"
                                    class="px-4 py-2 text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-colors">
                                    Cancel
                                </button>
                                <button type="button" id="confirm-tags-modal"
                                    class="px-6 py-2 bg-[var(--accent-secondary)] text-[var(--text-dark)] rounded-lg hover:opacity-90 transition-opacity">
                                    Done
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <div class="inline-block relative">
                    <div
                        class="absolute -inset-2 bg-gradient-to-r from-[#38A3A5] to-[#80ED99] rounded-lg blur-sm opacity-30">
                    </div>
                    <button type="submit" id="submit-btn"
                        class="submit-button relative inline-flex items-center justify-center gap-2 py-4 px-10 text-lg font-semibold">
                        <i class="fa-solid {{ $isEditMode ? 'fa-save' : 'fa-paper-plane' }}"></i> {{ $submitButtonText }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const QUESTION_TO_EDIT = @json($questionToEdit ?? null);
        document.addEventListener('DOMContentLoaded', function() {
            const AI_SERVICE_URL = "{{ env('AI_SERVICE_URL', 'http://127.0.0.1:5000/ai') }}";
            const IS_EDIT_MODE = {{ $isEditMode ? 'true' : 'false' }};
            const FORM_ACTION_URL = "{{ $formActionUrl }}";
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const ALL_TAGS_FROM_PHP = @json($allTags ?? []);
            const EXISTING_TAG_IDS_ON_LOAD = @json($selectedTagIdsOnLoad ?? []);

            let aiRecommendedTagIds = [];

            let selectedTagIds = [];
            let tempSelectedTagIds = [];
            let imageFile = null;
            let allTags = ALL_TAGS_FROM_PHP || [];
            let filteredTags = [...allTags];

            const form = document.getElementById('post-form');
            const titleInput = document.getElementById('title');
            const questionTextarea = document.getElementById('question');
            const imagePreviewContainer = document.getElementById("image-preview");
            const hiddenSelect = document.getElementById('tags-multiselect');
            const aiRecommendBtn = document.getElementById('ai-recommend-btn');
            const submitBtn = document.getElementById('submit-btn');

            const openModalBtn = document.getElementById('open-tags-modal');
            const closeModalBtn = document.getElementById('close-tags-modal');
            const cancelModalBtn = document.getElementById('cancel-tags-modal');
            const confirmModalBtn = document.getElementById('confirm-tags-modal');
            const modal = document.getElementById('tags-modal');
            const searchInput = document.getElementById('tags-search-input');
            const tagsGrid = document.getElementById('tags-grid');
            const selectedTagsContainer = document.getElementById('selected-tags-container');
            const selectedCountBadge = document.getElementById('selected-count-badge');
            const selectedCountModal = document.getElementById('selected-count-modal');
            const selectedCountFooter = document.getElementById('selected-count-footer');
            const showingCount = document.getElementById('showing-count');
            const noTagsFound = document.getElementById('no-tags-found');
            const clearAllBtn = document.getElementById('clear-all-tags');

            aiRecommendBtn.addEventListener('click', async function() {
                const originalBtnContent = this.innerHTML;
                this.innerHTML =
                    `<i class="fa-solid fa-spinner fa-spin mr-2"></i>Getting Suggestions...`;
                this.disabled = true;
                const aiFormData = new FormData();
                aiFormData.append('title', titleInput.value);
                aiFormData.append('question', questionTextarea.value);
                if (imageFile) {
                    aiFormData.append('image', imageFile);
                }
                try {
                    const response = await axios.post(`${AI_SERVICE_URL}/recommend_tags`,
                        aiFormData);
                    if (response.data && response.data.success) {
                        const recommendedTags = response.data.recommended_tags;
                        const recommendedIds = recommendedTags.map(tag => tag.id);
                        aiRecommendedTagIds = recommendedIds;
                        selectedTagIds = recommendedIds;
                        updateMainUI();

                        Toastify({
                            text: "AI recommendations added!",
                            duration: 3000,
                            style: {
                                background: "#57CC99"
                            }
                        }).showToast();
                    } else {
                        throw new Error(response.data.message || 'Failed to get recommendations.');
                    }
                } catch (error) {
                    console.error("AI Recommendation Error:", error);
                    const errorMessage = error.response?.data?.message ||
                        "Could not connect to the AI service.";
                    Toastify({
                        text: errorMessage,
                        duration: 3000,
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                } finally {
                    this.innerHTML = originalBtnContent;
                    this.disabled = false;
                }
            });

            function addTagsToUI(tagsToAdd) {
                tagsToAdd.forEach(tag => {
                    const option = document.querySelector(`#tags-multiselect option[value="${tag.id}"]`);
                    if (option && !option.selected) {
                        option.selected = true;
                        document.getElementById('tags-multiselect').dispatchEvent(new Event('change'));
                    }
                });
            }

            function init() {
                if (IS_EDIT_MODE && EXISTING_TAG_IDS_ON_LOAD.length > 0) {
                    selectedTagIds = [...EXISTING_TAG_IDS_ON_LOAD];
                }
                updateMainUI();
            }

            function openModal() {
                tempSelectedTagIds = [...selectedTagIds];
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('show');
                    searchInput.focus();
                }, 10);
                renderTagsGrid();
                updateModalCounts();
            }

            function closeModal() {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    searchInput.value = '';
                    filteredTags = [...allTags];
                }, 200);
            }

            function confirmSelection() {
                selectedTagIds = [...tempSelectedTagIds];
                updateMainUI();
                closeModal();
            }

            function cancelSelection() {
                tempSelectedTagIds = [...selectedTagIds];
                closeModal();
            }

            function renderTagsGrid() {
                tagsGrid.innerHTML = '';
                filteredTags.length === 0 ? noTagsFound.classList.remove('hidden') : noTagsFound.classList.add(
                    'hidden');
                showingCount.textContent = filteredTags.length;

                filteredTags.forEach(tag => {
                    const isSelected = tempSelectedTagIds.includes(tag.id.toString());
                    const tagItem = document.createElement('div');
                    tagItem.className = `tag-item-modal ${isSelected ? 'selected' : ''}`;
                    tagItem.innerHTML =
                        `<div class="tag-checkbox-modal ${isSelected ? 'checked' : ''}"></div><div class="tag-info"><div class="tag-name">${tag.name}</div><div class="tag-count">${tag.questions || 0} questions</div></div>`;
                    tagItem.addEventListener('click', () => toggleTempTag(tag.id.toString()));
                    tagsGrid.appendChild(tagItem);
                });
            }

            function toggleTempTag(tagId) {
                const index = tempSelectedTagIds.indexOf(tagId);
                if (index > -1) tempSelectedTagIds.splice(index, 1);
                else tempSelectedTagIds.push(tagId);
                renderTagsGrid();
                updateModalCounts();
            }

            function updateModalCounts() {
                selectedCountModal.textContent = tempSelectedTagIds.length;
                selectedCountFooter.textContent = tempSelectedTagIds.length;
            }

            function updateMainUI() {
                selectedTagsContainer.innerHTML = '';
                selectedTagIds.forEach(tagId => {
                    const tag = allTags.find(t => t.id.toString() === tagId);
                    if (tag) {
                        const badge = document.createElement('span');
                        badge.className = 'selected-tag-badge-new';
                        badge.innerHTML =
                            `<span>${tag.name}</span><button type="button" class="remove-tag" onclick="removeTag('${tagId}')">×</button>`;
                        selectedTagsContainer.appendChild(badge);
                    }
                });
                selectedCountBadge.textContent = selectedTagIds.length;
                Array.from(hiddenSelect.options).forEach(opt => opt.selected = selectedTagIds.includes(opt.value));
            }

            function filterTags(searchTerm) {
                const term = searchTerm.toLowerCase().trim();
                filteredTags = term === '' ? [...allTags] : allTags.filter(tag => tag.name.toLowerCase().includes(
                    term));
                renderTagsGrid();
            }

            openModalBtn.addEventListener('click', openModal);
            closeModalBtn.addEventListener('click', closeModal);
            cancelModalBtn.addEventListener('click', cancelSelection);
            confirmModalBtn.addEventListener('click', confirmSelection);
            searchInput.addEventListener('input', (e) => {
                filterTags(e.target.value);
            });
            clearAllBtn.addEventListener('click', () => {
                selectedTagIds = [];
                updateMainUI();
            });
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    cancelSelection();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    cancelSelection();
                }
            });
            window.removeTag = function(tagId) {
                selectedTagIds = selectedTagIds.filter(id => id !== tagId);
                updateMainUI();
            };

            document.getElementById("upload-image-btn").addEventListener("click", function() {
                let fileInput = document.createElement("input");
                fileInput.type = "file";
                fileInput.name = "image";
                fileInput.accept = "image/*";
                fileInput.onchange = event => {
                    const file = event.target.files[0];
                    if (file) {
                        imageFile = file;
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const imagePreviewContainer = document.getElementById("image-preview");
                            const oldNewPreview = imagePreviewContainer.querySelector(
                                '.new-image-preview-item');
                            if (oldNewPreview) oldNewPreview.remove();

                            const existingImageDiv = imagePreviewContainer.querySelector(
                                '.existing-image');
                            if (existingImageDiv) {
                                existingImageDiv.style.display = 'none';
                            }

                            const newPreviewDiv = document.createElement("div");
                            newPreviewDiv.className = "image-preview-item new-image-preview-item";
                            const img = document.createElement("img");
                            img.src = e.target.result;
                            img.style.maxWidth = '150px';
                            const deleteBtn = document.createElement("button");
                            deleteBtn.type = "button";
                            deleteBtn.textContent = "Delete";
                            deleteBtn.className = "delete-btn";
                            deleteBtn.onclick = () => {
                                newPreviewDiv.remove();
                                imageFile = null;
                                if (existingImageDiv) {
                                    existingImageDiv.style.display = 'flex';
                                    const removeFlagInput = document.getElementById(
                                        'remove_existing_image_input');
                                    if (removeFlagInput) removeFlagInput.remove();
                                }
                            };
                            newPreviewDiv.appendChild(img);
                            newPreviewDiv.appendChild(deleteBtn);
                            imagePreviewContainer.appendChild(newPreviewDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                };
                fileInput.click();
            });

            const deleteExistingImageBtn = document.querySelector('.delete-existing-image-btn');
            if (deleteExistingImageBtn) {
                deleteExistingImageBtn.addEventListener('click', function() {
                    const existingImageDiv = this.closest('.existing-image');
                    if (existingImageDiv) {
                        existingImageDiv.remove();
                    }
                    if (!document.getElementById('remove_existing_image_input')) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'remove_existing_image';
                        hiddenInput.value = '1';
                        hiddenInput.id = 'remove_existing_image_input';
                        form.appendChild(hiddenInput);
                    }
                    imageFile = null;
                    const newImagePreview = document.querySelector('.new-image-preview-item');
                    if (newImagePreview) newImagePreview.remove();
                });
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const title = titleInput.value.trim();
                const questionText = questionTextarea.value.trim();
                if (title === '' || questionText === '' || selectedTagIds.length === 0) {
                    Toastify({
                        text: "Please fill in title, question, and select at least one tag.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                    return;
                }

                Swal.fire({
                    title: `${IS_EDIT_MODE ? 'Update your old question' : 'Submit a new question'}`,
                    text: `Once ${IS_EDIT_MODE ? 'updated' : 'submitted'}, this action might not be easily undone!`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: `Yes, ${IS_EDIT_MODE ? 'Update' : 'Submit'}!`,
                    cancelButtonText: 'No, Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: IS_EDIT_MODE ? "Updating..." : "Submitting...",
                            text: "Please wait...",
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        let formData = new FormData();
                        formData.append("title", title);
                        formData.append("question", questionText);
                        if (imageFile) {
                            formData.append("image", imageFile);
                        }
                        if (IS_EDIT_MODE && document.getElementById(
                                'remove_existing_image_input')) {
                            formData.append("remove_existing_image", "1");
                        }
                        if (!IS_EDIT_MODE) {
                            selectedTagIds.forEach(id => formData.append("selected_tags[]", id));
                            aiRecommendedTagIds.forEach(id => formData.append("recommended_tags[]",
                                id));
                        } else {
                            selectedTagIds.forEach(id => formData.append("selected_tags[]", id));
                        }

                        fetch(FORM_ACTION_URL, {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": CSRF_TOKEN,
                                    "Accept": "application/json"
                                },
                                body: formData
                            })
                            .then(response => response.json().then(data => ({
                                ok: response.ok,
                                data
                            })))
                            .then(({
                                ok,
                                data
                            }) => {
                                Swal.close();
                                if (ok && data.success) {
                                    Toastify({
                                        text: data.message ||
                                            "Your Question is succesfully saved",
                                        duration: 3000,
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        style: {
                                            background: "#57CC99"
                                        }
                                    }).showToast();
                                    setTimeout(() => {
                                        if (data.data && data.data.id) {
                                            window.location.href =
                                                "{{ route('user.questions.list', ['id' => 'id']) }}"
                                                .replace('id', QUESTION_TO_EDIT.user
                                                    .id);
                                        } else {
                                            window.location.href =
                                                "{{ route('home') }}";
                                        }
                                    }, 3000);
                                } else {
                                    Toastify({
                                        text: data.message ||
                                            "An unexpected error occurred from the server.",
                                        duration: 3000,
                                        close: true,
                                        gravity: "top",
                                        position: "right",
                                        style: {
                                            background: "#e74c3c"
                                        }
                                    }).showToast();

                                }
                            })
                            .catch(error => {
                                console.error('Submission Error:', error);
                                Swal.close();
                                Toastify({
                                    text: error.message ||
                                        "Submission Error",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#e74c3c"
                                    }
                                }).showToast();
                            });
                    }
                });
            });
            init();
        });
    </script>
@endsection
