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
    $followers = collect($currUser['followers']);
    $countFollowers = count($followers);
    $data['countFollowers'] = $countFollowers;
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    return view('profile', $data);
  }
  public function editProfile()
  {
    $data['title'] = 'Edit Profile';

    $email = session('email');
    $currUser = $this->userController->getUserByEmail($email);
    $data['user'] = $currUser;
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    return view('editProfile', $data);
  }
  public function userQuestions($userId){
    $data['user'] = $this->userController->showUserQuestionsPage($userId);
    $image = $data['user']['image'];
    $data['image'] = $image;
    $data['title'] = 'User Questions';
    // dd($data['user']);
    return view('userQuestions', $data);
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
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    // dd($data);
    return view('popular', $data);
  }
  public function viewUser(string $email)
  {
    $data = $this->userController->getUserFollowers($email);
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    $data['title'] = 'PROFILE | ' . $data['user']['username'];
    $currUser = $this->userController->getUserByEmail(session('email'));
    $data['image'] = $currUser['image'];
    return view('otherProfiles', $data);
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
