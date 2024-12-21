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
        // $email = session('email');
        $api_url = env('API_URL') . '/users/get/' . $email;
        $response = Http::withToken(session('token'))->get($api_url);
        $response = json_decode($response, true);
        // dd($response['data']);
        return $response['data'];
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

    public function popular()
    {
        $data['title'] = 'Popular';
        return view('popular', $data);
    }

    public function seeProfile()
    {
        $data['title'] = 'My Profile';
        $email = session('email');

        $currUser = $this->getUserByEmail($email);
        $data['currUser'] = $currUser;
        $followers = collect($currUser['followers']);
        $countFollowers = count($followers);
        $data['countFollowers'] = $countFollowers;
        $data['image'] = $currUser['image'];

        return view('profile', $data);
    }

    public function editProfile()
    {
        $data['title'] = 'Edit Profile';

        $email = session('email');
        $currUser = $this->getUserByEmail($email);
        $data['user'] = $currUser;
        return view('editProfile', $data);
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



    public function askPage()
    {
        $api_url = env('API_URL') . '/tags';
        $response = Http::withToken(session('token'))->get($api_url);
        $response = json_decode($response, true);
        Log::info($response);

        $data['data'] = $response['data'];
        $data['title'] = 'Ask a Question';
        $user = $this->getUserByEmail(session('email'));
        $data['image'] = $user['image'];
        return view('ask', $data);
    }

    public function testUI()
    {
        $data['title'] = 'Popular';
        return view('question', $data);
    }

}
