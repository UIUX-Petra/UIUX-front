{{-- resources/views/admin/moderation/partials/_report_table.blade.php --}}
<div class="mb-4 flex justify-between items-center">
    <div class="relative w-full max-w-xs">
        <input type="text" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search reports in this tab...">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i class="ri-search-line text-gray-400"></i>
        </span>
    </div>
    {{-- You can add specific filters for each report type here if needed --}}
</div>
<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">ID</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[250px] max-w-sm">Preview</th>
            @if($reportItemType === 'answer' || $reportItemType === 'comment')
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Related To</th>
            @endif
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[150px]">Reason</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Reported</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($reports as $report)
        <tr class="align-top">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                <a href="#" class="text-blue-600 hover:underline">#{{ $report->id }}</a>
            </td>
            <td class="px-6 py-4 text-sm text-gray-700 min-w-[250px] max-w-sm">
                @if($reportItemType === 'question' && isset($report->question_title))
                    <span class="font-semibold text-gray-800 block">{{ Str::limit($report->question_title, 60) }}</span>
                    <p class="text-xs text-gray-500 break-words">{{ Str::limit($report->question_preview, 100) }}</p>
                @elseif($reportItemType === 'answer' && isset($report->answer_preview))
                    <p class="break-words">{{ Str::limit($report->answer_preview, 150) }}</p>
                @elseif($reportItemType === 'comment' && isset($report->comment_preview))
                    <p class="break-words">{{ Str::limit($report->comment_preview, 150) }}</p>
                @else
                    <p class="break-words">{{ Str::limit($report->question_preview ?? $report->answer_preview ?? $report->comment_preview ?? '', 150) }}</p>
                @endif
            </td>
            @if($reportItemType === 'answer')
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <a href="#" class="text-blue-600 hover:underline">Question #{{ $report->question_id }}</a>
                @if(isset($report->question_title))
                <p class="text-xs text-gray-400 truncate">{{ Str::limit($report->question_title, 40) }}</p>
                @endif
            </td>
            @elseif($reportItemType === 'comment')
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <a href="#" class="text-blue-600 hover:underline">{{ $report->reported_on_type }} #{{ $report->reported_on_id }}</a>
                 @if(isset($report->reported_on_content_preview))
                <p class="text-xs text-gray-400 truncate">{{ Str::limit($report->reported_on_content_preview, 40) }}</p>
                @endif
            </td>
            @endif
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                <a href="#" class="text-blue-600 hover:underline">{{ $report->reporter }}</a>
                 <span class="text-xs text-gray-500 block">(ID: {{ $report->reporter_id }})</span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500 min-w-[150px]">
                <p class="break-words">{{ $report->reason }}</p>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" title="{{ \Carbon\Carbon::parse($report->reported_at)->format('Y-m-d H:i:s') }}">
                {{ \Carbon\Carbon::parse($report->reported_at)->diffForHumans() }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1">
                <button class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-100" title="View Report Details" onclick="viewReportDetail('{{ $reportItemType }}', {{ $report->id }})">
                    <i class="ri-information-line text-lg"></i>
                </button>
                <button class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-100" title="Approve Report" onclick="processReport('approve', '{{ $reportItemType }}', {{ $report->id }})">
                    <i class="ri-check-line text-lg"></i>
                </button>
                <button class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-100" title="Reject Report" onclick="processReport('reject', '{{ $reportItemType }}', {{ $report->id }})">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ ($reportItemType === 'answer' || $reportItemType === 'comment') ? 7 : 6 }}" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                No new {{ $reportItemType }} reports matching your criteria.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
<div class="mt-6">
    {{-- Basic Pagination Placeholder --}}
    <nav class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6" aria-label="Pagination">
        <div class="hidden sm:block">
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">1</span> to <span class="font-medium">{{ count($reports) }}</span> of <span class="font-medium">{{ count($reports) }}</span> results
          </p>
        </div>
        <div class="flex-1 flex justify-between sm:justify-end">
          <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
          <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
        </div>
    </nav>
</div>