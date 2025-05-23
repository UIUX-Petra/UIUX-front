@extends('admin.layouts.admin-layout')

@section('title', 'Content Moderation Reports')

@section('content')
{{-- x-data utama untuk tab. Modal testing akan punya x-data sendiri yang terpisah & sederhana --}}
<div x-data="{ activeTab: 'question_reports' }">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Review Content Reports</h1>

    <div class="mb-6 border-b border-gray-200">
        <nav class="flex flex-wrap -mb-px sm:space-x-4" aria-label="Tabs">
            <button @click="activeTab = 'question_reports'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'question_reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'question_reports' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                Question Reports
                @php
                    $reportedQuestions = $reportedQuestions ?? [
                        (object)['id' => 101, 'question_title' => 'How to install dual boot Windows and Linux?', 'question_preview' => 'I am trying to set up a dual boot system with Windows 11 and Ubuntu 24.04...', 'reporter' => 'UserAlpha', 'reporter_id' => 201, 'reason' => 'Duplicate Question (already asked by #98)', 'reported_at' => '2025-05-22 10:00:00', 'status' => 'Pending'],
                        (object)['id' => 105, 'question_title' => 'Best way to learn Python for Data Science in 2025?', 'question_preview' => 'What is the most effective and up-to-date pathway to learn Python specifically for data science applications?...', 'reporter' => 'UserBeta', 'reporter_id' => 202, 'reason' => 'Off-topic for "Web Development" subject.', 'reported_at' => '2025-05-21 11:30:00', 'status' => 'Pending'],
                        (object)['id' => 112, 'question_title' => 'This user is an idiot!!!', 'question_preview' => 'The answer provided by @SomeUser is completely wrong and they are clearly an idiot...', 'reporter' => 'UserGamma', 'reporter_id' => 203, 'reason' => 'Inappropriate Language / Personal Attack', 'reported_at' => '2025-05-23 14:15:00', 'status' => 'Pending'],
                        (object)['id' => 115, 'question_title' => 'BUY CHEAP SOFTWARE KEYS HERE!!!', 'question_preview' => 'Spam! Buy my new amazing product now! Limited time offer! Visit mywebsite.com/spam...', 'reporter' => 'ModeratorBot', 'reporter_id' => 1, 'reason' => 'Spam / Unsolicited Promotion', 'reported_at' => '2025-05-20 09:00:00', 'status' => 'Pending'],
                    ];
                    $reportedAnswers = $reportedAnswers ?? [
                        (object)['id' => 201, 'answer_preview' => 'You should definitely use framework X because it is much faster than framework Y. Anyone using Y is a newbie...', 'question_id' => 101, 'question_title' => 'How to install dual boot Windows and Linux?', 'reporter' => 'UserCharlie', 'reporter_id' => 205, 'reason' => 'Rude and unhelpful tone. Contains personal attacks.', 'reported_at' => '2025-05-23 16:00:00', 'status' => 'Pending'],
                        (object)['id' => 202, 'answer_preview' => 'Just google it. This question is too basic. There are thousands of tutorials online for this. Stop being lazy...', 'question_id' => 105, 'question_title' => 'Best way to learn Python for Data Science in 2025?', 'reporter' => 'UserAlpha', 'reporter_id' => 201, 'reason' => 'Unconstructive and dismissive answer.', 'reported_at' => '2025-05-22 18:20:00', 'status' => 'Pending'],
                        (object)['id' => 203, 'answer_preview' => 'Check out my amazing course on how to become a millionaire programmer in 30 days! Visit my-scam-site.com...', 'question_id' => 112, 'question_title' => 'This user is an idiot!!!', 'reporter' => 'UserOmega', 'reporter_id' => 206, 'reason' => 'Spam / Unsolicited Advertisement.', 'reported_at' => '2025-05-24 09:00:00', 'status' => 'Pending'],
                        (object)['id' => 204, 'answer_preview' => 'The solution is simply `import antigravity`. This will solve all your problems and make you fly. Trust me, I am an engineer.', 'question_id' => 120, 'question_title' => 'Help with my C++ homework on pointers', 'reporter' => 'UserEpsilon', 'reporter_id' => 207, 'reason' => 'Joke answer / Not a serious attempt to help.', 'reported_at' => '2025-05-25 10:10:00', 'status' => 'Pending'],
                    ];
                    $reportedComments = $reportedComments ?? [
                        (object)['id' => 301, 'comment_preview' => 'This is totally wrong, you should use a different approach. The author of this answer clearly doesn\'t know what they are talking about...', 'reported_on_type' => 'Answer', 'reported_on_id' => 201, 'reported_on_content_preview' => 'You should definitely use framework X...', 'reporter' => 'UserZeta', 'reporter_id' => 208, 'reason' => 'Disrespectful and aggressive tone.', 'reported_at' => '2025-05-24 11:00:00', 'status' => 'Pending'],
                        (object)['id' => 302, 'comment_preview' => 'Hey, check out my website for more great tutorials: myawesomesite.com', 'reported_on_type' => 'Question', 'reported_on_id' => 105, 'reported_on_content_preview' => 'Best way to learn Python for Data Science in 2025?', 'reporter' => 'UserEta', 'reporter_id' => 209, 'reason' => 'Spam / Self-promotion.', 'reported_at' => '2025-05-23 12:30:00', 'status' => 'Pending'],
                        (object)['id' => 303, 'comment_preview' => 'LOL what a noob question. RTFM.', 'reported_on_type' => 'Question', 'reported_on_id' => 120, 'reported_on_content_preview' => 'Help with my C++ homework on pointers', 'reporter' => 'UserTheta', 'reporter_id' => 210, 'reason' => 'Unhelpful and demeaning.', 'reported_at' => '2025-05-25 13:00:00', 'status' => 'Pending'],
                        (object)['id' => 304, 'comment_preview' => 'This is actually a great point, I never thought of it that way. Thanks for sharing this perspective!', 'reported_on_type' => 'Answer', 'reported_on_id' => 202, 'reported_on_content_preview' => 'A good answer about Python data structures.', 'reporter' => 'UserKappa', 'reporter_id' => 211, 'reason' => 'Reported by mistake. Meant to upvote.', 'reported_at' => '2025-05-26 14:00:00', 'status' => 'Pending'],
                    ];
                @endphp
                <span class="ml-1 px-2 py-0.5 text-xs font-semibold text-orange-600 bg-orange-100 rounded-full">{{ count($reportedQuestions) }}</span>
            </button>
            <button @click="activeTab = 'answer_reports'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'answer_reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'answer_reports' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                Answer Reports
                <span class="ml-1 px-2 py-0.5 text-xs font-semibold text-purple-600 bg-purple-100 rounded-full">{{ count($reportedAnswers) }}</span>
            </button>
            <button @click="activeTab = 'comment_reports'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'comment_reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'comment_reports' }"
                    class="whitespace-nowrap py-3 px-2 sm:px-4 border-b-2 font-medium text-sm focus:outline-none">
                Comment Reports
                <span class="ml-1 px-2 py-0.5 text-xs font-semibold text-teal-600 bg-teal-100 rounded-full">{{ count($reportedComments) }}</span>
            </button>
        </nav>
    </div>

    <div x-show="activeTab === 'question_reports'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto" x-cloak>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Question Reports List</h2>
        @include('admin.moderation.partials._report_table', ['reports' => $reportedQuestions, 'reportItemType' => 'question'])
    </div>

    <div x-show="activeTab === 'answer_reports'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto" x-cloak>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Answer Reports List</h2>
        @include('admin.moderation.partials._report_table', ['reports' => $reportedAnswers, 'reportItemType' => 'answer'])
    </div>

    <div x-show="activeTab === 'comment_reports'" class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto" x-cloak>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Comment Reports List</h2>
         @include('admin.moderation.partials._report_table', ['reports' => $reportedComments, 'reportItemType' => 'comment'])
    </div>

</div> {{-- Penutup dari div x-data="{ activeTab: ... }" --}}



@push('scripts')
<script>
    // Fungsi JavaScript global Anda
    function viewReportDetail(type, id) {
        console.log(`Dispatching view-report-detail for main modal: type=${type}, id=${id}`);
        window.dispatchEvent(new CustomEvent('view-report-detail', { detail: { type: type, id: id }}));
    }

    function processReport(action, reportType, reportId) {
        console.log(`Dispatching process-report-confirm for main modal: action=${action}, type=${reportType}, id=${id}`);
        window.dispatchEvent(new CustomEvent('process-report-confirm', { detail: { action: action, reportType: reportType, reportId: reportId }}));
    }

    function performConfirmedAction() {
        // Target modal utama yang mungkin dikomentari
        const alpineComponent = document.querySelector('[x-data*="originalQuestionTitle"]'); // Atau selector lain yang spesifik untuk modal utama Anda
        if (!alpineComponent || !alpineComponent.__x) {
            console.warn('Main Alpine modal component not found for performConfirmedAction. This is expected if it is commented out.');
            // Jika Anda ingin fungsi ini tetap bekerja dengan modal test, Anda perlu logika tambahan
            // atau buat fungsi terpisah untuk modal test. Untuk saat ini, kita fokus pada diagnosa.
            return;
        }
        const alpineData = alpineComponent.__x.$data;

        const reportType = alpineData.reportType;
        const reportId = alpineData.reportId;
        const action = alpineData.actionForConfirmation;

        if (!action || !reportType || !reportId) {
            // alert('Error: Could not determine action details for confirmation from main modal data.');
            if(alpineData) alpineData.openModal = false;
            return;
        }
        alert(`Main modal: Action confirmed for ${reportType} #${reportId}. Action: ${action}. Implement AJAX logic here.`);
        alpineData.openModal = false;
        alpineData.actionForConfirmation = ''; 
    }
</script>
@endpush
@endsection