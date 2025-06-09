@extends('layout')
@section('content')
    @if (session()->has('Error'))
        <script>
            Toastify({
                text: "{{ session('Error') }}" || "An unexpected error occurred from the server.",
                duration: 3000,
                style: {
                    background: "#e74c3c"
                }
            }).showToast();
        </script>
    @endif

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
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .input-field:focus {
            border-color: var(--accent-tertiary);
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
            width: 150px;
            height: 150px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 24px;
            height: 24px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            padding: 0;
            font-size: 12px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .delete-btn:hover {
            background-color: rgba(255, 0, 0, 0.9);
            transform: scale(1.1);
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

    @include('partials.nav')

    <div class="max-w-5xl justify-start items-start px-4 py-8">
        <div class="flex items-center gap-4">
            <div class="page-title-icon flex items-center justify-center">
                <i class="fa-solid fa-question"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-[var(--text-primary)]">Edit Question</h1>
                <p class="text-[var(--text-secondary)]">Update your question and clarify your problem</p>
            </div>
        </div>

        <div class="tips-section p-6 my-8">
            <div class="tips-title flex items-center gap-2 mb-4">
                <i class="fa-solid fa-lightbulb"></i>
                <span>Tips for Editing Your Question</span>
            </div>
            <ul class="tips-list">
                <li class="flex items-start gap-2 mb-2">
                    <i class="fa-solid fa-check-circle mt-1"></i>
                    <span>Ensure your edits clearly address the problem</span>
                </li>
                <li class="flex items-start gap-2 mb-2">
                    <i class="fa-solid fa-check-circle mt-1"></i>
                    <span>Update code snippets or error messages if they've changed</span>
                </li>
                <li class="flex items-start gap-2 mb-2">
                    <i class="fa-solid fa-check-circle mt-1"></i>
                    <span>Verify that your selected tags are still accurate</span>
                </li>
            </ul>
        </div>

        <form id="edit-post-form" enctype="multipart/form-data">
            <input type="hidden" id="question-id" value="{{ $question->id }}">

            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-heading section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Question Title</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">A clear and specific title helps others understand your
                    question quickly</p>
                <input type="text" id="title" class="input-field p-3" placeholder="What's your question about?"
                    value="{{ $question->title }}" required>
            </div>

            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-align-left section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Question Details</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">Provide all relevant details to help others understand
                    your problem</p>

                <div id="editor" class="w-full">
                    <div class="toolbar flex items-center gap-4 p-3">
                        <button type="button" id="upload-image-btn"
                            class="image-upload-button flex items-center gap-2 py-2 px-4">
                            <i class="fa-solid fa-image"></i> Add Image
                        </button>
                    </div>
                    <div class="p-4 bg-[var(--bg-secondary)]">
                        <textarea id="question-content" rows="8"
                            class="block w-full px-0 text-[var(--text-primary)] bg-transparent border-0 focus:ring-0"
                            placeholder="Describe your question in detail..." required>{{ $question->question }}</textarea>

                        <div id="image-preview" class="flex flex-wrap gap-4 mt-4 p-2">
                            @if ($question->image)
                                <div class="image-preview-item">
                                    <img src="{{ $question->image_url }}" alt="Question Image">
                                    <button type="button" class="delete-btn"
                                        data-image-url="{{ $question->image }}">X</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-tags section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Tags</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">Select relevant tags to categorize your question and
                    reach the right audience</p>

                <div class="flex flex-wrap gap-6 mb-6">
                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Software Development</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (in_array($dat['name'], [
                                        'Introduction to Programming',
                                        'Software Engineering',
                                        'Web Development',
                                        'Mobile App Development',
                                        'Game Development',
                                        'Embedded Systems',
                                        'Smart Devices and Sensors',
                                        'Cloud Application Development',
                                        'Cloud Computing',
                                        'Cloud Storage and Virtualization',
                                        'Distributed Systems',
                                        'Operating Systems',
                                        'Computer Networks',
                                        'Advanced Networking',
                                        'Digital Logic Design',
                                        'Computer Graphics',
                                        'Cloud Infrastructure',
                                    ]))
                                    <a id="{{ $dat['name'] }}" data-tag-id="{{ $dat['id'] }}"
                                        class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Data, AI & Analytics</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (in_array($dat['name'], [
                                        'Data Structures and Algorithms',
                                        'Database Systems',
                                        'Advanced Database Systems',
                                        'Data Analytics',
                                        'Advanced Data Analytics',
                                        'Data Science',
                                        'Data Mining',
                                        'Big Data',
                                        'Machine Learning',
                                        'Advanced Machine Learning',
                                        'Artificial Intelligence',
                                        'Advanced Artificial Intelligence',
                                        'Artificial Neural Networks',
                                        'Computer Vision',
                                        'Natural Language Processing',
                                        'Business Intelligence',
                                        'Data Warehousing',
                                    ]))
                                    <a id="{{ $dat['name'] }}" data-tag-id="{{ $dat['id'] }}"
                                        class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Security</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (in_array($dat['name'], [
                                        'Cybersecurity',
                                        'Ethical Hacking',
                                        'Cryptography',
                                        'Digital Forensics',
                                        'Web Security',
                                        'AI Ethics',
                                        'Blockchain Technology',
                                        'Quantum Computing',
                                        'Virtual Reality',
                                        'Smart Cities',
                                        'Internet of Things (IoT)',
                                        'Computational Biology',
                                        'Robotics',
                                    ]))
                                    <a id="{{ $dat['name'] }}" data-tag-id="{{ $dat['id'] }}"
                                        class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Others</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (
                                    !in_array($dat['name'], [
                                        'Introduction to Programming',
                                        'Software Engineering',
                                        'Web Development',
                                        'Mobile App Development',
                                        'Game Development',
                                        'Embedded Systems',
                                        'Smart Devices and Sensors',
                                        'Cloud Application Development',
                                        'Cloud Computing',
                                        'Cloud Storage and Virtualization',
                                        'Distributed Systems',
                                        'Operating Systems',
                                        'Computer Networks',
                                        'Advanced Networking',
                                        'Digital Logic Design',
                                        'Computer Graphics',
                                        'Cloud Infrastructure',
                                
                                        'Data Structures and Algorithms',
                                        'Database Systems',
                                        'Advanced Database Systems',
                                        'Data Analytics',
                                        'Advanced Data Analytics',
                                        'Data Science',
                                        'Data Mining',
                                        'Big Data',
                                        'Machine Learning',
                                        'Advanced Machine Learning',
                                        'Artificial Intelligence',
                                        'Advanced Artificial Intelligence',
                                        'Artificial Neural Networks',
                                        'Computer Vision',
                                        'Natural Language Processing',
                                        'Business Intelligence',
                                        'Data Warehousing',
                                
                                        'Cybersecurity',
                                        'Ethical Hacking',
                                        'Cryptography',
                                        'Digital Forensics',
                                        'Web Security',
                                        'AI Ethics',
                                        'Blockchain Technology',
                                        'Quantum Computing',
                                        'Virtual Reality',
                                        'Smart Cities',
                                        'Internet of Things (IoT)',
                                        'Computational Biology',
                                        'Robotics',
                                    ]))
                                    <a id="{{ $dat['name'] }}" data-tag-id="{{ $dat['id'] }}"
                                        class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" id="update-btn"
                    class="submit-button inline-flex items-center justify-center gap-2 py-3 px-8 mt-4">
                    <i class="fa-solid fa-paper-plane"></i> Update Question
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        let selectedTags = [];
        let imageFile = null; // Store the new image file
        let existingImageUrl = "{{ $question->image_url }}"; // Store existing image URL
        let deletedImage = false; // Flag to track if the existing image is deleted

        // Initialize selected tags based on the question data
        document.addEventListener("DOMContentLoaded", function() {
            const questionTags = {!! json_encode($question->subjects->pluck('name')) !!};
            questionTags.forEach(tagName => {
                const button = document.getElementById(tagName);
                if (button) {
                    button.classList.remove('tab-inactive');
                    button.classList.add('tab-active');
                    selectedTags.push(button.dataset.tagId); // Store tag ID
                }
            });

            // Initialize image preview for existing image
            if (existingImageUrl) {
                const imagePreviewContainer = document.getElementById("image-preview");
                imagePreviewContainer.innerHTML =
                    ""; // Clear existing content to prevent duplicates if somehow there's something
                const imagePreviewItem = document.createElement("div");
                imagePreviewItem.classList.add("image-preview-item");

                const image = document.createElement("img");
                image.src = existingImageUrl;
                imagePreviewItem.appendChild(image);

                const deleteBtn = document.createElement("button");
                deleteBtn.textContent = "X";
                deleteBtn.classList.add("delete-btn");
                deleteBtn.addEventListener("click", function() {
                    imagePreviewItem.remove();
                    imageFile = null; // Clear new image file if any
                    existingImageUrl = null; // Clear existing image URL
                    deletedImage = true; // Set flag to indicate image deletion
                });

                imagePreviewItem.appendChild(deleteBtn);
                imagePreviewContainer.appendChild(imagePreviewItem);
            }
        });


        document.querySelectorAll('.tab-inactive, .tab-active').forEach((button) => {
            button.addEventListener('click', function() {
                const tagId = this.dataset.tagId; // Get tag ID from data attribute

                if (selectedTags.includes(tagId)) { // ga jadi pick tag
                    selectedTags = selectedTags.filter(tag => tag !== tagId);
                    this.classList.remove('tab-active');
                    this.classList.add('tab-inactive');
                } else { // pick tag
                    selectedTags.push(tagId);
                    this.classList.remove('tab-inactive');
                    this.classList.add('tab-active');
                }
            });
        });

        // Image upload and preview
        document.getElementById("upload-image-btn").addEventListener("click", function() {
            let fileInput = document.createElement("input");
            fileInput.type = "file";
            fileInput.accept = "image/*";
            fileInput.addEventListener("change", function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Remove any existing preview
                        const imagePreviewContainer = document.getElementById("image-preview");
                        imagePreviewContainer.innerHTML = ""; // Clear previous image

                        // Create a new image preview item
                        const imagePreviewContainerNew = document.createElement("div");
                        imagePreviewContainerNew.classList.add("image-preview-item");

                        const image = document.createElement("img");
                        image.src = e.target.result;
                        imagePreviewContainerNew.appendChild(image);

                        // Create a delete button for this image
                        const deleteBtn = document.createElement("button");
                        deleteBtn.textContent = "X";
                        deleteBtn.classList.add("delete-btn");
                        deleteBtn.addEventListener("click", function() {
                            imagePreviewContainerNew.remove();
                            imageFile = null; // Reset the imageFile variable
                            existingImageUrl =
                                null; // Ensure existing image is removed if new one was there
                            deletedImage = true; // Set flag to indicate image deletion
                        });

                        imagePreviewContainerNew.appendChild(deleteBtn);

                        // Append the preview item to the preview container
                        imagePreviewContainer.appendChild(imagePreviewContainerNew);

                        // Store the new file and clear existing image info
                        imageFile = file;
                        existingImageUrl = null;
                        deletedImage = false; // Reset deleted flag if new image is uploaded
                    };
                    reader.readAsDataURL(file);
                }
            });
            fileInput.click(); // Trigger the file input click event
        });

        // Form submission
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("update-btn").addEventListener("click", function(e) {
                e.preventDefault();

                const questionId = document.getElementById("question-id").value;
                const title = document.getElementById("title").value;
                const questionContent = document.getElementById("question-content").value;

                // Validate input
                if (title.trim() === '' || questionContent.trim() === '') {
                    Toastify({
                        text: 'Title and question content must be filled out!',
                        duration: 3000,
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to update this question?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Update!',
                    cancelButtonText: 'No, Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Updating...",
                            text: "Please wait while we update your question.",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        let formData = new FormData();
                        formData.append("title", title);
                        formData.append("question", questionContent);
                        formData.append("_method", "PUT"); // Use PUT method for update

                        selectedTags.forEach(tagId => {
                            formData.append("subject_id[]", tagId);
                        });

                        if (imageFile) {
                            formData.append("image", imageFile);
                        } else if (deletedImage) {
                            formData.append("image",
                                ""); // Send an empty string or null to indicate image removal
                        }
                        // If imageFile is null and deletedImage is false, it means existing image is kept, so no need to append image

                        fetch(`/questions/${questionId}`, { // Assuming your API route for update is /questions/{id}
                                method: "POST", // Method override for PUT request
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(res => {
                                Swal.close();

                                if (res.success) {
                                    Toastify({
                                        text: 'Your question has been successfully updated!',
                                        duration: 3000,
                                        style: {
                                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                                        }
                                    }).showToast();

                                    setTimeout((result) => {
                                        window.location.href =
                                            "{{ route('askPage') }}"; // Redirect to relevant page
                                    }, 3000);
                                } else {
                                    Toastify({
                                        text: res.message ||
                                            'There was an error updating your question.',
                                        duration: 3000,
                                        style: {
                                            background: "#e74c3c"
                                        }
                                    }).showToast();
                                }
                            })
                            .catch(err => {
                                console.error('Fetch Error:', err);
                                Toastify({
                                    text: 'There was an error while updating your question.',
                                    duration: 3000,
                                    style: {
                                        background: "#e74c3c"
                                    }
                                }).showToast();
                            });
                    }
                });
            });
        });
    </script>
@endsection
