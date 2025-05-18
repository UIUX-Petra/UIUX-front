<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;

class UserController extends Controller
{

    public function getAllUsers()
    {
        $api_url = env('API_URL') . '/userWithRecommendation';
        $data['email'] = session('email');
        $response = Http::withToken(session('token'))->get($api_url, $data);
        $responseData = json_decode($response, true);
        // dd($api_url);
        return $responseData['data'];
    }

    public function countUserVote()
    {
        $users = $this->getAllUsers();

        foreach ($users as &$user) {
            $countvotes = collect($user['question'])->sum(function ($question) {
                return $question['vote'] ?? 0;  // Default to 0 if no votes
            });

            $user['vote_count'] = $countvotes;
            $user['created_at'] = Carbon::parse($user['created_at'])->diffForHumans();
        }

        return $users;
    }


    public function orderUserBy()
    {
        // Get all users
        $users = $this->countUserVote();

        // Sort users by reputation (descending)
        $usersByReputation = $users;
        usort($usersByReputation, function ($a, $b) {
            return $b['reputation'] - $a['reputation']; // descending order
        });

        // Sort users by vote (descending)
        $usersByVote = $users;
        usort($usersByVote, function ($a, $b) {
            return $b['vote_count'] - $a['vote_count']; // descending order
        });

        $usersByNewest = $users;
        usort($usersByNewest, function ($a, $b) {
            // Ensure created_at is parsed as a DateTime object for proper comparison
            $dateA = new DateTime($a['created_at']);
            $dateB = new DateTime($b['created_at']);
            return $dateB <=> $dateA; // descending order
        });

        // Search for recommended users (assuming 'is_recommended' is a boolean or 1/0)
        $recUser = array_filter($users, function ($user) {
            return isset($user['is_recommended']) && $user['is_recommended'] == true;
        });

        // Convert the result to an array (since array_filter returns an array of matches)
        $recUser = array_values($recUser);

        // Log the results for debugging
        Log::info("Users ordered by reputation: " . print_r($usersByReputation, true));
        Log::info("Users ordered by vote: " . print_r($usersByVote, true));
        Log::info("Users ordered by new user: " . print_r($usersByNewest, true));

        return [
            'users_by_reputation' => $usersByReputation,
            'users_by_vote' => $usersByVote,
            'users_by_newest' => $usersByNewest,
            'recommended' => $recUser
        ];
    }



    public function getUserByEmail($email)
    {
        $api_url = env('API_URL') . '/users/get/' . $email;
        $response = Http::withToken(session('token'))->get($api_url);

        if ($response->failed()) {
            Log::error("API call failed for user email {$email}: " . $response->body());
            return null;
        }

        $responseData = json_decode($response->body(), true);
        // dd($responseData['data']);

        if (isset($responseData['data']) && is_array($responseData['data'])) {
            $userData = $responseData['data'];
            $questionCount = 0;
            $answerCount = 0;
            $followerCount = 0;
            $followingCount = 0;
            if (isset($userData['question']) && is_array($userData['question'])) {
                $questionCount = count($userData['question']);
            }
            if (isset($userData['answer']) && is_array($userData['answer'])) {
                $answerCount = count($userData['answer']);
            }

            if (isset($userData['followers']) && is_array($userData['followers'])) {
                $followerCount = count($userData['followers']);
            }
            if (isset($userData['following']) && is_array($userData['following'])) {
                $followingCount = count($userData['followers']);
            }

            $userData['questions_count'] = $questionCount;
            $userData['answers_count'] = $answerCount;
            $userData['followers_count'] = $followerCount;
            $userData['followings_count'] = $followingCount;
            // dd($userData);
            return $userData;
        } else {
            Log::warning("Unexpected API response structure or missing 'data' for user email {$email}: ", $responseData);
            return null;
        }
    }


    public function showUserQuestionsPage($userId)
    {
        $api_url_user = env('API_URL') . '/users/' . $userId;
        $responseUser = Http::withToken(session('token'))->get($api_url_user);

        if ($responseUser->failed()) {
            Log::error("API call failed to get user data for ID {$userId} for questions page: " . $responseUser->body());
            return null;
        }

        $userDataResponse = json_decode($responseUser->body(), true);

        if (isset($userDataResponse['success']) && $userDataResponse['success'] && isset($userDataResponse['data'])) {
            $userData = $userDataResponse['data'];
            // dd($userData); 
            return $userData;
        } else {
            Log::warning("Unexpected API response or user data not found for ID {$userId} for questions page: ", $userDataResponse);
            return null;
        }
    }

    public function getUserFollowers(string $email)
    {
        $user = $this->getUserByEmail($email) ?? ['username' => 'User Profile', 'followers' => []];
        $currUserId = session('email');

        $followers = collect($user['followers']); // Apakah currUser masuk/exist di user->followers

        $isFollowing = false;

        foreach ($followers as $follower) {
            if ($follower['email'] == $currUserId) {
                $isFollowing = True;
                break;
            }
        }
        $countFollowers = count($followers);
        $data['user'] = $user;
        $data['isFollowing'] = $isFollowing;
        $data['countFollowers'] = $countFollowers;
        dd($data);
        return $data;
    }



    public function nembakFollow(Request $reqs)
    {
        $api_url = env('API_URL') . '/users/' . $reqs->email . '/follow';
        $response = Http::withToken(session('token'))->post($api_url, [
            'emailCurr' => session('email')
        ]);

        return response()->json([
            'ok' => isset($response['success']) ? $response['success'] : false,
            'message' => $response['message'] ?? 'An error occurred during execution.',
            'data' => $response['data'] ?? ''
        ], $response->status());
    }
    public function getRecommendation()
    {
        $users = $this->getAllUsers();
        // Search for recommended users (assuming 'is_recommended' is a boolean or 1/0)
        $recUser = array_filter($users, function ($user) {
            return isset($user['is_recommended']) && $user['is_recommended'] == true;
        });

        // Convert the result to an array (since array_filter returns an array of matches)
        $recUser = array_values($recUser);
        return $recUser;
    }

    public function getMostViewedUser()
    {
        $api_url = env('API_URL') . '/getMostViewed/' . session('email');
        try {
            $response = Http::withToken(session('token'))->get($api_url);
            if ($response->successful()) {
                $responseData = $response->json();
                return $responseData['data'] ?? [];
            } else {
                Log::error('Failed to fetch most viewed user. API Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching most viewed user: ' . $e->getMessage());
        }
        return [];
    }

    public function editProfilePost(Request $request)
    {
        $email = session('email');
        $image = $request->file('image');
        $user = $this->getUserByEmail($email);
        $data = [
            'user_id' => $user['id'],
            'username' => $request->username,
            'biodata' => $request->biodata
        ];

        if ($image) {
            $timestamp = date('Y-m-d_H-i-s');
            $extension = $image->getClientOriginalExtension();
            $customFileName = "pp_" . session('email') . "_" . $timestamp . "." . $extension;

            $path = $image->storeAs("uploads/users/", $customFileName, 'public');
            $data['image'] = $path;
        }

        Log::info($data);

        $api_url = env('API_URL') . '/users/editProfileDULU';

        $response = Http::withToken(session('token'))->post($api_url, $data);
        Log::info($response);
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Profile has been Updated!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to Update User Profile.']);
        }
    }

    // beta ga bisa nembak api - buat tau per tag user ada brp post questions
    public function getTags($email)
    {
        $api_url = env('API_URL') . '/userTags';
        $data = ['email' => $email];
        $response = Http::get($api_url, $data);

        if ($response->failed()) {
            // Log the API URL and error message
            Log::error("API url: " . $api_url);
            Log::error("API call failed for user email {$email}: " . $response->body());
            return null;
        } else {
            $responseData = $response->json();
            return $responseData['data'] ?? [];
        }
    }
}
