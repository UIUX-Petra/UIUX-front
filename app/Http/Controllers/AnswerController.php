<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            $errorMessage = $response->json()['message'] ?? 'Failed to comment.';
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }
}
