<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

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
            \Log::error("API request to /questions-paginated failed: " . $response->body());

            return new LengthAwarePaginator([], 0, $per_page_from_request, $page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        $apiResponseData = $response->json();

        if (!isset($apiResponseData['success']) || $apiResponseData['success'] !== true || !isset($apiResponseData['data'])) {
            \Log::error("API request to /questions-paginated did not return a successful structure: " . $response->body());
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

        $queryParams = [
            'page' => $page,
            'per_page' => $per_page_from_request,
            'email' => session('email'),
        ];

        $response = Http::withToken(session('token'))->get($api_url, $queryParams);

        if ($response->failed()) {
            Log::error("API request to {$api_url} failed: " . $response->body());

            return new LengthAwarePaginator([], 0, $per_page_from_request, $page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        $apiResponseData = $response->json();

        if (
            !isset($apiResponseData['success']) || $apiResponseData['success'] !== true ||
            !isset($apiResponseData['data']) || !is_array($apiResponseData['data']) ||
            !isset($apiResponseData['data']['data']) ||
            !isset($apiResponseData['data']['total']) ||
            !isset($apiResponseData['data']['per_page']) ||
            !isset($apiResponseData['data']['current_page'])
        ) {
            Log::error("API request to {$api_url} did not return a successful and valid paginated structure: " . $response->body());
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5042',  // 5042 KB = 5 MB
        ]);

        // Get question data
        $title = $request->input('title');
        $question = $request->input('question');
        $image = $request->file('image');  // Expecting a single image file

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

            // Store the image in the public storage folder
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
        Log::info($response);
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Comment on this Question is submitted successfully!']);
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to comment.';
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }

    public function vote(Request $request)
    {
        $data['email'] = session('email');
        if ($request->vote === true) {
            $api_url = env('API_URL') . '/questions/' . $request->question_id . '/upvote';
        } else {
            $api_url = env('API_URL') . '/questions/' . $request->question_id . '/downvote';
        }
        $response = Http::withToken(session('token'))->post($api_url, $data);

        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Your Vote has been recorded']);
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
            return redirect()->back()->with('success', 'Question saved successfully!');
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to save question.';
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function unsaveQuestion(Request $request)
    {
        $api_url = env('API_URL') . '/unsaveQuestion/' . session('email') . '/' . $request->question_id;
        $response = Http::withToken(session('token'))->post($api_url);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Question unsaved successfully!');
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to save question.';
            return redirect()->back()->with('error', $errorMessage);
        }
    }
}
