@section('script')
    @include('utils.trie')
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // =========================================================================
        // BAGIAN 1: VARIABEL & ELEMEN UTAMA
        // =========================================================================
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';
        const questionsListContainer = document.getElementById('questions-list-ajax-container');
        const reportModal = document.getElementById('reportModal');
        
        let currentPage = {{ $initialPage ?? 1 }};
        let currentSortBy = '{{ $initialSortBy ?? 'latest' }}';
        let currentFilterTag = '{{ $initialFilterTag ?? '' }}';
        let currentSearchTerm = '{{ $initialSearchTerm ?? '' }}';


        // =========================================================================
        // BAGIAN 2: LOGIKA & FUNGSI MODAL REPORT
        // =========================================================================
        if (reportModal) {
            const reportForm = document.getElementById('reportForm');
            const reportableIdInput = document.getElementById('reportable_id');
            const additionalNotesContainer = document.getElementById('additionalNotesContainer');
            const additionalNotesTextarea = document.getElementById('additional_notes');

            window.openReportModal = (questionId) => {
                reportForm.reset();
                additionalNotesContainer.classList.add('hidden');
                reportableIdInput.value = questionId;
                reportModal.classList.remove('opacity-0', 'pointer-events-none');
            };

            window.closeReportModal = () => {
                reportModal.classList.add('opacity-0', 'pointer-events-none');
            };
            
            reportReasonsContainer.addEventListener('change', (e) => {
                const isOthers = e.target.type === 'radio' && e.target.dataset.reasonText.toLowerCase() === 'others';
                additionalNotesContainer.classList.toggle('hidden', !isOthers);
                if (isOthers) setTimeout(() => additionalNotesTextarea.focus(), 100);
            });
        }


        // =========================================================================
        // BAGIAN 3: LISTENER UTAMA (EVENT DELEGATION)
        // =========================================================================
        document.body.addEventListener('click', function(event) {
            const target = event.target;

            // --- Aksi: Klik tombol REPORT ---
            const reportButton = target.closest('.open-report-modal-btn');
            if (reportButton) {
                event.preventDefault();
                event.stopPropagation();
                if (window.openReportModal) {
                    const questionId = reportButton.closest('.question-card').dataset.url.split('/').pop();
                    window.openReportModal(questionId);
                }
                return;
            }
            
            // --- Aksi: Klik tombol SUBMIT REPORT ---
            const submitReportButton = target.closest('#submitReportBtn');
            if (submitReportButton) {
                const reportForm = document.getElementById('reportForm');
                if (!reportForm.querySelector('input[name="report_reason_id"]:checked')) {
                    Toastify({ text: 'Please select a reason.', style: { background: "#f39c12" } }).showToast();
                    return;
                }

                const formData = new FormData(reportForm);
                const reportableId = formData.get('reportable_id');

                submitReportButton.disabled = true;
                submitReportButton.querySelector('.btn-text').textContent = 'Submitting...';
                submitReportButton.querySelector('i').classList.remove('hidden');

                fetch(`{{ route('submitReport') }}`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Toastify({ text: data.message || 'Report submitted!', style: { background: "linear-gradient(to right, #00b09b, #96c93d)" } }).showToast();
                        if(window.closeReportModal) window.closeReportModal();
                        
                        const reportedButton = document.querySelector(`.question-card[data-url*='${reportableId}'] .open-report-modal-btn`);
                        if(reportedButton) {
                            reportedButton.style.transition = 'opacity 0.3s, transform 0.3s';
                            reportedButton.style.opacity = '0';
                            reportedButton.style.transform = 'scale(0.5)';
                            setTimeout(() => reportedButton.remove(), 300);
                        }
                    } else {
                         Toastify({ text: data.message || 'An error occurred.', style: { background: "#e74c3c" } }).showToast();
                    }
                })
                .catch(error => console.error('Error:', error))
                .finally(() => {
                    submitReportButton.disabled = false;
                    submitReportButton.querySelector('.btn-text').textContent = 'Submit Report';
                    submitReportButton.querySelector('i').classList.add('hidden');
                });
                return;
            }

            // --- Aksi: Klik tombol CLOSE atau CANCEL di modal ---
            if (target.closest('#closeReportModalBtn') || target.closest('#cancelReportBtn')) {
                if(window.closeReportModal) window.closeReportModal();
                return;
            }

            // --- Aksi: Klik tombol SAVE ---
            const saveButton = target.closest('.save-question-btn');
            if (saveButton) {
                event.preventDefault();
                event.stopPropagation();
                const icon = saveButton.querySelector('i');
                if (icon && icon.classList.contains('fa-solid')) {
                    unsaveQuestion(saveButton);
                } else {
                    saveQuestion(saveButton);
                }
                return;
            }

            // --- Aksi: Klik KARTU PERTANYAAN ---
            const questionCard = target.closest('.question-card');
            if (questionCard && !target.closest('.question-tag-link, .more-tags-button, .save-question-btn, .open-report-modal-btn')) {
                window.location.href = questionCard.dataset.url;
            }
        });


        // =========================================================================
        // BAGIAN 4: FUNGSI HELPER & LOGIKA SPESIFIK LAINNYA
        // =========================================================================
        function saveQuestion(btn) { /* ... kode asli fungsi saveQuestion Anda ... */ }
        function unsaveQuestion(btn) { /* ... kode asli fungsi unsaveQuestion Anda ... */ }
        function updateIconColors() { /* ... kode asli fungsi updateIconColors Anda ... */ }
        function updateSavedIcons() { /* ... kode asli fungsi updateSavedIcons Anda ... */ }
        function initTagToggles() { /* ... kode asli fungsi initTagToggles Anda ... */ }
        function initializePaginationLinks() { /* ... kode asli fungsi initializePaginationLinks Anda ... */ }
        function showLoadingSkeleton() { /* ... kode asli fungsi showLoadingSkeleton Anda ... */ }

        // --- Logika Fetch AJAX ---
        async function fetchQuestions(page = 1, updateUrlHistory = true) {
            showLoadingSkeleton();
            // ... (kode lengkap fetchQuestions Anda)
            try {
                // ... (setelah fetch berhasil dan inject HTML)

                // PENTING: Inisialisasi HANYA untuk fungsi yang tidak di-handle oleh delegasi
                initializePaginationLinks();
                initTagToggles();
                updateIconColors();
                updateSavedIcons();

            } catch (error) {
                // ...
            }
        }

        // --- Inisialisasi Awal & Listener Elemen Statis ---
        updateIconColors();
        updateSavedIcons();
        initTagToggles();
        initializePaginationLinks();

        sortByButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // ... (logika sort Anda)
            });
        });

        // ... sisa listener untuk filter tag, search, dll ...
    });
    </script>
@endsection