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
            scrollbar-color: #6366F1 #e5e7eb;
        }

        #editor::-webkit-scrollbar {
            width: 6px;
        }

        #editor::-webkit-scrollbar-thumb {
            background-color: #6366F1;
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
            background: linear-gradient(to right, #38A3A5, #80ED99);
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .image-upload-button:hover {
            background: linear-gradient(to right, #80ED99, #38A3A5);
            transform: translateY(-2px);
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
            color: white;
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
    </style>
@endsection
@section('content')
    @if (session()->has('Error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('Error') }}'
            });
        </script>
    @endif
    @include('partials.nav')

    @php
        $isEditMode = isset($questionToEdit) && $questionToEdit !== null;
        $formActionUrl = $isEditMode
            ? url(env('API_URL', '') . "/questions/{$questionToEdit['id']}/updatePartial")
            : route('addQuestion');
        $formMethod = 'POST';
        $pageH1Title = $isEditMode ? 'Edit Your Question' : 'Ask a Question';
        $submitButtonText = $isEditMode ? 'Update Question' : 'Publish Question';
    @endphp

    <div class="max-w-5xl mx-auto justify-start items-start px-4 py-8">
        <!-- Page Header Section -->
        <div class="flex items-center gap-4">
            <div class="page-title-icon flex items-center justify-center">
                <i class="fa-solid {{ $isEditMode ? 'fa-pen-to-square' : 'fa-question' }}"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-[var(--text-primary)]">{{ $pageH1Title }}</h1>
                <p class="text-[var(--text-secondary)]">
                    @if ($isEditMode)
                        Update the details of your question.
                    @else
                        Share your problem and get help from the community.
                    @endif
                </p>
            </div>
        </div>

        @if (!$isEditMode)
            <!-- Tips Section (Show only for new questions) -->
            <div class="tips-section p-6 my-8">
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
                            appropriate tags</span></li>
                </ul>
            </div>
        @endif

        <form id="post-form" enctype="multipart/form-data">
            @if ($isEditMode)
                <input type="hidden" id="question_id_for_js" value="{{ $questionToEdit['id'] }}">
            @endif

            <!-- Title Section -->
            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-heading section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Question Title</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">A clear and specific title helps others understand your
                    question quickly</p>
                <input type="text" id="title" name="title" class="input-field p-3 text-black"
                    placeholder="What's your question about?"
                    value="{{ old('title', $isEditMode ? $questionToEdit['title'] ?? '' : '') }}" required>
            </div>

            <!-- Details Section -->
            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-align-left section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Question Details</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">Provide all relevant details</p>
                <div id="editor" class="w-full">
                    <div class="toolbar flex flex-col justify-center gap-4 p-3">
                        <button type="button" id="upload-image-btn"
                            class="image-upload-button w-[200px] flex items-center gap-2 py-2 px-4">
                            <i class="fa-solid fa-image"></i> Add Image
                        </button>
                        <div id="image-preview" class="flex flex-wrap gap-4 mt-4 p-2">
                            @if ($isEditMode && !empty($questionToEdit['image']))
                                <div class="image-preview-item existing-image max-w-[150px]">
                                    <img src="{{ asset('storage/' . $questionToEdit['image']) }}"
                                        alt="Current question image">
                                    <button type="button" class="delete-btn delete-existing-image-btn"
                                        data-image-filename="{{ $questionToEdit['image'] }}">Delete</button>
                                    {{-- <input type="hidden" name="existing_image_filename" value="{{ $questionToEdit['image'] }}"> --}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-4 bg-[var(--bg-secondary)]">
                        <textarea id="question" name="question" rows="8"
                            class="block w-full px-0 text-[var(--text-primary)] bg-transparent border-0 focus:ring-0"
                            placeholder="Describe your question in detail..." required>{{ old('question', $isEditMode ? $questionToEdit['question'] ?? '' : '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tags Section -->
            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-tags section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Tags</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">Select relevant tags</p>
                <div class="flex flex-wrap gap-6 mb-6">
                    @php
                        $selectedTagIdsOnLoad = [];
                        if (
                            $isEditMode &&
                            isset($questionToEdit['group_question']) &&
                            is_array($questionToEdit['group_question'])
                        ) {
                            foreach ($questionToEdit['group_question'] as $group) {
                                if (isset($group['subject']) && isset($group['subject']['id'])) {
                                    $selectedTagIdsOnLoad[] = $group['subject']['id'];
                                }
                            }
                        }
                    @endphp

                    @foreach (['Software Development', 'Data, AI & Analytics', 'Security', 'Others'] as $categoryName)
                        <div class="flex-1 min-w-[200px]">
                            <h3 class="category-title mb-3">{{ $categoryName }}</h3>
                            <div class="flex flex-wrap gap-2">
                                {{-- Loop through $allTags which is passed from controller --}}
                                @if (isset($allTags) && is_array($allTags))
                                    @foreach ($allTags as $tag)
                                        @php
                                            $isInCategory = false;
                                            if (
                                                $categoryName === 'Software Development' &&
                                                in_array($tag['name'], ['Introduction to Programming' /* ... */])
                                            ) {
                                                $isInCategory = true;
                                            } elseif (
                                                $categoryName === 'Data, AI & Analytics' &&
                                                in_array($tag['name'], ['Data Structures and Algorithms' /* ... */])
                                            ) {
                                                $isInCategory = true;
                                            } elseif (
                                                $categoryName === 'Security' &&
                                                in_array($tag['name'], ['Cybersecurity' /* ... */])
                                            ) {
                                                $isInCategory = true;
                                            } elseif (
                                                $categoryName === 'Others' &&
                                                !in_array($tag['name'], [
                                                    /* all other named tags */
                                                ])
                                            ) {
                                                $isInCategory = true;
                                            }
                                        @endphp

                                        @if ($isInCategory)
                                            <a href="#" id="tag-btn-{{ $tag['id'] }}"
                                                data-tag-id="{{ $tag['id'] }}"
                                                class="tag-button {{ in_array($tag['id'], $selectedTagIdsOnLoad) ? 'tab-active' : 'tab-inactive' }} py-2 px-4">
                                                {{ $tag['name'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                @else
                                    <p class="text-xs text-red-500">Error: Tags not available.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <button type="submit" id="submit-btn"
                    class="submit-button inline-flex items-center justify-center gap-2 py-3 px-8 mt-4">
                    <i class="fa-solid {{ $isEditMode ? 'fa-save' : 'fa-paper-plane' }}"></i> {{ $submitButtonText }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        const IS_EDIT_MODE = {{ isset($questionToEdit) && $questionToEdit !== null ? 'true' : 'false' }};
        const QUESTION_TO_EDIT = @json($questionToEdit ?? null);
        const ALL_TAGS_FROM_PHP = @json($allTags ?? []);
        const CSRF_TOKEN = "{{ csrf_token() }}";

        const FORM_ACTION_URL = IS_EDIT_MODE ?
            "{{ route('question.saveEdit', ['id' => $questionToEdit['id'] ?? 'ERROR_NO_ID_FOR_ROUTE']) }}" :
            "{{ route('addQuestion') }}";

        let selectedTagIds = [];
        let imageFile = null;

        document.addEventListener('DOMContentLoaded', function() {
            if (IS_EDIT_MODE && QUESTION_TO_EDIT) {
                document.getElementById('title').value = QUESTION_TO_EDIT.title || '';
                document.getElementById('question').value = QUESTION_TO_EDIT.question || '';

                if (QUESTION_TO_EDIT.group_question && Array.isArray(QUESTION_TO_EDIT.group_question)) {
                    QUESTION_TO_EDIT.group_question.forEach(group => {
                        if (group.subject && group.subject.id) {
                            const tagId = group.subject.id;
                            if (!selectedTagIds.includes(tagId)) {
                                selectedTagIds.push(tagId);
                            }
                            const tagButton = document.getElementById(`tag-btn-${tagId}`);
                            if (tagButton) {
                                tagButton.classList.remove('tab-inactive');
                                tagButton.classList.add('tab-active');
                            }
                        }
                    });
                }
            }

            document.querySelectorAll('.tag-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tagId = this.dataset.tagId;

                    if (selectedTagIds.includes(tagId)) {
                        selectedTagIds = selectedTagIds.filter(id => id !== tagId);
                        this.classList.remove('tab-active');
                        this.classList.add('tab-inactive');
                    } else {
                        selectedTagIds.push(tagId);
                        this.classList.remove('tab-inactive');
                        this.classList.add('tab-active');
                    }
                });
            });

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
                                    existingImageDiv.style.display =
                                        'flex';
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
                        const form = document.getElementById('post-form');
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

            const form = document.getElementById('post-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const title = document.getElementById("title").value.trim();
                const questionText = document.getElementById("question").value.trim();

                if (title === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Title must be filled out!'
                    });
                    return;
                }
                if (questionText === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Question details must be filled out!'
                    });
                    return;
                }
                if (selectedTagIds.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select at least one tag!'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Once ${IS_EDIT_MODE ? 'updated' : 'submitted'}, this action might not be easily undone!`,
                    icon: 'warning',
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

                        selectedTagIds.forEach(tagId => {
                            formData.append("subject_id[]", tagId);
                        });

                        if (imageFile) {
                            formData.append("image", imageFile);
                        } else if (IS_EDIT_MODE && document.getElementById(
                                'remove_existing_image_input')) {
                            formData.append("remove_existing_image", "1");
                        }
                        const headers = {
                            "X-CSRF-TOKEN": CSRF_TOKEN,
                            "Accept": "application/json",
                        };

                        fetch(FORM_ACTION_URL, {
                                method: "POST",
                                headers: headers,
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(errData => {
                                        throw {
                                            status: response.status,
                                            data: errData
                                        };
                                    });
                                }
                                return response.json();
                            })
                            .then(res => {
                                Swal.close();
                                if (res.success || (res.data && res.data.id)) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: res.message ||
                                            `Question ${IS_EDIT_MODE ? 'updated' : 'submitted'} successfully!`
                                    }).then(() => {
                                        if (res.data && res.data.id) {
                                            window.location.href =
                                                `/ask/${res.data.id}`;
                                        } else {
                                            window.location.href =
                                                "{{ route('askPage') }}";
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: res.message ||
                                            'An unexpected error occurred from the server.'
                                    });
                                }
                            })
                            .catch(err => {
                                console.error('Fetch Error:', err);
                                Swal.close();
                                let errorMessage =
                                    'There was an error processing your request.';
                                if (err.data && err.data.message) {
                                    errorMessage = err.data.message;
                                } else if (err.message) {
                                    errorMessage = err.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Submission Error',
                                    text: errorMessage
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
