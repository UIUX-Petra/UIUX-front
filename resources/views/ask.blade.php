@extends('layout')
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
        <!-- Page Header Section -->
        <div class="flex items-center gap-4">
                <div class="page-title-icon flex items-center justify-center">
                    <i class="fa-solid fa-question"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-[var(--text-primary)]">Ask a Question</h1>
                    <p class="text-[var(--text-secondary)]">Share your problem and get help from the community</p>
                </div>
            </div>

         <!-- Tips Section -->
        <div class="tips-section p-6 my-8">
            <div class="tips-title flex items-center gap-2 mb-4">
                <i class="fa-solid fa-lightbulb"></i>
                <span>Tips for a Great Question</span>
            </div>
            <ul class="tips-list">
                <li class="flex items-start gap-2 mb-2">
                    <i class="fa-solid fa-check-circle mt-1"></i>
                    <span>Be specific and clear about your problem</span>
                </li>
                <li class="flex items-start gap-2 mb-2">
                    <i class="fa-solid fa-check-circle mt-1"></i>
                    <span>Include relevant code snippets or error messages</span>
                </li>
                <li class="flex items-start gap-2 mb-2">
                    <i class="fa-solid fa-check-circle mt-1"></i>
                    <span>Explain what you've already tried to solve the problem</span>
                </li>
                <li class="flex items-start gap-2 mb-2">
                    <i class="fa-solid fa-check-circle mt-1"></i>
                    <span>Select appropriate tags to reach the right audience</span>
                </li>
            </ul>
        </div>

        <!-- Main Form Container -->
        <form id="post-form" enctype="multipart/form-data">
            <!-- Title Section -->
            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-heading section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Question Title</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">A clear and specific title helps others understand your question quickly</p>
                <input type="text" id="title" class="input-field p-3" placeholder="What's your question about?" required>
            </div>

            <!-- Details Section -->
            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-align-left section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Question Details</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">Provide all relevant details to help others understand your problem</p>
                
                <!-- Editor -->
                <div id="editor" class="w-full">
                    <div class="toolbar flex items-center gap-4 p-3">
                        <button type="button" id="upload-image-btn" class="image-upload-button flex items-center gap-2 py-2 px-4">
                            <i class="fa-solid fa-image"></i> Add Image
                        </button>
                    </div>
                    <div class="p-4 bg-[var(--bg-secondary)]">
                        <textarea id="question" rows="8" class="block w-full px-0 text-[var(--text-primary)] bg-transparent border-0 focus:ring-0" placeholder="Describe your question in detail..." required></textarea>

                        <!-- Image Preview Section -->
                        <div id="image-preview" class="flex flex-wrap gap-4 mt-4 p-2">
                            <!-- Images will be dynamically added here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tags Section -->
            <div class="p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fa-solid fa-tags section-icon mr-3"></i>
                    <h2 class="text-lg font-semibold">Tags</h2>
                </div>
                <p class="text-[var(--text-secondary)] text-sm mb-4">Select relevant tags to categorize your question and reach the right audience</p>
                
                <div class="flex flex-wrap gap-6 mb-6">
                    <!-- Programming Languages Category -->
                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Software Development</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (in_array($dat['name'], [
                                    'Introduction to Programming', 'Software Engineering', 'Web Development', 'Mobile App Development',
                                    'Game Development', 'Embedded Systems', 'Smart Devices and Sensors', 'Cloud Application Development',
                                    'Cloud Computing', 'Cloud Storage and Virtualization', 'Distributed Systems', 'Operating Systems',
                                    'Computer Networks', 'Advanced Networking', 'Digital Logic Design', 'Computer Graphics', 'Cloud Infrastructure'
                                ]))
                                    <a id="{{ $dat['name'] }}" class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Frameworks Category -->
                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Data, AI & Analytics</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (in_array($dat['name'], [
                                    'Data Structures and Algorithms', 'Database Systems', 'Advanced Database Systems', 'Data Analytics', 'Advanced Data Analytics',
                                    'Data Science', 'Data Mining', 'Big Data', 'Machine Learning', 'Advanced Machine Learning',
                                    'Artificial Intelligence', 'Advanced Artificial Intelligence', 'Artificial Neural Networks',
                                    'Computer Vision', 'Natural Language Processing', 'Business Intelligence', 'Data Warehousing'
                                ]))
                                    <a id="{{ $dat['name'] }}" class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Course Subjects Category -->
                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Security</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (in_array($dat['name'], [
                                    'Cybersecurity', 'Ethical Hacking', 'Cryptography', 'Digital Forensics', 'Web Security', 'AI Ethics',
                                    'Blockchain Technology', 'Quantum Computing', 'Virtual Reality', 'Smart Cities', 'Internet of Things (IoT)',
                                    'Computational Biology', 'Robotics'
                                ]))
                                    <a id="{{ $dat['name'] }}" class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Others Category -->
                    <div class="flex-1 min-w-[200px]">
                        <h3 class="category-title mb-3">Others</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($data as $dat)
                                @if (!in_array($dat['name'], [
                                    'Introduction to Programming', 'Software Engineering', 'Web Development', 'Mobile App Development',
                                    'Game Development', 'Embedded Systems', 'Smart Devices and Sensors', 'Cloud Application Development',
                                    'Cloud Computing', 'Cloud Storage and Virtualization', 'Distributed Systems', 'Operating Systems',
                                    'Computer Networks', 'Advanced Networking', 'Digital Logic Design', 'Computer Graphics', 'Cloud Infrastructure',

                                    'Data Structures and Algorithms', 'Database Systems', 'Advanced Database Systems', 'Data Analytics', 'Advanced Data Analytics',
                                    'Data Science', 'Data Mining', 'Big Data', 'Machine Learning', 'Advanced Machine Learning',
                                    'Artificial Intelligence', 'Advanced Artificial Intelligence', 'Artificial Neural Networks',
                                    'Computer Vision', 'Natural Language Processing', 'Business Intelligence', 'Data Warehousing',

                                    'Cybersecurity', 'Ethical Hacking', 'Cryptography', 'Digital Forensics', 'Web Security', 'AI Ethics',
                                    'Blockchain Technology', 'Quantum Computing', 'Virtual Reality', 'Smart Cities', 'Internet of Things (IoT)',
                                    'Computational Biology', 'Robotics'
                                ]))

                                    <a id="{{ $dat['name'] }}" class="tab-inactive py-2 px-4">{{ $dat['name'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Section -->
            <div class="text-center">
                <button type="submit" id="submit-btn" class="submit-button inline-flex items-center justify-center gap-2 py-3 px-8 mt-4">
                    <i class="fa-solid fa-paper-plane"></i> Publish Question
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        let selectedTags = [];

        document.querySelectorAll('.tab-inactive').forEach((button) => {
            button.addEventListener('click', function() {
                const tagName = this.id;

                if (selectedTags.includes(tagName)) { // ga jadi pick tag
                    selectedTags = selectedTags.filter(tag => tag !== tagName);
                    this.className = 'tab-inactive py-2 px-4';

                } else { // pick tag
                    selectedTags.push(tagName);
                    this.className = 'tab-active py-2 px-4';
                }
            });
        });

        let imageFile = null; // Store the single image file

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
                        });

                        imagePreviewContainerNew.appendChild(deleteBtn);

                        // Append the preview item to the preview container
                        imagePreviewContainer.appendChild(imagePreviewContainerNew);

                        // Store the file
                        imageFile = file;
                    };
                    reader.readAsDataURL(file);
                }
            });
            fileInput.click(); // Trigger the file input click event
        });

        // Form submission
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("submit-btn").addEventListener("click", function(e) {
                e.preventDefault();

                const question = document.getElementById("question").value;
                const title = document.getElementById("title").value;

                // Validate input
                if (question === '' && title === '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Question must be filled out!'
                    });
                    return;
                }

                //CEK APA SAJA TAGS yang ada
                let tags = <?php echo json_encode($data); ?>;

                selectedTags = selectedTags.map(tagName => {
                    const matchingTag = tags.find(tag => tag.name === tagName);
                    return matchingTag ? matchingTag.id :
                        tagName;
                });

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Once submitted, you cannot undo this action!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Submit!',
                    cancelButtonText: 'No, Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Submitting...",
                            text: "Please wait while we submit your post.",
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        let formData = new FormData();
                        formData.append("title", title);
                        formData.append("question", question);

                        selectedTags.forEach(tagId => {
                            formData.append("subject_id[]", tagId);
                        });

                        if (imageFile) {
                            formData.append("image", imageFile);
                        }
                        fetch("{{ route('addQuestion') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(res => {
                                Swal.close();

                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Your post has been successfully submitted!'
                                    }).then(() => {
                                        window.location.href = "{{ route('askPage') }}";
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: res.message
                                    });
                                }
                            })
                            .catch(err => {
                                console.error('Fetch Error:', err);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'There was an error while submitting your post.'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection