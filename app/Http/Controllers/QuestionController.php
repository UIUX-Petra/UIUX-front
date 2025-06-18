<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    // public function getAllQuestions(Request $request)
    // {
    //     $api_url = env('API_URL') . '/questions';
    //     $response = Http::withToken(session('token'))->get($api_url);
    //     $response = json_decode($response, true);

    //     // Get the data (questions)
    //     $data = $response['data'];

    //     // Loop through each question and count comments
    //     foreach ($data as &$question) {
    //         $question['comments_count'] = (is_array($question['comment']) && $question['comment'] !== null)
    //             ? count($question['comment'])
    //             : 0;
    //     }

    //     $page = $request->input('page', 1);
    //     $per_page = 10;
    //     $offset = ($page - 1) * $per_page;
    //     $paginated_data = array_slice($data, $offset, $per_page);
    //     $paginator = new LengthAwarePaginator(
    //         $paginated_data,
    //         count($data),
    //         $per_page,
    //         $page,
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );

    //     // dd($data);
    //     // Return the updated data
    //     return $paginator;
    // }

    public function getAllQuestions(Request $request)
    {
        $api_base_url = env('API_URL');
        $api_url = $api_base_url . '/questions-paginated-home';
        $page = $request->input('page', 1);
        $per_page_from_request = $request->input('per_page', 10);

        $response = Http::withToken(session('token'))->get($api_url, [
            'page' => $page,
            'per_page' => $per_page_from_request,
            'email' => session('email'),
        ]);

        if ($response->failed()) {
            Log::error("API request to /questions-paginated-home failed: " . $response->body());

            return new LengthAwarePaginator([], 0, $per_page_from_request, $page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        $apiResponseData = $response->json();

        if (!isset($apiResponseData['success']) || $apiResponseData['success'] !== true || !isset($apiResponseData['data'])) {
            Log::error("API request to /questions-paginated did not return a successful structure: " . $response->body());
            return new LengthAwarePaginator([], 0, $per_page_from_request, $page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        $paginatedApiResponse = $apiResponseData['data'];

        $items = $paginatedApiResponse['data'];
        $total = $paginatedApiResponse['total'];
        $perPage = $paginatedApiResponse['per_page'];
        $currentPage = $paginatedApiResponse['current_page'];
        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return $paginator;
    }

    public function getAllQuestionsByPopularity(Request $request)
    {
        $api_base_url = env('API_URL');
        $api_url = $api_base_url . '/questions-paginated';

        $page = $request->input('page', 1);
        $per_page_from_request = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'latest');
        $filterTag = $request->input('filter_tag', null);
        $searchTerm = $request->input('search_term', null);

        $queryParams = [
            'page' => $page,
            'per_page' => $per_page_from_request,
            'email' => session('email'),
            'sort_by' => $sortBy,
        ];

        if (!empty($filterTag)) {
            $queryParams['filter_tag'] = $filterTag;
        }
        if (!empty($searchTerm)) {
            $queryParams['search_term'] = $searchTerm;
        }

        $response = Http::withToken(session('token'))->get($api_url, $queryParams); // Pastikan session 'token' ada

        if ($response->failed()) {
            Log::error("QuestionController: API request failed: {$response->status()} - {$response->body()}", $queryParams);
            return $this->emptyPaginator($request, $per_page_from_request, $page, $queryParams);
        }

        $apiResponseData = $response->json();

        if (
            !isset($apiResponseData['success']) || $apiResponseData['success'] !== true ||
            !isset($apiResponseData['data']) || !is_array($apiResponseData['data']) ||
            !isset($apiResponseData['data']['data']) || !is_array($apiResponseData['data']['data']) ||
            !isset($apiResponseData['data']['total']) ||
            !isset($apiResponseData['data']['per_page']) ||
            !isset($apiResponseData['data']['current_page'])
        ) {
            Log::error("QuestionController: Invalid API response structure: " . $response->body(), $queryParams);
            return $this->emptyPaginator($request, $per_page_from_request, $page, $queryParams);
        }

        $paginatedData = $apiResponseData['data'];
        $items = $paginatedData['data'];
        $total = (int) $paginatedData['total'];
        $perPage = (int) $paginatedData['per_page'];
        $currentPage = (int) $paginatedData['current_page'];

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    private function emptyPaginator(Request $request, int $perPage, int $currentPage, array $query = [])
    {
        return new LengthAwarePaginator([], 0, $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $query,
        ]);
    }


    public function getQuestionDetails($id)
    {
        $data['email'] = session('email');
        $api_url = env('API_URL') . '/questions/' . $id . '/view';
        $response = Http::post($api_url, $data);
        $response = json_decode($response, true);
        $questionData = $response['data'];

        $comments = collect($questionData['comment']);
        $countcomments = count($comments);
        $questionData['comment_count'] = $countcomments;
        // dd($questionData);
        return $questionData;
    }

public function addQuestion(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'selected_tags' => 'required|array|min:1',
            'selected_tags.*' => 'string|uuid',
            'recommended_tags' => 'sometimes|array',
            'recommended_tags.*' => 'string|uuid',
        ]);

        $apiUrl = env('API_URL') . '/questions';
        $apiRequest = Http::withToken(session('token'))->asMultipart();

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $apiRequest->attach(
                'image',
                file_get_contents($imageFile->getRealPath()),
                $imageFile->getClientOriginalName()
            );
        }

        $payload = [
            [
                'name' => 'title',
                'contents' => $validated['title'],
            ],
            [
                'name' => 'question',
                'contents' => $validated['question'],
            ],
            // Tambahkan email dari session sebagai payload
            [
                'name' => 'email', // Nama field di API yang menerima email
                'contents' => session('email'),
            ],
        ];

        foreach ($validated['selected_tags'] as $tag) {
            $payload[] = [
                'name' => 'selected_tags[]',
                'contents' => $tag,
            ];
        }

        foreach ($request->input('recommended_tags', []) as $tag) {
            $payload[] = [
                'name' => 'recommended_tags[]',
                'contents' => $tag,
            ];
        }

        try {
            $response = $apiRequest->post($apiUrl, $payload);
            return $response->json();
        } catch (\Exception $e) {
            Log::error("Web Controller API Call Failed: " . $e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine());
            return response()->json(['success' => false, 'message' => 'Internal error connecting to the API service.'], 500);
        }
    }
    public function saveEditedQuestion(Request $request, $questionId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'subject_id' => 'sometimes|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5042',
            'remove_existing_image' => 'nullable|in:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid form data.', 'errors' => $validator->errors()], 422);
        }

        $apiPayload = [
            'title' => $request->input('title'),
            'question' => $request->input('question'),
        ];

        if ($request->has('subject_id')) {
            $apiPayload['subject_id'] = $request->input('subject_id');
        }

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $timestamp = date('Y-m-d_H-i-s');
            $extension = $imageFile->getClientOriginalExtension();
            $userIdentifier = str_replace(['@', '.'], ['_', '_'], session('email'));
            $customFileName = "q_" . $userIdentifier . "_" . $timestamp . "." . $extension;

            $path = $imageFile->storeAs("uploads/questions", $customFileName, 'public');
            $apiPayload['image'] = $path;
        }
        if ($request->has('remove_existing_image') && $request->input('remove_existing_image') == '1') {
            $apiPayload['remove_existing_image_flag'] = true;
        }

        $apiUrlForUpdate = env('API_URL') . "/questions/{$questionId}/updatePartial";

        try {
            $response = Http::withToken(session('token'))
                ->post($apiUrlForUpdate, $apiPayload);

            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json([
                    'success' => true,
                    'message' => 'Question update request processed!',
                    'data' => $responseData['data'] ?? null
                ]);
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to process question update via API.';
                return response()->json(['success' => false, 'message' => $errorMessage, 'api_errors' => $response->json()['errors'] ?? null], $response->status());
            }
        } catch (\Exception $e) {
            Log::error("WEB saveEditedQuestion: Error calling update API: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error during API request from web handler.'], 500);
        }
    }

    public function vote(Request $request)
    {
        $data['email'] = session('email');
        $vote = filter_var($request->vote, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($vote === true) {
            $api_url = env('API_URL') . '/questions/' . $request->question_id . '/upvote';
        } else {
            $api_url = env('API_URL') . '/questions/' . $request->question_id . '/downvote';
        }
        $response = Http::withToken(session('token'))->post($api_url, $data);
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Your Vote has been recorded',
                'voteUpdated' => $response->json()['data']
            ]);
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to comment.';
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }

    public function saveQuestion(Request $request)
    {
        $api_url = env('API_URL') . '/saveQuestion/' . session('email') . '/' . $request->question_id;
        $response = Http::withToken(session('token'))->post($api_url);

        if ($response->successful()) {
            return response()->json([
                'success' => $response->json()['success'],
                'message' => $response->json()['message'],
            ]);
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to save question.';
            return response()->json([
                'success' => $response->json()['success'],
                'message' => $errorMessage,
            ]);
        }
    }
    public function unsaveQuestion(Request $request)
    {
        $api_url = env('API_URL') . '/unsaveQuestion/' . session('email') . '/' . $request->question_id;
        $response = Http::withToken(session('token'))->post($api_url);

        if ($response->successful()) {
            return response()->json([
                'success' => $response->json()['success'],
                'message' => $response->json()['message'],
            ]);
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to unsave question.';
            return response()->json([
                'success' => $response->json()['success'],
                'message' => $errorMessage,
            ]);
        }
    }
    public function showEditQuestionPage($id)
    {
        // 1. Ambil data pertanyaan dari API
        $api_url = env('API_URL') . '/questions/' . $id . '/view';
        $response = Http::withToken(session('token'))
                                ->post($api_url, ['email' => session('email')]);  // Kirim email untuk otorisasi

        if ($response->failed() || !$response->json()['success']) {
            return redirect()->route('home')->with('Error', 'Could not load question for editing.');
        }
        $questionData = $response->json()['data'];

        // Pastikan hanya pemilik pertanyaan yang bisa mengakses halaman edit
        // Cek `is_owner` yang dikirim dari API
        if (!($questionData['is_owner'] ?? false)) {
            return redirect()->route('home')->with('Error', 'You are not authorized to edit this question.');
        }

        // 2. Ambil semua tags (subjects) untuk ditampilkan di form
        $tags_api_url = env('API_URL') . '/subjects'; // Asumsi ada endpoint untuk mengambil semua tags
        $tags_response = Http::withToken(session('token'))->get($tags_api_url);

        if ($tags_response->failed() || !$tags_response->json()['success']) {
            return redirect()->route('home')->with('Error', 'Could not load the list of subjects.');
        }
        $allTags = $tags_response->json()['data'];

        // 3. prepare array selectedTagIdsOnLoad (ini yang sebelumnya ngga ada)
        $selectedTagIdsOnLoad = [];
        if (!empty($questionData['group_question'])) {
            $selectedTagIdsOnLoad = array_map(function($group) {
                return $group['subject']['id'];
            }, $questionData['group_question']);
        }

        return view('ask', [
            'questionToEdit' => $questionData,      
            'allTags' => $allTags,                  
            'selectedTagIdsOnLoad' => $selectedTagIdsOnLoad 
        ]);
    }

    public function updateQuestion(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'question' => 'required|string',
            'image' => 'nullable|image|max:2048', // 2048 supaya sama w/ addQuestion
            'remove_existing_image' => 'nullable|in:1',
            'selected_tags' => 'required|array|min:1',
            'selected_tags.*' => 'string|uuid', 
        ]);

        $apiRequest = Http::withToken(session('token'))->asMultipart();

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $apiRequest->attach(
                'image',
                file_get_contents($imageFile->getRealPath()),
                $imageFile->getClientOriginalName()
            );
        }

        $payload = [
            ['name' => 'title', 'contents' => $validated['title']],
            ['name' => 'question', 'contents' => $validated['question']],
        ];

        if ($request->has('remove_existing_image')) {
            $payload[] = ['name' => 'remove_existing_image', 'contents' => '1'];
        }

        foreach ($validated['selected_tags'] as $tagId) {
            $payload[] = ['name' => 'selected_tags[]', 'contents' => $tagId];
        }

        // call api di sini instead
        $apiUrl = env('API_URL') . "/questions/{$id}/updatePartial";

        try {
            $response = $apiRequest->post($apiUrl, $payload);
            return $response->json(); 

        } catch (\Exception $e) {
            Log::error("WEB updateQuestion API Call Failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error connecting to the API service.'], 500);
        }
    }
    public function deleteQuestion(Request $request, $id)
    {
        $api_url = env('API_URL') . '/questions/' . $id;

        try {
            $response = Http::withToken(session('token'))
                ->delete($api_url, ['email' => session('email')]);

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Question deleted successfully!']);
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to delete question.';
                return response()->json(['success' => false, 'message' => $errorMessage], $response->status());
            }
        } catch (\Exception $e) {
            Log::error("Error deleting question {$id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error during API request: ' . $e->getMessage()], 500);
        }
    }
}