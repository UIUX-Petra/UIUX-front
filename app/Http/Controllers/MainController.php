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
    $data['image'] = $user['image'] ?? null;
    $data['username'] = $user['username'] ?? 'Guest';
    $data['id'] = $user['id'];
    $data['title'] = 'Home';
    $questions = $this->questionController->getAllQuestions($request);

    if ($request->ajax() || $request->wantsJson()) {
      $questionsHtml = view('partials.questions_only_list', ['questions' => $questions])->render();
      $paginationHtml = $questions->links()->toHtml();

      return response()->json([
        'success' => true,
        'questions_html' => $questionsHtml,
        'pagination_html' => $paginationHtml,
      ]);
    }
    $data['questions'] = $questions;
    $data['user'] = $user;
    $data['histories'] = $user['histories'];
    // dd($data);
    return view('home', $data);
  }

  public function askPage(Request $request, $questionId = null) // Added Request for consistency if needed later
  {
    $viewData = [];
    $currUser = $this->userController->getUserByEmail($request->session()->get('email'));
    $viewData['image'] = $currUser['image'] ?? null;
    $viewData['username'] = $currUser['username'] ?? null;
    $viewData['id'] = $currUser['id'];

    $allTags = $this->tagController->getAllTags();
    $viewData['allTags'] = $allTags;
    $viewData['questionToEdit'] = null;
    if ($questionId !== null) {
      $questionDetails = $this->questionController->getQuestionDetails($questionId);
      if ($questionDetails && isset($currUser['id']) && isset($questionDetails['user_id']) && $currUser['id'] === $questionDetails['user_id']) {
        $viewData['questionToEdit'] = $questionDetails;
      } else if ($questionDetails) {
        return redirect()->route('home')
          ->with('Error', 'You are not authorized to edit this question.');
      } else {
        return redirect()->route('askPage')
          ->with('Error', 'The question you are trying to edit was not found.');
      }
    }
    $viewData['title'] = isset($viewData['questionToEdit']) ? 'Edit Question' : 'Ask a Question';
    $viewData['histories'] = $currUser['histories'];

    return view('ask', $viewData);
  }
  public function seeProfile()
  {
    $data['title'] = 'My Profile';
    $email = session('email');
    $currUser = $this->userController->getUserByEmail($email);
    $data['currUser'] = $currUser;
    $data['username'] = $currUser['username'];
    $data['id'] = $currUser['id'];
    // dd($data);
    $data['image'] = $currUser['image'];
    $data['histories'] = $currUser['histories'];
    return view('profile', $data);
  }

  public function viewUser(string $email)
  {
    $userViewed = $this->userController->getUserFollowers($email);
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['username'] = $currUser['username'];
    $data['image'] = $currUser['image'];
    $data['id'] = $currUser['id'];
    $data['title'] = 'PROFILE | ' . $userViewed['user']['username'];
    $data['userViewed'] = $userViewed['user'];

    $emailSession = session('email');
    $data['isOwnProfile'] = $email === $emailSession ? true : false;

    $data['userRelation'] = 0; // tak ada relasi (asing bjir)
    foreach ($userViewed['user']['followers'] as $follower) {
      if ($follower['id'] == $currUser['id']) {
        $data['userRelation'] = 1; // aku follow dirinya -> btn bertuliskan following
        break;
      }
    }

    if ($data['userRelation'] === 0) { // jika habis di cek, trnyt ak ga folo dia, cek apakah dia folo ak -> btn bertuliskan follow back
      foreach ($userViewed['user']['following'] as $following) {
        if ($following['id'] == $currUser['id']) {
          $data['userRelation'] = 2;
          break;
        }
      }
    }

    $data['histories'] = $currUser['histories'];
    // dd($data);
    return view('otherProfiles', $data);
  }

  public function editProfile()
  {
    $data['title'] = 'Edit Profile';

    $email = session('email');
    $currUser = $this->userController->getUserByEmail($email);
    $data['username'] = $currUser['username'];
    $data['user'] = $currUser;
    $data['image'] = $currUser['image'];
    $data['id'] = $currUser['id'];

    $data['histories'] = $currUser['histories'];
    return view('editProfile', $data);
  }
  public function userQuestions($id)
  {
    $data['user'] = $this->userController->showUserQuestionsPage($id);
    // dd($data['user']);
    $data['username'] = $data['user']['username'];
    $data['image'] = $data['user']['image'];
    $data['id'] = $data['user']['id'];
    $data['title'] = htmlspecialchars($data['user']['username'] ?? 'User') . "'s Questions";
    if (session('email') != $data['user']['email']) {
      $currUser = $this->userController->getBasicUserByEmail(session('email'));
      $data['viewer'] = $currUser;
      $data['username'] = $currUser['username'];
      $data['image'] = $currUser['image'];
      $data['id'] = $currUser['id'];
      $data['title'] = htmlspecialchars($currUser['username'] ?? 'User') . "'s Questions";
    }
    $data['histories'] = $data['user']['histories'];
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

    $processList = function (array $rawListFromProfileUser) use ($loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) {
      return collect($rawListFromProfileUser)->map(function ($item) use ($loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) {
        if (!is_array($item) || !isset($item['email'])) {
          Log::warning("Invalid item structure in connection list for profile.", ['item_structure' => $item]);
          return null;
        }
        $status = $loggedInUserEmail ? $this->userController->determineFollowStatus($item, $loggedInUserFollowingArray, $loggedInUserFollowersArray, $loggedInUserEmail) : ['follow_status' => 'not_logged_in', 'is_mutual' => false];
        $item['follow_status'] = $status['follow_status'];
        $item['is_mutual'] = $status['is_mutual'];
        return $item;
      })->filter()->values(); //filter() untuk hapus null, values() untuk re-index collection
    };

    // Pastikan $profileUser['followers'] dan $profileUser['following'] adalah array
    $rawFollowers = isset($profileUser['followers']) && is_array($profileUser['followers']) ? $profileUser['followers'] : [];
    $rawFollowing = isset($profileUser['following']) && is_array($profileUser['following']) ? $profileUser['following'] : [];

    $followersList = $processList($rawFollowers);
    $followingList = $processList($rawFollowing);

    //tambahin status relasi untuk pengguna profil utama kalo bukan profil sendiri
    if ($loggedInUser && !$isOwnProfile) {
      $relationToProfileUser = $this->userController->determineFollowStatus(
        $profileUser,
        $loggedInUserFollowingArray,
        $loggedInUserFollowersArray,
        $loggedInUserEmail
      );
      $profileUser['current_user_relation'] = $relationToProfileUser;
    }

    $data = [
      'title' => ($profileUser['username'] ?? 'User') . ($initialTabType === 'followers' ? ' - Followers' : ' - Following'),
      'id' => $profileUser['id'],
      'username' => $profileUser['username'],
      'image' => $profileUser['image'],
      'profileUser' => $profileUser,
      'followersList' => $followersList,
      'followingList' => $followingList,
      'loggedInUser' => $loggedInUser,
      'isOwnProfile' => $isOwnProfile,
      'activeTab' => $initialTabType,
      'histories' => $loggedInUser['histories']
    ];
    // dd($data);

    return view('connections', $data);
  }


  public function popular(Request $request)
  {
    $email = session('email');
    $user = $email ? $this->userController->getUserByEmail($email) : null;

    $questionsPaginator = $this->questionController->getAllQuestionsByPopularity($request);
    $tags = $this->tagController->getAllTags();

    // Handle AJAX requests
    if ($request->ajax() || $request->wantsJson()) {
      $activeFilters = [
        'currentFilterTag' => $request->input('filter_tag'),
        'currentSearchTerm' => $request->input('search_term'),
      ];
      return response()->json([
        'html' => view('partials.questions_list_content', array_merge(['questions' => $questionsPaginator], $activeFilters))->render(),
        'pagination_html' => $questionsPaginator->appends($request->query())->links()->toHtml(),
        'total_results' => $questionsPaginator->total(),
        'current_page' => $questionsPaginator->currentPage(),
      ]);
    }

    // Untuk Initial Page Load (Non-AJAX)
    $data = [
      'username' => $user['username'],
      'id' => $user['id'],
      'image' => $user['image'],
      'title' => 'Popular Questions',
      'questions' => $questionsPaginator,
      'tags' => $tags,
      'initialSortBy' => $request->input('sort_by', 'latest'),
      'initialFilterTag' => $request->input('filter_tag', null),
      'initialSearchTerm' => $request->input('search_term', null),
      'initialPage' => $request->input('page', 1),
      'histories' => $user['histories'],
    ];

    return view('popular', $data);
  }

  // hrse terima param id question, nih aku cuman mau coba view
  public function viewAnswers($questionId)
  {
    $data['question'] = $this->questionController->getQuestionDetails($questionId);
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['username'] = $currUser['username'];
    $data['image'] = $currUser['image'];
    $data['title'] = 'View Answers';
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    $data['id'] = $currUser['id'];
    // dd($data);
    Log::info($data['question']);

    $data['histories'] = $currUser['histories'];
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
    $data['username'] = $currUser['username'];
    $data['id'] = $currUser['id'];
    $data['image'] = $currUser['image'];
    $data['recommended'] = $user['recommended'];

    $data['histories'] = $currUser['histories'];
    return view('viewAllUsers', $data);
  }

  public function viewTags()
  {
    $tags = $this->tagController->getAllTags();
    $data['tags'] = $tags;
    $data['title'] = 'View Tags';
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['username'] = $currUser['username'];
    $data['id'] = $currUser['id'];
    $data['image'] = $currUser['image'];
    // dd($data);
    $data['histories'] = $currUser['histories'];

    return view('viewTags', $data);
  }

  public function leaderboard()
  {
    $data['title'] = 'Leaderboard';
    $data['tags'] = $this->tagController->getTagOnly();
    $data['mostViewed'] = $this->userController->getMostViewedUser();
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['username'] = $currUser['username'];
    $data['id'] = $currUser['id'];
    $data['image'] = $currUser['image'];
    $data['histories'] = $currUser['histories'];

    return view('leaderboard', $data);
  }

  public function savedQuestion()
  {
    $data = $this->userController->getSavedQuestion();
    return view('savedQuestions', $data);
  }
}
