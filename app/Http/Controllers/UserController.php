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
                return $question['vote'] ?? 0;
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

        return [
            'users_by_reputation' => $usersByReputation,
            'users_by_vote' => $usersByVote,
            'users_by_newest' => $usersByNewest,
            'recommended' => $recUser
        ];
    }

    public function getBasicUserByEmail($email)
    {
        $api_url = env('API_URL') . '/users/get/' . $email;
        $response = Http::withToken(session('token'))->get($api_url);

        if ($response->failed()) {
            Log::error("API call failed for user email {$email}: " . $response->body());
            return null;
        }

        $responseData = json_decode($response->body(), true);

        $groupedHistories = [];

        if (isset($responseData['data']['histories']) && is_array($responseData['data']['histories'])) {
            $histories = array_slice(array_reverse($responseData['data']['histories']), 0, 5);

            foreach ($histories as $historyItem) {
                $type = $historyItem['searched_type'] ?? null;
                $id = $historyItem['searched_id'] ?? null;
                $username = $historyItem['searched']['username']
                    ?? ($historyItem['searched']['user']['username'] ?? null);

                $title = $historyItem['searched']['title']
                    ?? ($historyItem['searched']['name'] ?? null);

                $email = $historyItem['searched']['email'] ?? null;

                if ($type && $id && $username) {
                    $groupedHistories[$type][$username] = ['id' => $id, 'title' => $title, 'email' => $email, 'historyId' => $historyItem['id']];
                }
            }
        }
        $responseData['data']['histories'] = $groupedHistories;
        return $responseData['data'];
    }
    public function getUserByEmail($email)
    {
        $api_url = env('API_URL') . '/users/get/' . $email;
        Log::info('api url', ['api_url' => $api_url]);

        $response = Http::withToken(session('token'))->get($api_url);

        if ($response->failed()) {
            Log::error("API call failed for user email {$email}: " . $response->body());
            return null;
        }

        $responseData = json_decode($response->body(), true);

        if (isset($responseData['data']) && is_array($responseData['data'])) {
            $originalUserData = $responseData['data'];
            $questionCount = 0;
            $answerCount = 0;
            $followerCount = 0;
            $followingCount = 0;

            // Mengubah $subjectCount menjadi array untuk menyimpan detail subjek
            $subjectsAggregated = []; // Ganti nama dari $subjectCount

            $allQuestions = $originalUserData['question'] ?? [];
            $topQuestionPost = null;

            if (is_array($allQuestions) && !empty($allQuestions)) {
                $questionCount = count($allQuestions);
                $topQuestionPost = $allQuestions[0];
                $topQuestionPost['vote'] = $topQuestionPost['vote'] ?? 0;
                $topQuestionPost['view'] = $topQuestionPost['view'] ?? 0;

                foreach ($allQuestions as $questionItem) {
                    // SubjectAggregation
                    if (isset($questionItem['group_question']) && is_array($questionItem['group_question'])) {
                        foreach ($questionItem['group_question'] as $group) {
                            if (isset($group['subject']['name']) && isset($group['subject']['abbreviation'])) {
                                $name = $group['subject']['name'];
                                $abbr = $group['subject']['abbreviation'];

                                // Menggunakan subjectName sebagai kunci unik untuk agregasi
                                if (!isset($subjectsAggregated[$name])) {
                                    $subjectsAggregated[$name] = [
                                        'name' => $name,
                                        'abbr' => $abbr,
                                        'count' => 0
                                    ];
                                }
                                $subjectsAggregated[$name]['count']++;
                            }
                        }
                    }

                    // Proses untuk menentukan top question post
                    $currentVote = $questionItem['vote'] ?? 0;
                    $currentView = $questionItem['view'] ?? 0;

                    if ($currentVote > $topQuestionPost['vote']) {
                        $topQuestionPost = $questionItem;
                    } elseif ($currentVote == $topQuestionPost['vote']) {
                        if ($currentView > ($topQuestionPost['view'] ?? 0)) {
                            $topQuestionPost = $questionItem;
                        }
                    }
                }
                $topQuestionPost['vote'] = $topQuestionPost['vote'] ?? 0;
                $topQuestionPost['view'] = $topQuestionPost['view'] ?? 0;

                if ($topQuestionPost !== null) {
                    $topQuestionPost['comment_count'] = count($topQuestionPost['comment'] ?? []);
                }
            }

            if (isset($originalUserData['answer']) && is_array($originalUserData['answer'])) {
                $answerCount = count($originalUserData['answer']);
                foreach ($originalUserData['answer'] as $answerItem) {
                    if (isset($answerItem['question']['group_question']) && is_array($answerItem['question']['group_question'])) {
                        foreach ($answerItem['question']['group_question'] as $group) {
                            if (isset($group['subject']['name']) && isset($group['subject']['abbreviation'])) {
                                $subjectName = $group['subject']['name'];
                                $subjectAbbr = $group['subject']['abbreviation'];

                                if (!isset($subjectsAggregated[$subjectName])) {
                                    $subjectsAggregated[$subjectName] = [
                                        'name' => $subjectName,
                                        'abbr' => $subjectAbbr,
                                        'count' => 0
                                    ];
                                }
                                $subjectsAggregated[$subjectName]['count']++;
                            }
                        }
                    }
                }
            }

            $followerCount = isset($originalUserData['followers']) && is_array($originalUserData['followers']) ? count($originalUserData['followers']) : 0;
            $followingCount = isset($originalUserData['following']) && is_array($originalUserData['following']) ? count($originalUserData['following']) : 0;

            $topSubjectsData = [];
            if (!empty($subjectsAggregated)) {
                // Mengubah dari array asosiatif (keyed by subject_name) menjadi array numerik
                $subjectList = array_values($subjectsAggregated);

                // Mengurutkan berdasarkan 'count' secara descending
                usort($subjectList, function ($a, $b) {
                    return $b['count'] <=> $a['count']; // PHP 7+ spaceship operator
                    // Untuk PHP < 7: return $b['count'] - $a['count'];
                });
                $topSubjectsData = $subjectList;
            }

            $groupedHistories = [];
            if (isset($originalUserData['histories']) && is_array($originalUserData['histories'])) {
                $histories = array_slice(array_reverse($originalUserData['histories']), 0, 5);
                foreach ($histories as $historyItem) {
                    $type = $historyItem['searched_type'] ?? null;
                    $id = $historyItem['searched_id'] ?? null;
                    $username = $historyItem['searched']['username']
                        ?? ($historyItem['searched']['user']['username'] ?? null);
                    $title = $historyItem['searched']['title']
                        ?? ($historyItem['searched']['name'] ?? null);
                    $emailHistory = $historyItem['searched']['email'] ?? null; // Ganti nama variabel agar tidak bentrok

                    if ($type && $id && $username) {
                        $groupedHistories[$type][$username] = ['id' => $id, 'title' => $title, 'email' => $emailHistory, 'historyId' => $historyItem['id']];
                    }
                }
            }

            return [
                'id' => $originalUserData['id'] ?? null,
                'username' => $originalUserData['username'] ?? null,
                'email' => $originalUserData['email'] ?? null,
                'image' => $originalUserData['image'] ?? null,
                'biodata' => $originalUserData['biodata'] ?? null,
                'reputation' => $originalUserData['reputation'] ?? 0,
                'user_achievement' => $originalUserData['user_achievement'] ?? [],
                'followers' => $originalUserData['followers'] ?? [],
                'following' => $originalUserData['following'] ?? [],
                'top_question_post' => $topQuestionPost,
                'top_subjects' => $topSubjectsData, // Sudah dalam format yang diinginkan
                'questions_count' => $questionCount,
                'answers_count' => $answerCount,
                'followers_count' => $followerCount,
                'followings_count' => $followingCount,
                'histories' => $groupedHistories,
            ];
        } else {
            Log::warning("Unexpected API response structure or missing 'data' for user email {$email}: ", (array) $responseData);
            return null;
        }
    }

    /**
     * Helper untuk menentukan status follow dari logged-in user terhadap target user.
     *
     * @param array $targetUser Data pengguna target.
     * @param array|null $loggedInUserFollowingArray Daftar email yang diikuti oleh pengguna yang login.
     * @param array|null $loggedInUserFollowersArray Daftar email followers dari pengguna yang login.
     * @param string $loggedInUserEmail Email pengguna yang login.
     * @return array ['follow_status' => string, 'is_mutual' => bool]
     */
    public function determineFollowStatus(array $targetUser, ?array $loggedInUserFollowingArray, ?array $loggedInUserFollowersArray, string $loggedInUserEmail): array
    {
        if ($targetUser['email'] === $loggedInUserEmail) {
            return ['follow_status' => 'is_self', 'is_mutual' => false];
        }

        $isFollowingTarget = $loggedInUserFollowingArray !== null && in_array($targetUser['email'], array_column($loggedInUserFollowingArray, 'email'));
        $targetIsFollowingLoggedInUser = $loggedInUserFollowersArray !== null && in_array($targetUser['email'], array_column($loggedInUserFollowersArray, 'email'));

        if ($isFollowingTarget) {
            return ['follow_status' => 'following', 'is_mutual' => $targetIsFollowingLoggedInUser];
        } elseif ($targetIsFollowingLoggedInUser) {
            return ['follow_status' => 'follows_you', 'is_mutual' => false];
        } else {
            return ['follow_status' => 'not_following', 'is_mutual' => false];
        }
    }

    /**
     * Menyiapkan daftar followers atau following dengan status relasi terhadap logged-in user.
     *
     * @param string $profileUserEmail Email pengguna yang profilnya dilihat.
     * @param string $type 'followers' atau 'following'.
     * @return array
     */
    public function getConnectionList(string $profileUserEmail, string $type = 'followers'): array
    {
        $profileUser = $this->getUserByEmail($profileUserEmail);
        if (!$profileUser) {
            // Handle jika user profil tidak ditemukan, mungkin redirect atau tampilkan error
            return ['profileUser' => null, 'list' => collect(), 'loggedInUser' => null, 'isOwnProfile' => false];
        }

        $loggedInUserEmail = session('email');
        $loggedInUser = null;
        $loggedInUserFollowingArray = null;
        $loggedInUserFollowersArray = null;

        if ($loggedInUserEmail) {
            $loggedInUser = $this->getUserByEmail($loggedInUserEmail); // Ambil data lengkap logged-in user
            if ($loggedInUser) {
                // Pastikan 'following' dan 'followers' adalah array sebelum di-pass
                $loggedInUserFollowingArray = is_array($loggedInUser['following']) ? $loggedInUser['following'] : [];
                $loggedInUserFollowersArray = is_array($loggedInUser['followers']) ? $loggedInUser['followers'] : [];
            }
        }

        $isOwnProfile = $loggedInUserEmail === $profileUserEmail;

        $listData = $profileUser[$type] ?? [];
        $processedList = collect($listData)->map(function ($item) use ($loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) {
            // Pastikan $item adalah array dan memiliki 'email'
            if (!is_array($item) || !isset($item['email'])) {
                // Log atau handle item yang tidak valid
                Log::warning("Invalid item structure in connection list for user: " . ($profileUserEmail ?? 'N/A'), ['item' => $item]);
                return null; // Atau return item asli jika tidak ingin memfilternya
            }
            $status = $loggedInUserEmail ? $this->determineFollowStatus($item, $loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) : ['follow_status' => 'not_logged_in', 'is_mutual' => false];
            $item['follow_status'] = $status['follow_status'];
            $item['is_mutual'] = $status['is_mutual'];
            return $item;
        })->filter(); // Hapus item null jika ada

        // Tambahkan status relasi untuk pengguna profil utama (jika bukan profil sendiri)
        if ($loggedInUserEmail && !$isOwnProfile) {
            $relationToProfileUser = $this->determineFollowStatus($profileUser, $loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail);
            $profileUser['current_user_relation'] = $relationToProfileUser;
        }


        return [
            'profileUser' => $profileUser,
            'list' => $processedList,
            'loggedInUser' => $loggedInUser, // Kirim data loggedInUser ke view
            'isOwnProfile' => $isOwnProfile,
            'type' => $type // Untuk menentukan tab mana yang aktif
        ];
    }


    public function showUserQuestionsPage($userId)
    {
        $api_url_user = env('API_URL') . '/users/' . $userId . '/questions';
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

        $followers = collect($user['followers']);

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
        // dd($data);
        return $data;
    }



    public function nembakFollow(Request $reqs)
    {
        $api_url = env('API_URL') . '/users/' . $reqs->email . '/follow';
        $response = Http::withToken(session('token'))->post($api_url, [
            'emailCurr' => session('email')
        ]);

        return response()->json([
            'success' => isset($response['success']) ? $response['success'] : false,
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

        $api_url = env('API_URL') . '/users/editProfileDULU';

        $response = Http::withToken(session('token'))->post($api_url, $data);
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
    public function getSavedQuestion()
    {
        $email = session('email');
        $api_url = env('API_URL') . '/getSavedQuestions/' . $email;
        $response = Http::withToken(session('token'))->get($api_url);
        if ($response->successful()) {
            $responseData = $response->json();
        } else {
            Log::error('Failed to fetch most viewed user. API Response: ' . $response->body());
        }
        $user = $this->getBasicUserByEmail($email);
        $data['questions'] = $responseData['data'];
        $data['username'] = $user['username'];
        $data['id'] = $user['id'];
        $data['image'] = $user['image'];
        $data['title'] = 'Saved Questions';
        $data['histories'] = $user['histories'];

        return $data;
    }

    public function addHistory($searchedId, Request $reqs)
    {
        $email = session('email');
        $api_url = env('API_URL') . '/histories';

        $data = ['email' => $email, 'searched_id' => $searchedId, 'searched_type' => $reqs->type];

        $response = Http::withToken(session('token'))->post($api_url, $data);
        if ($response->successful()) {
            // $responseData = $response->json();
        } else {
            Log::error('Failed to fetch most viewed user. API Response: ' . $response->body());
        }
    }
    public function deleteHistory(Request $request)
    {
        $api_url = env('API_URL') . '/histories/' . $request->id;

        $response = Http::withToken(session('token'))->delete($api_url);

        if ($response->successful()) {
        } else {
            Log::error('Failed to Delete History. API Response: ' . $response->body());
        }
        $responseData = $response->json();
        return $responseData;
    }
    public function clearHistory()
    {
        $email = session('email');
        $api_url = env('API_URL') . '/history/clear/' . $email;

        $data = ['email' => $email];

        $response = Http::withToken(session('token'))->post($api_url, $data);
        if ($response->successful()) {
            $responseData = $response->json();
            return $responseData;
        } else {
            Log::error('Failed to fetch most viewed user. API Response: ' . $response->body());
        }
    }

    public function submitComment(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'commentable_id' => 'required|uuid',
            'commentable_type' => 'required|string|in:question,answer',
        ]);

        // if (session('reputation') < 10) {
        //     return response()->json(['success' => false, 'message' => 'Your Reputation is Insufficient']);
        // }

        $apiData = [
            'email' => session('email'),
            'comment' => $request->comment,
            'commentable_id' => $request->commentable_id,
            'commentable_type' => $request->commentable_type,
        ];

        $apiUrl = env('API_URL') . '/comments';
        $response = Http::withToken(session('token'))->post($apiUrl, $apiData);

        if ($response->successful()) {
            $data = $response->object();
            $comment = $data->data->comment;
            $formattedComment = [
                'id' => $comment->id,
                'username' => $comment->user->username ?? 'User', // Beri nilai default
                'email' => $comment->user->email ?? '#',         // Beri nilai default
                'image' => $comment->user->image ?? null,      // Sertakan path gambar
                'comment' => $comment->comment,
                'timestamp' => $comment->created_at,
            ];
            return response()->json(['success' => true, 'message' => 'Comment is submitted successfully!', 'comment' => $formattedComment]);
        } else {
            $errorMessage = $response->json()['message'] ?? 'Failed to comment.';
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }
}
