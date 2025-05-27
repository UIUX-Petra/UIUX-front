@extends('layout')

@section('style')
    <style>
        .form-container {
            max-width: 48rem;
            margin-left: auto;
            margin-right: auto;
            padding: 2rem 1rem;
            color: var(--text-primary);
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-title-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--button-primary);
            color: var(--button-text);
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: bold;
            font-family: 'Cal Sans', sans-serif;
            color: var(--text-primary);
        }

        .form-section {
            background-color: var(--bg-card);
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            transition: box-shadow 0.3s ease;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-section:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            background-color: var(--bg-card-hover);
        }

        .input-label {
            font-weight: 600;
            color: var(--text-primary);
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .textarea-field {
            width: 100%;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            padding: 0.75rem 1rem;
            min-height: 180px;
            resize: vertical;
        }

        .textarea-field:focus {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(56, 163, 165, 0.3);
            outline: none;
        }

        .image-upload-button {
            background: var(--button-primary);
            color: var(--button-text);
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .image-upload-button:hover {
            background: var(--button-primary-trf);
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px dashed var(--border-color);
            background-color: var(--bg-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .delete-image-btn {
            position: absolute;
            top: 0.3rem;
            right: 0.3rem;
            width: 22px;
            height: 22px;
            background-color: rgba(239, 68, 68, 0.75);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .delete-image-btn:hover {
            background-color: rgba(220, 38, 38, 1);
            transform: scale(1.1);
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
            margin-top: 1.5rem;
        }

        .submit-button {
            background: var(--button-primary);
            color: var(--button-text);
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }

        .submit-button:hover {
            background: var(--button-primary-trf);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .cancel-button {
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
        }

        .cancel-button:hover {
            background-color: var(--bg-card-hover);
            border-color: var(--text-secondary);
            color: var(--text-primary);
        }

        .helper-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-container {
                padding: 1rem 0.5rem;
            }
            
            .form-actions {
                flex-direction: column-reverse;
                gap: 0.75rem;
            }
            
            .submit-button,
            .cancel-button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    @include('partials.nav')

    <div class="form-container">
        <div class="form-header">
            <div class="form-title-icon">
                <i class="fas fa-edit"></i>
            </div>
            <h1 class="form-title">{{ $title ?? 'Edit Answer' }}</h1>
        </div>

        <form id="edit-answer-form" enctype="multipart/form-data">
            <div class="form-section">
                <label for="answer_content_input" class="input-label">Your Answer:</label>
                <textarea 
                    id="answer_content_input" 
                    name="answer_content" 
                    rows="10" 
                    class="textarea-field" 
                    required
                    placeholder="Enter your answer here..."
                >{{ old('answer_content', $answer['answer'] ?? '') }}</textarea>
            </div>

            <div class="form-section">
                <label for="answer_image_upload_btn" class="input-label">Update Image (Optional):</label>
                <button type="button" id="answer_image_upload_btn" class="image-upload-button">
                    <i class="fas fa-image" style="margin-right: 0.5rem;"></i>Choose New Image
                </button>
                <div id="answer-image-preview-container" class="image-preview-container">
                    @if (!empty($answer['image']))
                        <div class="image-preview-item existing-image-item">
                            <img src="{{ asset('storage/' . $answer['image']) }}" alt="Current answer image">
                            <button type="button" class="delete-image-btn delete-existing-image-btn"
                                title="Remove current image">&times;</button>
                        </div>
                    @endif
                </div>
                <p class="helper-text">Uploading a new image will replace the current one. Click 'X' on an image to remove it.</p>
            </div>

            <div class="form-actions">
                <a href="{{ route('user.answers.index', ['userId' => $answer['user_id']]) }}" class="cancel-button">
                    Cancel
                </a>
                <button type="submit" id="submit-edit-answer-btn" class="submit-button">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const CSRF_TOKEN = "{{ csrf_token() }}";
            const answerId = "{{ $answer['id'] }}";
            const answerUserId = "{{ $answer['user_id'] }}";

            let newImageFile = null;
            let removeExistingImageFlag = false;

            const form = document.getElementById('edit-answer-form');
            const answerContentInput = document.getElementById('answer_content_input');
            const imageUploadBtn = document.getElementById('answer_image_upload_btn');
            const imagePreviewContainer = document.getElementById('answer-image-preview-container');
            const existingImageItem = imagePreviewContainer.querySelector('.existing-image-item');

            // Image Upload Handler
            if (imageUploadBtn) {
                imageUploadBtn.addEventListener('click', function() {
                    const fileInput = document.createElement('input');
                    fileInput.type = 'file';
                    fileInput.name = 'image';
                    fileInput.accept = 'image/jpeg,image/png,image/jpg';
                    
                    fileInput.onchange = event => {
                        const file = event.target.files[0];
                        if (file) {
                            newImageFile = file;
                            removeExistingImageFlag = false;

                            // Remove previous new image preview if any
                            const oldNewPreview = imagePreviewContainer.querySelector('.new-image-preview-item');
                            if (oldNewPreview) oldNewPreview.remove();

                            // Hide existing image preview if present
                            if (existingImageItem) existingImageItem.style.display = 'none';

                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewDiv = document.createElement('div');
                                previewDiv.className = 'image-preview-item new-image-preview-item';
                                
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                
                                const deleteBtn = document.createElement('button');
                                deleteBtn.type = 'button';
                                deleteBtn.innerHTML = '&times;';
                                deleteBtn.className = 'delete-image-btn delete-new-image-btn';
                                deleteBtn.title = 'Remove this new image';
                                deleteBtn.onclick = () => {
                                    previewDiv.remove();
                                    newImageFile = null;
                                    if (existingImageItem) {
                                        existingImageItem.style.display = 'flex';
                                    }
                                };
                                
                                previewDiv.appendChild(img);
                                previewDiv.appendChild(deleteBtn);
                                imagePreviewContainer.appendChild(previewDiv);
                            };
                            reader.readAsDataURL(file);
                        }
                    };
                    fileInput.click();
                });
            }

            // Handle deletion of existing image
            if (existingImageItem) {
                const deleteExistingBtn = existingImageItem.querySelector('.delete-existing-image-btn');
                if (deleteExistingBtn) {
                    deleteExistingBtn.addEventListener('click', function() {
                        existingImageItem.style.display = 'none';
                        removeExistingImageFlag = true;
                        newImageFile = null;
                        
                        const oldNewPreview = imagePreviewContainer.querySelector('.new-image-preview-item');
                        if (oldNewPreview) oldNewPreview.remove();
                    });
                }
            }

            // Form Submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const answerText = answerContentInput.value.trim();

                    if (answerText.length < 5) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Answer content must be at least 5 characters long.',
                            background: 'var(--bg-card)',
                            color: 'var(--text-primary)'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Confirm Update',
                        text: 'Are you sure you want to save these changes?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: 'var(--accent-primary)',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, Update Answer!',
                        background: 'var(--bg-card)',
                        color: 'var(--text-primary)',
                        customClass: {
                            popup: 'rounded-lg shadow-xl border border-[var(--border-color)]'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Updating...",
                                text: "Please wait while your answer is being updated.",
                                allowOutsideClick: false,
                                background: 'var(--bg-card)',
                                color: 'var(--text-primary)',
                                didOpen: () => Swal.showLoading()
                            });

                            const formData = new FormData();
                            formData.append('answer_content', answerText);

                            if (newImageFile) {
                                formData.append('image', newImageFile);
                            } else if (removeExistingImageFlag) {
                                formData.append('remove_existing_image', '1');
                            }
                            
                            formData.append('_token', CSRF_TOKEN);

                            const frontendUpdateUrl = "{{ route('user.answers.update', ['answerId' => $answer['id']]) }}";

                            fetch(frontendUpdateUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': CSRF_TOKEN,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(res => {
                                Swal.close();
                                if (res.success) {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: res.message || 'Answer updated successfully!',
                                        icon: 'success',
                                        background: 'var(--bg-card)',
                                        color: 'var(--text-primary)'
                                    }).then(() => {
                                        window.location.href = "{{ route('user.answers.index', ['userId' => $answer['user_id']]) }}";
                                    });
                                } else {
                                    let errorText = res.message || 'An unknown error occurred.';
                                    if (res.errors) {
                                        errorText += '<ul class="text-left mt-2">';
                                        for (const field in res.errors) {
                                            res.errors[field].forEach(errorMessage => {
                                                errorText += `<li>${errorMessage}</li>`;
                                            });
                                        }
                                        errorText += '</ul>';
                                    }
                                    Swal.fire({
                                        title: 'Update Failed!',
                                        html: errorText,
                                        icon: 'error',
                                        background: 'var(--bg-card)',
                                        color: 'var(--text-primary)'
                                    });
                                }
                            })
                            .catch(err => {
                                Swal.close();
                                console.error('Fetch Error:', err);
                                Swal.fire({
                                    title: 'Request Error!',
                                    text: 'There was a problem submitting your request. Please check your connection and try again.',
                                    icon: 'error',
                                    background: 'var(--bg-card)',
                                    color: 'var(--text-primary)'
                                });
                            });
                        }
                    });
                });
            }
        });
    </script>
@endsection