<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home', ['title' => 'coba']);
// });
// Route::get('/home', [UserController::class, 'home'])->name('home');

Route::get('/loginOrRegist', [AuthController::class, 'loginOrRegist'])->name('loginOrRegist');
Route::post('/manualLogin', [AuthController::class, 'manualLogin'])->name('manualLogin');
Route::post('/submitRegister', [AuthController::class, 'submitRegister'])->name('submitRegister');
Route::get('/email/verify', [AuthController::class, 'verifyEmail'])->name('email.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');

Route::get('/auth', [AuthController::class, 'googleAuth'])->name('auth');
Route::get('/process/login', [AuthController::class, 'processLogin'])->name('processLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['isLogin'])->group(function () {
    Route::get('/popular', [MainController::class, 'popular'])->name('popular');
    Route::get('/ask', [MainController::class, 'askPage'])->name('askPage');
    Route::get('/viewUser/{email}', [MainController::class, 'viewOther'])->name('viewOthers');

    // Route::get('/{id}', [UserController::class, 'viewOther']);
    Route::post('/follow', [UserController::class, 'nembakFollow'])->name('nembakFollow');
    Route::get('/', [MainController::class, 'home'])->name('home');

    Route::get('/myProfile', [MainController::class, 'seeProfile'])->name('seeProfile');
    // routes/web.php

    Route::get('/user/{id}/questions', [MainController::class, 'userQuestions'])->name('user.questions.list');
    Route::get('/user/{email}/connections', [MainController::class, 'userConnections'])->name('user.connections');
    // Route untuk aksi follow/unfollow (via POST untuk AJAX)
    Route::post('/user/toggle-follow', [MainController::class, 'toggleFollow'])->name('user.toggleFollow');
    Route::get('/editProfile', [MainController::class, 'editProfile'])->name('editProfile');
    Route::post('/editProfile', [UserController::class, 'editProfilePost'])->name('editProfile.post');
    Route::get('/user/recommendation', [UserController::class, 'recommendation'])->name('recommendation');

    Route::post('/question/vote', [QuestionController::class, 'vote'])->name('question.vote');
    Route::post('/answer/vote', [AnswerController::class, 'vote'])->name('answer.vote');

    // view questions
    Route::get('/viewUsers', [MainController::class, 'viewAllUsers'])->name('viewAllUsers');
    Route::get('/viewAnswers/{questionId}', [MainController::class, 'viewAnswers'])->name('user.viewQuestions');
    Route::get('/viewTags', [MainController::class, 'viewTags'])->name('viewAllTags');
    Route::get('/viewUser/{email}', [MainController::class, 'viewUser'])->name('viewUser');
    Route::post('/submitAnswer/{questionId}', [AnswerController::class, 'submitAnswer'])->name('submitAnswer');
    Route::post('/addQuestion', [QuestionController::class, 'addQuestion'])->name('addQuestion');
    Route::post('/submit/question/comment/{questionId}', [QuestionController::class, 'submitQuestionComment'])->name('question.comment.submit');
    Route::get('/leaderboard', [MainController::class, 'leaderboard'])->name('user.leaderboard');
    Route::get('/getTagLeaderboard/{id}', [TagController::class, 'getTagLeaderboard'])->name('tag.leaderboard');
    Route::get('/getMostViewed', [UserController::class, 'getMostViewed'])->name('user.mostViewed');
    Route::post('/saveQuestion', [QuestionController::class, 'saveQuestion'])->name('saveQuestion');
    Route::post('/unsaveQuestion', [QuestionController::class, 'unsaveQuestion'])->name('unsaveQuestion');
});
