<?php

use App\Http\Controllers\AdminController;
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
    Route::get('/getSavedQuestion', [MainController::class, 'savedQuestion'])->name('savedQuestions');
    Route::post('/saveQuestion', [QuestionController::class, 'saveQuestion'])->name('saveQuestion');
    Route::post('/unsaveQuestion', [QuestionController::class, 'unsaveQuestion'])->name('unsaveQuestion');
});


// ADMIN
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/users/index', [AdminController::class, 'userRegistration'])->name('admin.users.index');
Route::get('/admin/users/activity', [AdminController::class, 'userActivity'])->name('admin.users.activity');
Route::get('/admin/moderation/dashboard', [AdminController::class, 'moderationDashboard'])->name('admin.moderation.dashboard');
Route::get('/admin/moderation/questions', [AdminController::class, 'moderationQuestions'])->name('admin.moderation.questions');
Route::get('/admin/moderation/contents', [AdminController::class, 'moderationContents'])->name('admin.moderation.contents');
Route::get('/admin/moderation/comments', [AdminController::class, 'moderationComments'])->name('admin.moderation.comments');
Route::get('/admin/manage/content', [AdminController::class, 'manageContent'])->name('admin.content.manage');
Route::get('/admin/subjects/index', [AdminController::class, 'subjects'])->name('admin.subjects.index');
Route::get('/admin/moderation/log', [AdminController::class, 'moderationLog'])->name('admin.moderation.log');
Route::get('/admin/support/index', [AdminController::class, 'support'])->name('admin.support.index');
Route::get('/admin/platform/annoucement', [AdminController::class, 'announcement'])->name('admin.platform.announcements');
Route::get('/admin/platform/role', [AdminController::class, 'role'])->name('admin.platform.roles');