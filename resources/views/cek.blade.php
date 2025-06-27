
@section('script')
    @include('utils.trie')
    <script>
        // --- Functions from home.blade.php to be added ---
        function initClickableQuestionCards() {
            document.querySelectorAll('.question-card').forEach(card => {
                if (card.dataset.clickableInitialized === 'true') return;

                card.addEventListener('click', function(event) {
                    if (event.target.closest('.save-question-btn') ||
                        event.target.closest('.question-tag-link') ||
                        event.target.closest('.more-tags-button')) {
                        return;
                    }
                    const url = this.dataset.url;
                    if (url) {
                        window.location.href = url;
                    }
                });
                card.dataset.clickableInitialized = 'true';
            });
        }

        function initTagToggles() {
            document.querySelectorAll('.more-tags-button').forEach(button => {
                if (button.dataset.toggleInitialized === 'true') return;

                button.addEventListener('click', function(event) {
                    event.stopPropagation();
                    const questionId = this.dataset.questionId;
                    const extraTags = document.querySelectorAll(`.extra-tag-${questionId}`);
                    const isCurrentlyHidden = extraTags.length > 0 && extraTags[0].classList.contains(
                        'hidden');

                    extraTags.forEach(tag => {
                        tag.classList.toggle('hidden', !isCurrentlyHidden);
                    });

                    if (isCurrentlyHidden) {
                        this.textContent = 'show less';
                    } else {
                        this.textContent = this.dataset.initialText;
                    }
                });
                button.dataset.toggleInitialized = 'true';
            });
        }


        document.addEventListener('DOMContentLoaded', function() {
            initSaveButtons();
            updateSavedIcons();
            updateIconColors();
            initClickableQuestionCards();
            initTagToggles();
            initQuestionCardActions();

            if (typeof Trie === 'undefined') {
                console.error(
                    'FATAL ERROR: Trie class is not defined. Make sure utils.trie.blade.php is included correctly and defines the Trie class globally.'
                );
                const questionsListOutputContainer = document.getElementById(
                    'questionsListOutput');
                const mainQuestionContainer = document.getElementById('questions-list-ajax-container');
                if (mainQuestionContainer) {
                    mainQuestionContainer.innerHTML =
                        '<p style="color:red; text-align:center; padding:20px;">Search functionality is currently unavailable due to a configuration error. Please contact support.</p>';
                }
                return;
            }

            // updateIconColors(); // Already called above

            if (typeof window.pageThemeObserver === 'undefined') {
                window.pageThemeObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') {
                            updateIconColors();
                            updateSavedIcons
                                ();
                        }
                    });
                });
                window.pageThemeObserver.observe(document.documentElement, {
                    attributes: true
                });
            }


            const questionsListContainer = document.getElementById('questions-list-ajax-container');
            const paginationLinksContainer = questionsListContainer.querySelector('.pagination-container');
            const searchInput = document.getElementById('questionSearchInput');
            // const tagFilterSelect = document.getElementById('filter_tag_select');
            const sortByButtons = document.querySelectorAll('.tab-item[data-sortby]');

            const tagFilterContainer = document.getElementById('tag-filter-container');
            const tagFilterButton = document.getElementById('tag-filter-button');
            const tagFilterDropdown = document.getElementById('tag-filter-dropdown');
            const tagSearchInput = document.getElementById('tag-search-input');
            const tagList = document.getElementById('tag-list');
            const currentTagNameSpan = document.getElementById('current-tag-name');

            let currentPage = {{ $initialPage ?? 1 }};
            let currentSortBy = '{{ $initialSortBy ?? 'latest' }}';
            let currentFilterTag = '{{ $initialFilterTag ?? '' }}';
            let currentSearchTerm = '{{ $initialSearchTerm ?? '' }}';

            const ajaxUrl = '{{ route('home') }}';

            // event listener buat subject drop down + search
            if (tagFilterContainer) {
                tagFilterButton.addEventListener('click', (event) => {
                    event
                        .stopPropagation(); // Prevent the 'document' click listener from firing immediately
                    const isOpen = tagFilterDropdown.classList.toggle('open');
                    tagFilterButton.classList.toggle('active', isOpen);
                    if (isOpen) {
                        tagSearchInput.focus();
                    }
                });

                tagSearchInput.addEventListener('input', () => {
                    const searchTerm = tagSearchInput.value.toLowerCase();
                    tagList.querySelectorAll('li').forEach(li => {
                        const tagName = li.textContent.toLowerCase();
                        li.style.display = tagName.includes(searchTerm) ? 'block' : 'none';
                    });
                });

                // handle select subject
                tagList.addEventListener('click', (event) => {
                    const targetLink = event.target.closest('.tag-link-item');
                    if (targetLink) {
                        event.preventDefault();
                        const selectedTag = targetLink.dataset.tagName;

                        if (currentFilterTag !== selectedTag) {
                            currentFilterTag = selectedTag;
                            fetchQuestions(1); // Fetch new questions for page 1 with the new tag

                            // Update the button text and active state in the list
                            currentTagNameSpan.textContent = selectedTag || 'All';
                            tagList.querySelectorAll('.tag-link-item').forEach(link => link.classList
                                .remove('active'));
                            targetLink.classList.add('active');
                        }

                        tagFilterDropdown.classList.remove('open');
                        tagFilterButton.classList.remove('active');
                    }
                });
            }

            document.addEventListener('click', (event) => {
                if (tagFilterContainer && !tagFilterContainer.contains(event.target)) {
                    tagFilterDropdown.classList.remove('open');
                    tagFilterButton.classList.remove('active');
                }
            });

            function showLoadingSkeleton() {
                // ... (your existing skeleton logic - no change needed here)
                if (!questionsListContainer) return;
                const listContentArea = questionsListContainer.querySelector(
                    '#questionsListOutput'); // Assuming content goes here
                if (listContentArea) { // Clear only specific content area if it exists
                    listContentArea.innerHTML = ''; // Clear previous questions
                } else { // Fallback: clear most of container except pagination
                    while (questionsListContainer.firstChild && questionsListContainer.firstChild !==
                        paginationLinksContainer) {
                        questionsListContainer.removeChild(questionsListContainer.firstChild);
                    }
                }
                let skeletonHTML = '';
                const skeletonCount = 3;
                for (let i = 0; i < skeletonCount; i++) {
                    skeletonHTML += `
                    <div class="question-card popular-question-card rounded-lg mb-4 p-5 flex skeleton">
                        <div class="flex flex-col items-end justify-start mr-4 pt-1 space-y-3 px-3 border-r border-[var(--border-color)]">
                            <div class="w-6 h-4 rounded bg-gray-300 animate-pulse"></div> <div class="w-6 h-4 rounded bg-gray-300 animate-pulse"></div> <div class="w-6 h-4 rounded bg-gray-300 animate-pulse"></div>
                        </div>
                        <div class="flex-1 p-0 mr-4 z-10">
                            <div class="h-5 rounded w-3/4 mb-3 bg-gray-300 animate-pulse"></div> <div class="h-3 rounded w-full mb-2 bg-gray-300 animate-pulse"></div>
                            <div class="h-3 rounded w-5/6 mb-4 bg-gray-300 animate-pulse"></div>
                            <div class="flex flex-wrap gap-2 items-center"> <div class="h-4 w-16 rounded bg-gray-300 animate-pulse"></div> <div class="h-4 w-20 rounded bg-gray-300 animate-pulse"></div> </div>
                        </div>
                    </div>`;
                }
                // Insert skeleton before pagination or at the start of where content should be
                const targetInsertLocation = listContentArea || questionsListContainer;
                const insertBeforeElement = listContentArea ? null :
                    paginationLinksContainer; // If listContentArea, append. Else, insert before pagination.

                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = skeletonHTML;
                Array.from(tempDiv.children).forEach(skelNode => {
                    if (insertBeforeElement) {
                        targetInsertLocation.insertBefore(skelNode, insertBeforeElement);
                    } else {
                        targetInsertLocation.appendChild(skelNode);
                    }
                });
                if (paginationLinksContainer) paginationLinksContainer.innerHTML = '';
            }

            async function fetchQuestions(page = 1, updateUrlHistory = true) {
                showLoadingSkeleton();
                const params = new URLSearchParams({
                    page,
                    sort_by: currentSortBy
                });
                if (currentFilterTag) params.append('filter_tag', currentFilterTag);
                if (currentSearchTerm) params.append('search_term', currentSearchTerm);

                const displayParams = new URLSearchParams(params.toString());
                if (parseInt(page) === 1 && displayParams.has('page')) {
                    displayParams.delete('page');
                }

                const requestUrl = `${ajaxUrl}?${params.toString()}`;
                const historyUrl =
                    `${window.location.pathname}${displayParams.toString() ? '?' + displayParams.toString() : ''}`;

                try {
                    const response = await fetch(requestUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    const data = await response.json();

                    // Determine where to inject the new HTML content
                    const listContentArea = questionsListContainer.querySelector(
                        '#questionsListOutput'); // Ideal target
                    const targetContainer = listContentArea || questionsListContainer; // Fallback
                    const insertBeforeNode = listContentArea ? null :
                        paginationLinksContainer; // If using listContentArea, append to it. Otherwise, insert before pagination in main container.

                    // Clear previous content before injecting new HTML
                    if (listContentArea) {
                        listContentArea.innerHTML = data.html; // Replace content of specific area
                    } else {
                        // Clear old question cards if not using a dedicated output div
                        while (targetContainer.firstChild && targetContainer.firstChild !==
                            paginationLinksContainer) {
                            targetContainer.removeChild(targetContainer.firstChild);
                        }
                        const tempContentDiv = document.createElement('div');
                        tempContentDiv.innerHTML = data.html;
                        Array.from(tempContentDiv.children).forEach(contentNode => {
                            if (insertBeforeNode) {
                                targetContainer.insertBefore(contentNode, insertBeforeNode);
                            } else {
                                targetContainer.appendChild(contentNode);
                            }
                        });
                    }


                    if (paginationLinksContainer) {
                        paginationLinksContainer.innerHTML = data.pagination_html;
                        initializePaginationLinks();
                    }
                    currentPage = data.current_page || page;
                    if (updateUrlHistory) {
                        window.history.pushState({
                            page: currentPage,
                            sortBy: currentSortBy,
                            filterTag: currentFilterTag,
                            searchTerm: currentSearchTerm
                        }, '', historyUrl);
                    }

                      initializePaginationLinks();
                initTagToggles();
                updateIconColors();
                updateSavedIcons();

                } catch (error) {
                    console.error('Error fetching questions:', error);
                    const errorTarget = questionsListContainer.querySelector('#questionsListOutput') ||
                        questionsListContainer;
                    while (errorTarget.firstChild && errorTarget.firstChild !== paginationLinksContainer) {
                        errorTarget.removeChild(errorTarget.firstChild);
                    }
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'popular-question-card rounded-lg p-8 text-center text-red-500';
                    errorDiv.innerHTML = '<p>Sorry, something went wrong. Please try refreshing the page.</p>';

                    const insertBeforeErrorNode = questionsListContainer.querySelector('#questionsListOutput') ?
                        null : paginationLinksContainer;
                    if (insertBeforeErrorNode) {
                        errorTarget.insertBefore(errorDiv, insertBeforeErrorNode);
                    } else {
                        errorTarget.appendChild(errorDiv);
                    }

                    if (paginationLinksContainer) paginationLinksContainer.innerHTML = '';
                }
            }

            function initializePaginationLinks() {
                // ... (your existing pagination logic - no change needed here)
                if (!paginationLinksContainer) return;
                paginationLinksContainer.querySelectorAll('a[href]').forEach(link => {
                    if (link.getAttribute('aria-current') === 'page' || link.closest(
                            'span[aria-disabled="true"]')) return;
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page');
                        if (page) fetchQuestions(parseInt(page));
                    });
                });
            }
            initializePaginationLinks(); // Initial call for any pre-rendered pagination

            sortByButtons.forEach(button => {
                // ... (your existing sort button logic - no change needed here)
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const newSortBy = this.dataset.sortby;
                    if (newSortBy && newSortBy !== currentSortBy) {
                        currentSortBy = newSortBy;
                        currentPage = 1;
                        fetchQuestions(currentPage);
                        sortByButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });

            if (tagFilterSelect) {
                tagFilterSelect.addEventListener('change', function() {
                    currentFilterTag = this.value;
                    currentPage = 1;
                    fetchQuestions(currentPage);
                });
            }

            let searchDebounceTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchDebounceTimeout);
                    searchDebounceTimeout = setTimeout(() => {
                        currentSearchTerm = this.value.trim();
                        currentPage = 1;
                        fetchQuestions(currentPage);
                    }, 500);
                });
            }

            window.addEventListener('popstate', function(event) {
                const state = event.state || {};
                const paramsFromUrl = new URLSearchParams(window.location.search);

                currentPage = state.page || parseInt(paramsFromUrl.get('page')) || 1;
                currentSortBy = state.sortBy || paramsFromUrl.get('sort_by') || 'latest';
                currentFilterTag = state.filterTag || paramsFromUrl.get('filter_tag') || '';
                currentSearchTerm = state.searchTerm || paramsFromUrl.get('search_term') || '';

                if (searchInput) searchInput.value = currentSearchTerm;
                if (tagFilterSelect) tagFilterSelect.value = currentFilterTag;
                sortByButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.sortby ===
                    currentSortBy));
                fetchQuestions(currentPage, false); // `false` because URL is already updated by browser
            });

            questionsListContainer.addEventListener('click', function(event) {
                if (event.target.matches('a.filter-clear-link')) {
                    event.preventDefault();
                    currentFilterTag = '';
                    currentSearchTerm = '';
                    currentPage = 1;
                    // currentSortBy = 'latest'; 
                    if (searchInput) searchInput.value = '';
                    if (tagFilterSelect) tagFilterSelect.value = '';
                    sortByButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.sortby ===
                        currentSortBy));
                    fetchQuestions(currentPage);
                }
            });

            const communityCards = document.querySelectorAll('.grid > div');
            if (communityCards) {
                communityCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-5px)';
                        this.style.boxShadow = '0 10px 25px rgba(245, 158, 11, 0.1)';
                    });

                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = 'none';
                    });
                });
            }
        });

        function initQuestionCardActions() {
            const reportButtons = document.querySelectorAll('.open-report-modal-btn');
            reportButtons.forEach(button => {
                // Mencegah listener ganda
                if (button.dataset.reportInitialized === 'true') return;
                button.dataset.reportInitialized = 'true';

                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation(); // Penting agar tidak trigger klik kartu

                    const questionId = button.closest('.question-card').dataset.url.split('/').pop();

                    if (window.openReportModal) {
                        window.openReportModal(questionId);
                    }
                });
            });
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.options-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
        const reportModal = document.getElementById('reportModal');
        
        if (reportModal) {
            const reportModalContent = document.getElementById('reportModalContent');
            const closeReportModalBtn = document.getElementById('closeReportModalBtn');
            const cancelReportBtn = document.getElementById('cancelReportBtn');
            const submitReportBtn = document.getElementById('submitReportBtn');
            const reportForm = document.getElementById('reportForm');
            const reportableIdInput = document.getElementById('reportable_id');
            const additionalNotesContainer = document.getElementById('additionalNotesContainer');
            const additionalNotesTextarea = document.getElementById('additional_notes');
            const reportReasonsContainer = document.getElementById('reportReasonsContainer');

            // Jadikan fungsi ini global agar bisa diakses dari initQuestionCardActions
            window.openReportModal = function(questionId) {
                if (!reportModal) return;
                reportForm.reset();
                additionalNotesContainer.classList.add('hidden');
                reportableIdInput.value = questionId;

                reportModal.classList.remove('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    reportModalContent.classList.remove('scale-95', 'opacity-0');
                }, 10);
            }

            function closeReportModal() {
                if (!reportModal) return;
                reportModalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    reportModal.classList.add('opacity-0', 'pointer-events-none');
                }, 300);
            }

            closeReportModalBtn.addEventListener('click', closeReportModal);
            cancelReportBtn.addEventListener('click', closeReportModal);
            reportModal.addEventListener('click', (event) => {
                if (event.target === reportModal) closeReportModal();
            });

            reportReasonsContainer.addEventListener('change', (event) => {
                if (event.target.type === 'radio') {
                    const selectedReasonText = event.target.dataset.reasonText || '';
                    if (selectedReasonText.toLowerCase() === 'others') {
                        additionalNotesContainer.classList.remove('hidden');
                        setTimeout(() => additionalNotesTextarea.focus(), 100);
                    } else {
                        additionalNotesContainer.classList.add('hidden');
                        additionalNotesTextarea.value = '';
                    }
                }
            });

            // --- AJAX Submit Report ---
            if (submitReportBtn) {
                submitReportBtn.addEventListener('click', () => {
                    const reportableId = reportableIdInput.value;
                    const selectedReason = reportForm.querySelector(
                        'input[name="report_reason_id"]:checked');

                    if (!selectedReason) {
                        Toastify({
                            text: 'Please select a reason for the report.',
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#f39c12"
                            }
                        }).showToast();
                        return;
                    }

                    const formData = new FormData(reportForm);
                    formData.set('reportable_id', reportableId);

                    // Only append additional notes if the container is visible
                    if (!additionalNotesContainer.classList.contains('hidden')) {
                        formData.append('additional_notes', additionalNotesTextarea.value);
                    }

                    // UI loading state
                    submitReportBtn.disabled = true;
                    submitReportBtn.querySelector('.btn-text').textContent = 'Submitting...';
                    submitReportBtn.querySelector('i').classList.remove('hidden');


                    fetch(`{{ route('submitReport') }}`, {
                            method: 'POST',
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                'Accept': 'application/json',
                            },
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toastify({
                                    text: data.message || 'Report submitted successfully!',
                                    duration: 3000,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                                    }
                                }).showToast();
                                closeReportModal();
                            } else {
                                Toastify({
                                    text: data.message || 'An error occurred.',
                                    duration: 4000, // Longer duration for errors
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#e74c3c"
                                    }
                                }).showToast();
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting report:', error);
                            Toastify({
                                text: 'A network error occurred. Please try again.',
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#e74c3c"
                                }
                            }).showToast();
                        })
                        .finally(() => {
                            submitReportBtn.disabled = false;
                            submitReportBtn.querySelector('.btn-text').textContent = 'Submit Report';
                            submitReportBtn.querySelector('i').classList.add('hidden');
                        });
                });
            }
        }

        function initSaveButtons() {
            const saveButtons = document.querySelectorAll('.save-question-btn');
            saveButtons.forEach(button => {
                if (button.dataset.saveBtnInitialized === 'true') return;

                const newButton = button.cloneNode(true);
                newButton.removeAttribute('onclick');
                button.parentNode.replaceChild(newButton, button);

                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Crucial to prevent card click

                    const icon = this.querySelector('i');
                    if (icon && icon.classList.contains('fa-solid') && icon.classList.contains(
                            'fa-bookmark')) {
                        unsaveQuestion(this);
                    } else {
                        saveQuestion(this);
                    }
                });
                newButton.dataset.saveBtnInitialized = 'true'; // Mark as initialized
            });
        }

        function updateIconColors() { // This function is defined in your original script
            const statsItems = document.querySelectorAll('.stats-item');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            if (statsItems) {
                statsItems.forEach((item, index) => {
                    const icon = item.querySelector('i');
                    if (!icon) return;
                    if (index % 3 === 0) icon.style.color = isLightMode ? '#10b981' : '#23BF7F';
                    else if (index % 3 === 1) icon.style.color = isLightMode ? '#f59e0b' : '#ffd249';
                    else icon.style.color = isLightMode ? '#4DB2BF' : '#3DAAA3';
                });
            }
        }


        function updateSavedIcons() {
            // ... (your existing updateSavedIcons logic - no change needed here)
            const savedIcons = document.querySelectorAll('.save-question-btn i.fa-solid.fa-bookmark');
            const isLightMode = document.documentElement.classList.contains('light-mode');
            savedIcons.forEach(icon => {
                icon.style.color =
                    'var(--accent-secondary)'; // Simplified as it seems to be the same for both modes
            });
        }

        function unsaveQuestion(btn) {
            // ... (your existing unsaveQuestion logic - no change needed here)
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Unsaving...",
                duration: -1,
                /*...*/
                style: {
                    background: "#444"
                }
            });
            loadingToast.showToast();

            fetch("{{ route('unsaveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(response => response.json()).then(res => {
                loadingToast.hideToast();
                if (res.success) {
                    Toastify({
                        text: res.message,
                        duration: 3000,
                        /*...*/
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    btn.innerHTML =
                        `<i class="fa-regular fa-bookmark text-[var(--text-muted)] hover:text-[var(--accent-secondary)]"></i>`;
                    btn.setAttribute("title", "Save Question");
                } else {
                    Toastify({
                        text: res.message || "Failed to unsave.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                }
            }).catch(err => {
                /* Error Toast */
            });
        }

        function saveQuestion(btn) {
            // ... (your existing saveQuestion logic - no change needed here)
            const id = btn.getAttribute('data-question-id');
            let formData = new FormData();
            formData.append("question_id", id);

            let loadingToast = Toastify({
                text: "Saving...",
                duration: -1,
                /*...*/
                style: {
                    background: "#444"
                }
            });
            loadingToast.showToast();

            fetch("{{ route('saveQuestion') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            }).then(response => response.json()).then(res => {
                loadingToast.hideToast();
                if (res.success) {
                    Toastify({
                        text: res.message,
                        duration: 3000,
                        /*...*/
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                    btn.innerHTML = `<i class="fa-solid fa-bookmark text-[var(--accent-secondary)]"></i>`;
                    btn.setAttribute("title", "Unsave Question");
                    updateSavedIcons(); // Call to ensure new saved icon gets correct styling
                    btn.classList.add('saved-animation');
                    setTimeout(() => btn.classList.remove('saved-animation'), 300);
                } else {
                    Toastify({
                        text: res.message || "Failed to save.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#e74c3c"
                        }
                    }).showToast();
                }
            }).catch(err => {
                /* Error Toast */
            });
        }
    </script>
@endsection
