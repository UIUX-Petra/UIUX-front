@extends('layout')

@section('head')
    <style>
        /* Core styling variables that match the homepage */
        :root {
            --text-primary: #7494ec;
            --profile-secondary: #5f83c8;
            --text-secondary: #38A3A5;
            --text-secondary-hover: #80ED99;
            --tag-bg: #7494ec;
            --tag-bg-hover: #5f83c8;
            --border-color: #e5e7eb;
            --bg-card: #ffffff;
            --bg-card-hover: #f9fafb;
            --bg-secondary: #f4f6f8;
            --transition-speed: 0.3s;
        }

        /* Profile editing specific styles */
        .edit-profile-container {
            background-color: var(--bg-secondary);
            transition: background-color var(--transition-speed);
        }

        .edit-card {
            background-color: var(--bg-card);
            border-radius: 1rem;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .edit-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .edit-section {
            transition: all 0.3s ease;
        }

        .edit-section:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(to right, var(--text-primary), var(--profile-secondary));
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, var(--profile-secondary), var(--text-primary));
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-accent {
            background: linear-gradient(to right, var(--text-secondary), var(--text-secondary-hover));
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            background: linear-gradient(to right, var(--text-secondary-hover), var(--text-secondary));
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-upload {
            transition: all 0.3s ease;
        }

        .profile-upload:hover {
            transform: scale(1.05);
        }

        .profile-upload-icon {
            opacity: 0;
            transition: all 0.3s ease;
        }

        .profile-upload:hover .profile-upload-icon {
            opacity: 1;
        }

        .form-input {
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background-color: var(--bg-shadow);
        }

        .form-input:focus {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 2px rgba(116, 148, 236, 0.25);
        }

        .form-label {
            transition: all 0.3s ease;
            color: var(--text-primary);
        }

        /* Animated gradient background */
        .background-gradient {
            background-image:
                radial-gradient(at 93% 100%, #7494ec 0px, transparent 50%),
                radial-gradient(at 0% 0%, #633F92 0px, transparent 50%),
                radial-gradient(at 38% 60%, #fffd44 0px, transparent 50%),
                radial-gradient(at 100% 0%, #7494ec 0px, transparent 50%),
                radial-gradient(at 80% 50%, #633F92 0px, transparent 50%),
                radial-gradient(at 0% 100%, #fffd44 0px, transparent 50%);
            background-size: 200% 200%;
            background-repeat: no-repeat;
            animation: gradient 30s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 0%;
            }
            50% {
                background-position: 100% 100%;
            }
            100% {
                background-position: 0% 0%;
            }
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .edit-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
@include('partials.nav')
@include('utils.background3')

<div class="min-h-screen p-4 sm:p-6 lg:p-8 edit-profile-container max-w-[70rem] mx-auto">
    <!-- Main Content -->
    <div class="w-full rounded-xl shadow-lg overflow-hidden edit-card">
        <!-- Profile Header with Cover Photo -->
        <div class="bg-gradient-to-r from-[#38A3A5] to-[#80ED99] h-32 sm:h-48 relative">
            <!-- Edit cover photo button -->
            <button class="absolute bottom-4 right-4 px-3 py-1.5 bg-[var(--bg-)] rounded-lg shadow-md text-sm text-[var(--text-primary)] flex items-center hover:bg-[var(--bg-card-hover)]">
                <i class="fa-solid fa-camera mr-2"></i>
                Edit Cover
            </button>
        </div>

        <!-- Profile Content -->
        <div class="relative px-6 py-8">
            <!-- Profile Picture - Positioned to overlap -->
            <div class="absolute -top-16 left-1/2 transform -translate-x-1/2 sm:left-8 sm:transform-none">
                <div class="relative profile-upload group">
                    <img id="profile-img"
                        src="{{ $user['image'] ? asset('storage/' . $user['image']) : 'https://via.placeholder.com/150' }}"
                        alt="Profile Picture" class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center profile-upload-icon">
                        <i class="fa-solid fa-camera text-white text-2xl"></i>
                    </div>
                    <input type="file" id="profile-input" class="hidden" accept="image/*">
                </div>
            </div>

            <!-- Page Title -->
            <div class="text-center sm:text-left sm:ml-40 mt-16 sm:mt-0">
                <h1 class="text-[var(--text-primary)] text-3xl font-bold cal-sans-regular mb-1">Edit Profile</h1>
                <p class="text-[var(--text-secondary)] text-sm">Update your profile information</p>
            </div>

            <!-- Form Container -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-12 gap-8 edit-grid">
                <!-- Left Column -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Basic Information Card -->
                    <div class="edit-card p-6">
                        <h3 class="text-[var(--text-primary)] text-xl font-bold mb-4 cal-sans-regular">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <!-- Username Input -->
                            <div class="edit-section">
                                <label for="username" class="block text-[var(--text-primary)] font-medium mb-2">Username</label>
                                <input type="text" id="username" name="username"
                                    class="form-input w-full px-4 py-2 rounded-lg focus:outline-none"
                                    value="{{ $user['username'] }}">
                            </div>

                            {{-- <!-- Email Input (Example, add if needed) -->
                            <div class="edit-section">
                                <label for="email" class="block text-[var(--text-primary)] font-medium mb-2">Email</label>
                                <input type="email" id="email" name="email"
                                    class="form-input w-full px-4 py-2 rounded-lg focus:outline-none"
                                    value="{{ $user['email'] ?? 'user@example.com' }}" disabled>
                                <p class="text-sm text-gray-500 mt-1">Email cannot be changed</p>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Skills & Tags Card -->
                    <div class="edit-card p-6">
                        <h3 class="text-[var(--text-primary)] text-xl font-bold mb-4 cal-sans-regular">Skills & Expertise</h3>
                        
                        <div class="space-y-4">
                            <!-- Tags Input -->
                            <div class="edit-section">
                                <label for="tags" class="block text-[var(--text-primary)] font-medium mb-2">Tags</label>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <span class="bg-[var(--tag-bg)] text-white px-3 py-1 rounded-full text-sm flex items-center">
                                        angular
                                        <button class="ml-2 text-white"><i class="fa-solid fa-times"></i></button>
                                    </span>
                                    <span class="bg-[var(--tag-bg)] text-white px-3 py-1 rounded-full text-sm flex items-center">
                                        html
                                        <button class="ml-2 text-white"><i class="fa-solid fa-times"></i></button>
                                    </span>
                                    <span class="bg-[var(--tag-bg)] text-white px-3 py-1 rounded-full text-sm flex items-center">
                                        javascript
                                        <button class="ml-2 text-white"><i class="fa-solid fa-times"></i></button>
                                    </span>
                                </div>
                                <input type="text" id="tags" name="tags" placeholder="Add a tag"
                                    class="form-input w-full px-4 py-2 rounded-lg focus:outline-none">
                                <p class="text-sm text-gray-500 mt-1">Press Enter to add a tag</p>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings Example -->
                    <div class="edit-card p-6">
                        <h3 class="text-[var(--text-primary)] text-xl font-bold mb-4 cal-sans-regular">Privacy Settings</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label for="showEmail" class="text-[var(--text-secondary)]">Show email to others</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="showEmail" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[var(--text-primary)]"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between">
                                <label for="showActivity" class="text-[var(--text-secondary)]">Show activity feed</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="showActivity" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[var(--text-primary)]"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- About Me Card -->
                    <div class="edit-card p-6">
                        <h3 class="text-[var(--text-primary)] text-xl font-bold mb-4 cal-sans-regular">About Me</h3>
                        
                        <div class="edit-section">
                            <textarea id="biodata" name="biodata" rows="8"
                                class="form-input w-full px-4 py-3 rounded-lg focus:outline-none"
                                placeholder="Tell us about yourself...">{{ empty($user['biodata']) ? '' : $user['biodata'] }}</textarea>
                            <p class="text-sm text-gray-500 mt-2">Write a short bio to introduce yourself to the community</p>
                        </div>
                    </div>

                    <!-- Social Links Example -->
                    <div class="edit-card p-6">
                        <h3 class="text-[var(--text-primary)] text-xl font-bold mb-4 cal-sans-regular">Social Links</h3>
                        
                        <div class="space-y-4">
                            <div class="edit-section">
                                <label for="github" class="block text-[var(--text-primary)] font-medium mb-2">
                                    <i class="fa-brands fa-github mr-2"></i>GitHub
                                </label>
                                <input type="text" id="github" name="github"
                                    class="form-input w-full px-4 py-2 rounded-lg focus:outline-none"
                                    placeholder="https://github.com/username">
                            </div>
                            <div class="edit-section">
                                <label for="linkedin" class="block text-[var(--text-primary)] font-medium mb-2">
                                    <i class="fa-brands fa-linkedin mr-2"></i>LinkedIn
                                </label>
                                <input type="text" id="linkedin" name="linkedin"
                                    class="form-input w-full px-4 py-2 rounded-lg focus:outline-none"
                                    placeholder="https://linkedin.com/in/username">
                            </div>
                            <div class="edit-section">
                                <label for="website" class="block text-[var(--text-primary)] font-medium mb-2">
                                    <i class="fa-solid fa-globe mr-2"></i>Website
                                </label>
                                <input type="text" id="website" name="website"
                                    class="form-input w-full px-4 py-2 rounded-lg focus:outline-none"
                                    placeholder="https://yourwebsite.com">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-center sm:justify-end gap-4">
                <a href="{{ route('seeProfile') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700 font-medium text-center transition-all">
                    Cancel
                </a>
                <button type="submit" id="save" class="px-6 py-3 btn-primary text-white rounded-lg font-medium transition-all flex items-center justify-center">
                    <i class="fa-solid fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profileInput = document.getElementById('profile-input');
        const profileImg = document.getElementById('profile-img');
        const saveButton = document.getElementById('save');
        let imageFile = null; // Variable to store the selected image file

        // Trigger file input when the profile container is clicked
        document.querySelector('.profile-upload').addEventListener('click', function() {
            profileInput.click();
        });

        // Handle the image file selection
        profileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                imageFile = file; // Store the selected file
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Set the new image as the profile picture preview
                    profileImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Tags functionality
        const tagsInput = document.getElementById('tags');
        if (tagsInput) {
            tagsInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && tagsInput.value.trim() !== '') {
                    e.preventDefault();
                    const tagValue = tagsInput.value.trim();
                    const tagContainer = tagsInput.previousElementSibling;
                    
                    // Create new tag element
                    const newTag = document.createElement('span');
                    newTag.className = 'bg-[var(--tag-bg)] text-white px-3 py-1 rounded-full text-sm flex items-center';
                    newTag.innerHTML = `${tagValue} <button class="ml-2 text-white"><i class="fa-solid fa-times"></i></button>`;
                    
                    // Add remove functionality
                    newTag.querySelector('button').addEventListener('click', function() {
                        newTag.remove();
                    });
                    
                    // Add to container and clear input
                    tagContainer.appendChild(newTag);
                    tagsInput.value = '';
                }
            });
            
            // Add remove functionality to existing tags
            document.querySelectorAll('.bg-[var(--tag-bg)] button').forEach(button => {
                button.addEventListener('click', function() {
                    button.closest('span').remove();
                });
            });
        }

        saveButton.addEventListener('click', (event) => {
            event.preventDefault();

            const usernameInput = document.getElementById("username");
            const biodataTextArea = document.getElementById("biodata");

            const username = usernameInput.value.trim();
            const biodata = biodataTextArea.value.trim();

            if (username === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in your username!',
                });
                return;
            }

            // Collect tags
            const tags = [];
            document.querySelectorAll('.bg-[var(--tag-bg)]').forEach(tag => {
                const tagText = tag.textContent.trim();
                if (tagText) {
                    tags.push(tagText);
                }
            });

            const formData = new FormData();
            formData.append('username', username);
            formData.append('biodata', biodata);
            
            if (tags.length > 0) {
                formData.append('tags', JSON.stringify(tags));
            }

            if (imageFile) {
                formData.append('image', imageFile);
            }

            // Social links
            const github = document.getElementById('github')?.value.trim() || '';
            const linkedin = document.getElementById('linkedin')?.value.trim() || '';
            const website = document.getElementById('website')?.value.trim() || '';
            
            if (github) formData.append('github', github);
            if (linkedin) formData.append('linkedin', linkedin);
            if (website) formData.append('website', website);

            // Privacy settings
            const showEmail = document.getElementById('showEmail')?.checked || false;
            const showActivity = document.getElementById('showActivity')?.checked || false;
            
            formData.append('show_email', showEmail ? '1' : '0');
            formData.append('show_activity', showActivity ? '1' : '0');

            fetch("{{ route('editProfile.post') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile Updated!',
                        text: 'Your profile has been successfully updated.',
                        showConfirmButton: true,
                        confirmButtonText: 'View Profile',
                        confirmButtonColor: '#7494ec',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('seeProfile') }}";
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Something went wrong.',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                });
                console.error(error);
            });
        });
        
        // Add animations for better UX
        const editSections = document.querySelectorAll('.edit-section');
        editSections.forEach(section => {
            const input = section.querySelector('input, textarea');
            if (input) {
                input.addEventListener('focus', () => {
                    section.style.transform = 'translateY(-5px)';
                });
                input.addEventListener('blur', () => {
                    section.style.transform = '';
                });
            }
        });
    });
</script>
@endsection