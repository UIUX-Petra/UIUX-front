<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    protected $userController;
    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }
    public function getAllAnswers($id)
    {
        $api_url = env('API_URL') . '/answers/' . $id;
        $response = Http::get($api_url);
        $response = json_decode($response, true);
        // dd($response['data']);
        return $response['data'];
    }

    public function submitAnswer(Request $request, $questionId)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'answer' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5042',  // 5042 KB = 5 MB
        ]);

        $image = $request->file('image');
        $answer = $request->input('answer');
        $data["answer"] = $answer;
        if ($image) {
            $timestamp = now()->format('Y-m-d_H-i-s');
            $extension = $image->getClientOriginalExtension();
            $customFileName = "a_" . session('email') . "_" . $questionId . "_" . $timestamp . "_" . "." . $extension;
            $path = $image->storePubliclyAs("uploads/answers/" . $questionId, $customFileName, 'public');
            $data['image'] = $path;
        }

        $data['email'] = session('email');
        $data['question_id'] = $questionId;

        $api_url = env('API_URL') . '/answers';
        $response = Http::withToken(session('token'))->post($api_url, $data);

        if ($response->successful()) {
            $data = $response->object();

            $answer = $data->data->answer;

            $formattedAnswer = [
                'id' => $answer->id,
                'username' => $answer->user->username ?? null,
                'image' => $answer->image,
                'answer' => $answer->answer,
                'vote' => $answer->vote,
                'timestamp' => $answer->created_at,
            ];

            return response()->json(['success' => true, 'message' => 'Answer submitted successfully!', 'answer' => $formattedAnswer]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to submit answer.']);
        }
    }

    public function vote(Request $request)
    {
        // kirim email
        $data['email'] = session('email');
        $vote = filter_var($request->vote, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($vote === true) {
            $api_url = env('API_URL') . '/answers/' . $request->answer_id . '/upvote';
        } else {
            $api_url = env('API_URL') . '/answers/' . $request->answer_id . '/downvote';
        }
        $response = Http::withToken(session('token'))->post($api_url, $data);
        if ($response->successful()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Your Vote has been recorded',
                    'voteAnswerUpdated' => $response->json()['data']
                ]
            );
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to vote.';
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }

    public function viewUserAnswers(Request $request, $userId)
    {
        $token = session('token');
        if (!$token) {
            return redirect()->route('loginOrRegist')->with('Error', 'Please log in to view answers.');
        }

        $viewedUserApiUrl = env('API_URL') . '/users/' . $userId;
        $viewedUserResponse = Http::withToken($token)
                                ->acceptJson()
                                ->get($viewedUserApiUrl);

        if (!$viewedUserResponse->successful()) {
            Log::error("Failed to fetch user (ID: {$userId}) for answers page from {$viewedUserApiUrl}. Status: " . $viewedUserResponse->status() . " Body: " . $viewedUserResponse->body());
            abort(404, 'User not found.');
        }
        $viewedUser = $viewedUserResponse->json('data');
        if (!$viewedUser) {
            Log::error("User data not found in API response for user ID {$userId}.");
            abort(404, 'User data could not be retrieved.');
        }

        $page = $request->input('page', 1);
        $perPage = 10; 

        $answersApiUrl = env('API_URL') . '/answers-paginated';
        $answersApiResponse = Http::withToken($token)
            ->acceptJson()
            ->get($answersApiUrl, [
                'user_id' => $userId,
                'page' => $page,
                'per_page' => $perPage,
            ]);

        $answersPaginator = null;
        $apiError = null;

        if ($answersApiResponse->successful() && isset($answersApiResponse->json()['data']['data'])) {
            $apiPaginatedData = $answersApiResponse->json()['data'];
            $answersPaginator = new LengthAwarePaginator(
                $apiPaginatedData['data'],
                $apiPaginatedData['total'],
                $apiPaginatedData['per_page'],
                $apiPaginatedData['current_page'],
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            Log::error("Failed to fetch paginated answers for user ID {$userId} from {$answersApiUrl}. Status: " . $answersApiResponse->status() . " Body: " . $answersApiResponse->body());
            $apiError = 'Could not retrieve answers for this user at this time.';
        }

        $loggedInUserArray = null;
        if (session('email')) {
            $loggedInUserArray = $this->userController->getUserByEmail(session('email'));
        }

        return view('userAnswers.index', [
            'title' => htmlspecialchars($viewedUser['username'] ?? 'User') . "'s Answers",
            'username' => $loggedInUserArray['username'],
            'id' => $loggedInUserArray['id'],
            'user' => $viewedUser,
            'answers' => $answersPaginator,
            'loggedInUser' => $loggedInUserArray,
            'apiError' => $apiError,
            'histories' => $loggedInUserArray['histories'],
            'image' => $loggedInUserArray['image'] ?? null,
        ]);
    }

    /**
     * Show the form for editing an answer.
     */
    public function editAnswerForm($answerId)
    {
        $token = session('token');
        $loggedInUserId = session('user_id');

        if (!$loggedInUserId) {
            return redirect()->route('loginOrRegist')->with('Error', 'Please log in to edit answers.');
        }

        $apiUrl = rtrim(env('API_URL'), '/') . '/answers/' . $answerId;
        $response = Http::withToken($token)
                        ->acceptJson()
                        ->get($apiUrl); // API GET /answers/{id}
        $user = $this->userController->getUserByEmail(session('email'));
        

        if ($response->successful()) {
            $answer = $response->json('data');
            if ($answer && isset($answer['user_id']) && $answer['user_id'] == $loggedInUserId) {
                return view('userAnswers.edit', [
                    'title' => 'Edit Your Answer',
                    'answer' => $answer,
                    'image' => $user['image'] ?? null,
                    'user' => $user,
                    'histories' => $user['histories'],
                    'id' => $user['id'],
                ]);
            } elseif ($answer) {
                return redirect()->route('user.answers.index', ['userId' => $answer['user_id']])
                                 ->with('Error', 'You are not authorized to edit this answer.');
            } else {
                return redirect()->route('popular')->with('Error', 'Answer not found.');
            }
        } else {
            Log::error("Failed to fetch answer (ID: {$answerId}) for edit from {$apiUrl}. Status: " . $response->status() . " Body: " . $response->body());
            return redirect()->route('popular')->with('Error', 'Could not retrieve answer for editing.');
        }
    }

    /**
     * Update the specified answer. (Called by JS from edit form)
     */
    public function updateAnswer(Request $request, $answerId)
    {
        $token = session('token');
        $loggedInUserId = session('user_id');

        if (!$loggedInUserId) {
            return response()->json(['success' => false, 'message' => 'Authentication required.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'answer_content' => 'required|string|min:5',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg|max:5048',
            'remove_existing_image' => 'nullable|in:1,true', 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        $currentAnswerApiUrl = rtrim(env('API_URL'), '/') . '/answers/' . $answerId;
        $currentAnswerResponse = Http::withToken($token)->acceptJson()->get($currentAnswerApiUrl);

        if (!$currentAnswerResponse->successful() || !$currentAnswerResponse->json('data')) {
            return response()->json(['success' => false, 'message' => 'Original answer not found or API error.'], 404);
        }
        $currentAnswer = $currentAnswerResponse->json('data');
        if ($currentAnswer['user_id'] != $loggedInUserId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to update this answer.'], 403);
        }

        $apiUpdateUrl = rtrim(env('API_URL'), '/') . '/answers/' . $answerId . '/updatePartial';
        $pendingRequest = Http::withToken($token)->acceptJson();

        $payload = ['answer' => $request->input('answer_content')];

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $questionIdForPath = $currentAnswer['question_id'] ?? 'answer_assets';
            $timestamp = now()->format('Ymd_His');
            $extension = $imageFile->getClientOriginalExtension();
            $userIdentifier = str_replace(['@', '.'], ['_', '_'], session('email') ?? 'user');
            $customFileName = "a_upd_" . $userIdentifier . "_qid" . $questionIdForPath . "_" . $timestamp . "." . $extension;
            // Store image locally
            $imagePath = $imageFile->storeAs("uploads/answers_images/" . $questionIdForPath, $customFileName, 'public');
            
            // Attach file for multipart request
            $pendingRequest->attach('image_file', file_get_contents($imageFile->getRealPath()), $customFileName);
            $payload['image'] = $imagePath; 
        } elseif ($request->input('remove_existing_image') === '1' || $request->input('remove_existing_image') === true) {
            $payload['image'] = null;
            $payload['remove_existing_image_flag'] = true;
        }
        
        $updateResponse = $pendingRequest->post($apiUpdateUrl, $payload);


        if ($updateResponse->successful()) {
            return response()->json(['success' => true, 'message' => 'Answer updated successfully!']);
        } else {
            Log::error("Failed to update answer (ID: {$answerId}) via API {$apiUpdateUrl}. Status: " . $updateResponse->status() . " Body: " . $updateResponse->body());
            return response()->json([
                'success' => false,
                'message' => $updateResponse->json('message', 'Failed to update answer.'),
                'errors' => $updateResponse->json('errors')
            ], $updateResponse->status());
        }
    }

    /**
     * Handle deletion of an answer (called via AJAX by JavaScript).
     */
    public function deleteAnswer($answerId) 
    {                                      
        $token = session('token');
        $loggedInUserId = session('user_id');

        if (!$loggedInUserId) {
            return response()->json(['success' => false, 'message' => 'Authentication required.'], 401);
        }

        $answerCheckApiUrl = rtrim(env('API_URL'), '/') . '/answers/' . $answerId;
        $answerCheckResponse = Http::withToken($token)->acceptJson()->get($answerCheckApiUrl);

        if ($answerCheckResponse->successful()) {
            $answerData = $answerCheckResponse->json('data');
            if (!$answerData || $answerData['user_id'] != $loggedInUserId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Answer not found or API error.'], $answerCheckResponse->status());
        }

        $apiDeleteUrl = rtrim(env('API_URL'), '/') . '/answers/' . $answerId;
        $deleteResponse = Http::withToken($token)->acceptJson()->delete($apiDeleteUrl);

        if ($deleteResponse->successful()) {
            return response()->json(['success' => true, 'message' => 'Answer deleted successfully.']);
        } else {
            Log::error("Failed to delete answer (ID: {$answerId}) from {$apiDeleteUrl}. Status: " . $deleteResponse->status() . " Body: " . $deleteResponse->body());
            return response()->json([
                'success' => false,
                'message' => $deleteResponse->json('message', 'Could not delete the answer.')
            ], $deleteResponse->status());
        }
    }
}
