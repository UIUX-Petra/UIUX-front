<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UserController;
use Illuminate\Container\Attributes\Tag;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;

class MainController extends Controller
{
  public $userController;
  public $answerController;
  public $questionController;
  public $tagController;
  public function __construct(UserController $userController, AnswerController $answerController, QuestionController $questionController, TagController $tagController)
  {
    $this->userController = $userController;
    $this->answerController = $answerController;
    $this->questionController = $questionController;
    $this->tagController = $tagController;
  }

  public function home(Request $request)
  {
    $user = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $user['image'];
    $data['username'] = $user['username'];
    $data['title'] = 'Home';
    $questions = $this->questionController->getAllQuestions($request);
    $data['questions'] = $questions;
    $data['user'] = $user;
    // dd($data);]
    return view('home', $data);
  }

  public function askPage()
  {
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    $tags = $this->tagController->getTagOnly();
    $data['data'] = $tags;
    $data['title'] = 'Ask a Question';
    return view('ask', $data);
  }

  public function seeProfile()
  {
    $data['title'] = 'My Profile';
    $email = session('email');
    $currUser = $this->userController->getUserByEmail($email);
    $data['currUser'] = $currUser;
    // dd($data);
    $data['image'] = $currUser['image'];
    return view('profile', $data);
  }

  public function viewUser(string $email)
  {
    $userViewed = $this->userController->getUserFollowers($email);
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    $data['title'] = 'PROFILE | ' . $userViewed['user']['username'];
    $data['userViewed'] = $userViewed['user'];
    $data['image'] = $currUser['image'];
    // dd($data);
    return view('otherProfiles', $data);
  }

  public function editProfile()
  {
    $data['title'] = 'Edit Profile';

    $email = session('email');
    $currUser = $this->userController->getUserByEmail($email);
    $data['user'] = $currUser;
    $data['image'] = $currUser['image'];
    return view('editProfile', $data);
  }
  public function userQuestions($userId)
  {
    $data['user'] = $this->userController->showUserQuestionsPage($userId);
    $image = $data['user']['image'];
    $data['image'] = $image;
    $data['title'] = 'User Questions';
    // dd($data['user']);
    return view('userQuestions', $data);
  }

  public function userConnections(Request $request, string $email)
    {
        $initialTabType = $request->input('type', 'followers');
        if (!in_array($initialTabType, ['followers', 'following'])) {
            $initialTabType = 'followers'; // Default ke followers jika query 'type' tidak valid
        }

        $profileUser = $this->userController->getUserByEmail($email);

        if (!$profileUser) {
            abort(404, 'User not found.');
        }

        $loggedInUserEmail = session('email');
        $loggedInUser = null;
        $loggedInUserFollowingArray = []; 
        $loggedInUserFollowersArray = []; 

        if ($loggedInUserEmail) {
            $loggedInUser = $this->userController->getUserByEmail($loggedInUserEmail);
            if ($loggedInUser) {
                $loggedInUserFollowingArray = is_array($loggedInUser['following']) ? $loggedInUser['following'] : [];
                $loggedInUserFollowersArray = is_array($loggedInUser['followers']) ? $loggedInUser['followers'] : [];
            }
        }

        $isOwnProfile = $loggedInUserEmail === $profileUser['email'];

        // 3. Fungsi helper lokal untuk memproses daftar (followers atau following)
        $processList = function (array $rawListFromProfileUser) use ($loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) {
            return collect($rawListFromProfileUser)->map(function ($item) use ($loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) {
                if (!is_array($item) || !isset($item['email'])) {
                    Log::warning("Invalid item structure in connection list for profile.", ['item_structure' => $item]);
                    return null; // Abaikan item yang tidak valid
                }
                // Panggil method public determineFollowStatus dari instance userController
                $status = $loggedInUserEmail ? $this->userController->determineFollowStatus($item, $loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) : ['follow_status' => 'not_logged_in', 'is_mutual' => false];
                $item['follow_status'] = $status['follow_status'];
                $item['is_mutual'] = $status['is_mutual'];
                return $item;
            })->filter()->values(); // filter() untuk menghapus null, values() untuk re-index collection
        };

        // 4. Proses daftar followers dan following secara terpisah
        // Pastikan $profileUser['followers'] dan $profileUser['following'] adalah array
        $rawFollowers = isset($profileUser['followers']) && is_array($profileUser['followers']) ? $profileUser['followers'] : [];
        $rawFollowing = isset($profileUser['following']) && is_array($profileUser['following']) ? $profileUser['following'] : [];

        $followersList = $processList($rawFollowers);
        $followingList = $processList($rawFollowing);

        // 5. Tambahkan status relasi untuk pengguna profil utama (jika bukan profil sendiri)
        if ($loggedInUser && !$isOwnProfile) {
            $relationToProfileUser = $this->userController->determineFollowStatus(
                $profileUser, // $profileUser adalah array yang sudah memiliki 'email'
                $loggedInUserFollowingArray,
                $loggedInUserFollowersArray,
                $loggedInUserEmail
            );
            // Tambahkan ke array $profileUser sebelum dikirim ke view
            $profileUser['current_user_relation'] = $relationToProfileUser;
        }

        $data = [
            'title' => ($profileUser['username'] ?? 'User') . ($initialTabType === 'followers' ? ' - Followers' : ' - Following'),
            'image' => $profileUser['image'],
            'profileUser' => $profileUser,
            'followersList' => $followersList,
            'followingList' => $followingList,
            'loggedInUser' => $loggedInUser,
            'isOwnProfile' => $isOwnProfile,
            'activeTab' => $initialTabType
        ];
        
        // ---- UNTUK DEBUGGING ----
        // Hapus atau beri komentar setelah selesai debugging
        // dd([
        // 'profileUser_email' => $profileUser['email'],
        // 'loggedInUser_email' => $loggedInUserEmail,
        // 'active_tab' => $initialTabType,
        // 'followers_emails' => $followersList->pluck('email')->all(),
        // 'following_emails' => $followingList->pluck('email')->all(),
        // 'raw_profile_followers_count' => count($rawFollowers),
        // 'raw_profile_following_count' => count($rawFollowing),
        // 'profile_user_data_from_api_sample' => $profileUser // Untuk melihat struktur lengkap
        // ]);
        // ---- AKHIR DEBUGGING ----

        return view('connections', $data); 
    }


  public function popular(Request $request)
  {
    $email = session('email');
    $user = $this->userController->getUserByEmail($email);
    $data['username'] = $user['username'];
    $data['image'] = $user['image'];
    $data['title'] = 'Home';
    $questions = $this->questionController->getAllQuestionsByPopularity($request);
    $data['questions'] = $questions;
    $data['image'] = $user['image'];
    // dd($data);
    return view('popular', $data);
  }

  // hrse terima param id question, nih aku cuman mau coba view
  public function viewAnswers($questionId)
  {
    $data['question'] = $this->questionController->getQuestionDetails($questionId);
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    $data['title'] = 'View Answers';
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    // dd($data);
    Log::info($data['question']);
    return view('viewAnswers', $data);
  }

  public function viewAllUsers()
  {
    $data['title'] = 'View Users';
    $user = $this->userController->orderUserBy();

    $usersByReputation = $user['users_by_reputation'];
    $usersByVote = $user['users_by_vote'];
    $usersByNewest = $user['users_by_newest'];

    // hapus user yang login dari view All User
    if (session()->has('email')) {
      $loggedInUserEmail = session('email');

      $usersByReputation = array_filter($usersByReputation, function ($user) use ($loggedInUserEmail) {
        return $user['email'] !== $loggedInUserEmail;
      });

      $usersByVote = array_filter($usersByVote, function ($user) use ($loggedInUserEmail) {
        return $user['email'] !== $loggedInUserEmail;
      });

      $usersByNewest = array_filter($usersByNewest, function ($user) use ($loggedInUserEmail) {
        return $user['email'] !== $loggedInUserEmail;
      });

      // Re-index array
      $usersByReputation = array_values($usersByReputation);
      $usersByVote = array_values($usersByVote);
      $usersByNewest = array_values($usersByNewest);
    }

    $data['order_by_reputation'] = $usersByReputation;
    $data['order_by_vote'] = $usersByVote;
    $data['order_by_newest'] = $usersByNewest;
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    $data['recommended'] = $user['recommended'];
    return view('viewAllUsers', $data);
  }

  public function viewTags()
  {
    $tags = $this->tagController->getAllTags();
    $data['tags'] = $tags;
    $data['title'] = 'View Tags';
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];

    return view('viewTags', $data);
  }

  public function leaderboard()
  {
    $data['title'] = 'Leaderboard';
    $data['tags'] = $this->tagController->getTagOnly();
    $data['mostViewed'] = $this->userController->getMostViewedUser();
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    return view('leaderboard', $data);
  }
}
