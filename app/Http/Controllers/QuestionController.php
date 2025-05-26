<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
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
        $api_url = $api_base_url . '/questions-paginated';
        $page = $request->input('page', 1);
        $per_page_from_request = $request->input('per_page', 10);

        $response = Http::withToken(session('token'))->get($api_url, [
            'page' => $page,
            'per_page' => $per_page_from_request,
            'email' => session('email'),
        ]);

        if ($response->failed()) {
            Log::error("API request to /questions-paginated failed: " . $response->body());

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
            'email' => session('email'), // Pastikan session 'email' ada dan valid
            'sort_by' => $sortBy,
        ];

        if (!empty($filterTag)) {
            $queryParams['filter_tag'] = $filterTag;
        }
        if (!empty($searchTerm)) {
            $queryParams['search_term'] = $searchTerm;
        }

        Log::info("QuestionController: Requesting API: {$api_url} with params: " . json_encode($queryParams));

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
        return $questionData;
    }


    public function addQuestion(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'title' => 'required|string',
            'question' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5042',
        ]);

        // Get question data
        $title = $request->input('title');
        $question = $request->input('question');
        $image = $request->file('image');

        $api_url = env('API_URL') . '/questions';

        $data = [
            'title' => $title,
            'question' => $question,
            'email' => session('email'),
            'tag_id' => $request->subject_id
        ];

        // If an image is uploaded, process it
        if ($image) {
            $timestamp = date('Y-m-d_H-i-s');
            $extension = $image->getClientOriginalExtension();
            $customFileName = "q_" . session('email') . "_" . $timestamp . "." . $extension;

            $path = $image->storeAs("uploads/questions/", $customFileName, 'public');
            $data['image'] = $path;

            Log::info("Image uploaded to: " . $path);  // Log image upload path for debugging
        }

        Log::info("Data to be sent: ", $data);

        try {
            $response = Http::withToken(session('token'))->post($api_url, $data);

            Log::info("API Response Status: " . $response->status());
            Log::info("API Response Body: " . $response->body());

            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Question submitted successfully!']);
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to submit question.';
                return response()->json(['success' => false, 'message' => $errorMessage]);
            }
        } catch (\Exception $e) {
            Log::error("Error submitting question: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error during API request']);
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
            Log::info("WEB saveEditedQuestion: New image stored at final location: " . $path);
        }
        if ($request->has('remove_existing_image') && $request->input('remove_existing_image') == '1') {
            $apiPayload['remove_existing_image_flag'] = true;
            Log::info("WEB saveEditedQuestion: Flag set to remove existing image.");
        }

        $apiUrlForUpdate = env('API_URL') . "/questions/{$questionId}/updatePartial";

        Log::info("WEB saveEditedQuestion: Sending data to API: ", $apiPayload);

        try {
            $response = Http::withToken(session('token'))
                ->post($apiUrlForUpdate, $apiPayload);

            Log::info("WEB saveEditedQuestion: API Update Response Status: " . $response->status());
            Log::info("WEB saveEditedQuestion: API Update Response Body: " . $response->body());

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
    public function submitQuestionComment(Request $request, $questionId)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        if (!isset($request->answer_id)) {
            $data['question_id'] = $questionId;
        } else {
            $data['answer_id'] = $questionId;
        }

        if (session('reputation') < 11) {
            return response()->json(['success' => false, 'message' => 'Your Reputation is Insufficient']);
        }

        $data['email'] = session('email');
        $data['comment'] = $request->comment;
        Log::info("Data to be sent: ", $data);

        $api_url = env('API_URL') . '/comments';
        $response = Http::withToken(session('token'))->post($api_url, $data);
        // Log::info($response);
        if ($response->successful()) {

            $data = $response->object();

            $comment = $data->data->comment;

            $formattedComment = [
                'id' => $comment->id,
                'username' => $comment->user->username ?? null,
                'comment' => $comment->comment,
                'timestamp' => $comment->created_at,
            ];
            return response()->json(['success' => true, 'message' => 'Comment is submitted successfully!', 'comment' => $formattedComment]);
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to comment.';
            return response()->json(['success' => false, 'message' => $errorMessage]);
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
}
