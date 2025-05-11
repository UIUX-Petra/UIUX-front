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
        $page = $request->input('page', 1);
        $per_page = 10;
        $api_url = env('API_URL') . '/questions-paginated'; 
    
        $response = Http::withToken(session('token'))->get($api_url, [
            'page' => $page,
            'per_page' => $per_page,
        ]);
    
      
        if ($response->failed()) {
            logger()->error('API request failed for questions: ' . $response->body() . ' URL: ' . $api_url);
            return new LengthAwarePaginator([], 0, $per_page, $page, [
                'path' => $request->url(),
                'query' => $request->query()
            ]);
        }
    
        $apiResponseData = $response->json();
    
        if (isset($apiResponseData['success']) && $apiResponseData['success'] && isset($apiResponseData['data'])) {
            $paginatedResultFromApi = $apiResponseData['data'];
    
            $questions_for_current_page = $paginatedResultFromApi['data'];
            $total_questions = $paginatedResultFromApi['total'];
            $api_per_page = $paginatedResultFromApi['per_page'];
            $api_current_page = $paginatedResultFromApi['current_page'];
    
            if (!empty($questions_for_current_page) && (!isset($questions_for_current_page[0]['comments_count']) && !isset($questions_for_current_page[0]['comment_count']))) {
                foreach ($questions_for_current_page as &$question_item) {
                    $question_item['comments_count'] = (isset($question_item['comment']) && is_array($question_item['comment']))
                                                  ? count($question_item['comment'])
                                                  : 0;
                }
                unset($question_item);
            }
    
            $paginator = new LengthAwarePaginator(
                $questions_for_current_page,
                $total_questions,
                $api_per_page,
                $api_current_page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
    
            return $paginator;
        } else {
            logger()->error('Invalid API response structure for questions. URL: ' . $api_url . ' Response: ' . json_encode($apiResponseData));
            return new LengthAwarePaginator([], 0, $per_page, $page, [
                'path' => $request->url(),
                'query' => $request->query()
            ]);
        }
    }

    public function getAllQuestionsByPopularity(Request $request)
    {
        $api_url = env('API_URL') . '/questions';
        $response = Http::get($api_url);
        $response = json_decode($response, true);

        // Get the data (questions)
        $data = $response['data'];

        // Loop through each question and count comments
        foreach ($data as &$question) {
            // If comments is null or not an array, set it to an empty array, otherwise count the array length
            $question['comments_count'] = (is_array($question['comment']) && $question['comment'] !== null)
                ? count($question['comment'])
                : 0;
        }
        usort($data, function ($a, $b) {
            return $b['vote'] <=> $a['vote']; });

        $page = $request->input('page', 1);
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        $paginated_data = array_slice($data, $offset, $per_page);
        $paginator = new LengthAwarePaginator(
            $paginated_data,
            count($data),
            $per_page,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );


        // dd($data);
        // Return the updated data
        return $paginator;
    }


    public function getQuestionDetails($id)
    {
        $data['email'] = session('email');
        $api_url = env('API_URL') . '/questions/' . $id . '/view';
        $response = Http::post($api_url, $data);
        $response = json_decode($response, true);
        $questionData = $response['data'];

        $comments = collect($questionData['comment']); // Apakah currUser masuk/exist di user->comments
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
}
