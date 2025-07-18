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
  public $reportController;
  public function __construct(UserController $userController, AnswerController $answerController, QuestionController $questionController, TagController $tagController, ReportController $reportController)
  {
    $this->userController = $userController;
    $this->answerController = $answerController;
    $this->questionController = $questionController;
    $this->tagController = $tagController;
    $this->reportController = $reportController;
  }

  public function askPage(Request $request, $questionId = null) 
  {
    $viewData = [];
    $currUser = $this->userController->getUserByEmail($request->session()->get('email'));
     if (!$currUser) {
        return redirect()->route('home')->with('Error', 'User session not valid.');
    }

   $viewData = [
        'title' => 'Ask a Question',
        'image' => $currUser['image'] ?? null,
        'username' => $currUser['username'] ?? null,
        'id' => $currUser['id'],
        'histories' => $currUser['histories'],
        'allTags' => $this->tagController->getAllTags(),
        'questionToEdit' => null, // default nya ask
        'selectedTagIdsOnLoad' => [],
    ];

    if ($questionId !== null) {
       $questionDetails = $this->questionController->getQuestionDetails($questionId);

        if (!$questionDetails) {
            return redirect()->route('home')->with('Error', 'The question you are trying to edit was not found.');
        }

        if ($currUser['id'] !== $questionDetails['user_id']) {
            return redirect()->route('home')->with('Error', 'You are not authorized to edit this question.');
        }

        $viewData['title'] = 'Edit Question';
        $viewData['questionToEdit'] = $questionDetails;
        $viewData['selectedTagIdsOnLoad'] = array_column($questionDetails['group_question'], 'tag_id');
    }

    return view('ask', $viewData);

  }

  // coba gabung profile + otherprofile
  public function viewUser(string $email)
  {
    $userViewed = $this->userController->getUserFollowers($email);
    if (session('email') != $email) {
      $currUser = $this->userController->getUserByEmail(session('email'));
      $data['username'] = $currUser['username'];
      $data['image'] = $currUser['image'];
      $data['id'] = $currUser['id'];
      $data['histories'] = $currUser['histories'];

    } else {
      $data['username'] = $userViewed['user']['username'];
      $data['image'] = $userViewed['user']['image'];
      $data['id'] = $userViewed['user']['id'];
      $data['histories'] = $userViewed['user']['histories'];
    }

    $data['title'] = 'PROFILE | ' . $userViewed['user']['username'];
    $data['userViewed'] = $userViewed['user'];

    $emailSession = session('email');
    $data['isOwnProfile'] = $email === $emailSession ? true : false;

    $data['userRelation'] = 0; // tak ada relasi (asing bjir)
    if (session('email') != $email) {
      foreach ($userViewed['user']['followers'] as $follower) {
        if ($follower['id'] == $currUser['id']) {
          $data['userRelation'] = 1; // aku follow dirinya -> btn bertuliskan following
          break;
        }
      }
      // dd($userViewed['user']);
      if ($data['userRelation'] === 0) { // jika habis di cek, trnyt ak ga folo dia, cek apakah dia folo ak -> btn bertuliskan follow back
        foreach ($userViewed['user']['following'] as $following) {
          if ($following['id'] == $currUser['id']) {
            $data['userRelation'] = 2;
            break;
          }
        }
      }
    }
    return view('profile', $data);
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
    $data['username'] = $data['user']['username'];
    $data['image'] = $data['user']['image'];
    $data['id'] = $data['user']['id'];
    $data['title'] = htmlspecialchars($data['user']['username'] ?? 'User') . "'s Questions";
    $currUser = $this->userController->getBasicUserByEmail(session('email'));
    if (session('email') != $data['user']['email']) {
      $data['viewer'] = $currUser;
      $data['username'] = $currUser['username'];
      $data['image'] = $currUser['image'];
      $data['id'] = $currUser['id'];
      $data['title'] = htmlspecialchars($currUser['username'] ?? 'User') . "'s Questions";
    }
    $data['histories'] = $currUser['histories'];
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
      'username' => $loggedInUser['username'],
      'image' => $loggedInUser['image'],
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


  public function home(Request $request)
  {
    $email = session('email');
    $user = $email ? $this->userController->getUserByEmail($email) : null;

    $questionsPaginator = $this->questionController->getAllQuestionsByPopularity($request);
    $tags = $this->tagController->getAllTags();
    $reportReasons = $this->reportController->getReportReasons();

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
      'title' => 'Home',
      'questions' => $questionsPaginator,
      'tags' => $tags,
      'reportReasons' => $reportReasons,
      'initialSortBy' => $request->input('sort_by', 'latest'),
      'initialFilterTag' => $request->input('filter_tag', null),
      'initialSearchTerm' => $request->input('search_term', null),
      'initialPage' => $request->input('page', 1),
      'histories' => $user['histories'],
    ];
    // dd($data);
    return view('home', $data);
  }

  // hrse terima param id question, nih aku cuman mau coba view
  public function viewAnswers($questionId)
  {
    $data['question'] = $this->questionController->getQuestionDetails($questionId);
    // dd($data['question']);
    $reportReasons = $this->reportController->getReportReasons();
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['username'] = $currUser['username'];
    $data['image'] = $currUser['image'];
    $data['title'] = 'View Answers';
    // $currUser = $this->userController->getUserByEmail(session('email'));
    // $data['image'] = $currUser['image'];
    $data['id'] = $currUser['id'];
    $data['email'] = $currUser['email'];
    // dd($data);

    $data['histories'] = $currUser['histories'];
    $data['reportReasons'] = $reportReasons;
    // dd($data['reportReasons']);
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
    // dd($data);
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

  public function faq()
  {
      $data['title'] = 'Frequently Asked Questions';

      // Get the logged-in user to pass all the necessary navbar data
      $currUser = $this->userController->getUserByEmail(session('email'));
      $data['username'] = $currUser['username'];
      $data['image'] = $currUser['image'];
      $data['id'] = $currUser['id'];
      $data['histories'] = $currUser['histories'];

      return view('faq', $data);
  }
}