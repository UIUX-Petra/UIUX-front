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
Route::get('/auth/verify-notice', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
Route::get('/handle-pending-verification', [AuthController::class, 'handlePendingVerification'])->name('verify.pending');

Route::get('/auth', [AuthController::class, 'googleAuth'])->name('auth');
Route::get('/process/login', [AuthController::class, 'processLogin'])->name('processLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['isLogin'])->group(function () {
    Route::get('/popular', [MainController::class, 'popular'])->name('popular');
    Route::get('/ask/{questionId?}', [MainController::class, 'askPage'])->name('askPage');
    Route::post('/questions/{id}/save-edit', [QuestionController::class, 'saveEditedQuestion'])->name('question.saveEdit');
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


    Route::get('/questions/{id}/edit', [QuestionController::class, 'showEditQuestionPage'])->name('editQuestionPage');
    Route::post('/questions/{id}', [QuestionController::class, 'updateQuestion'])->name('updateQuestion');
    Route::post('/questions/{id}/delete', [QuestionController::class, 'deleteQuestion'])->name('deleteQuestion');

    Route::get('/user/{userId}/answers', [AnswerController::class, 'viewUserAnswers'])->name('user.answers.index');
    Route::get('/answers/{answerId}/edit', [AnswerController::class, 'editAnswerForm'])->name('user.answers.edit');
    Route::post('/answers/{answerId}/update', [AnswerController::class, 'updateAnswer'])->name('user.answers.update');
    Route::delete('/answers/{answerId}', [AnswerController::class, 'deleteAnswer'])->name('answer.delete');
    Route::post('/user/history/{searchedId}', [UserController::class, 'addHistory'])->name('nembakHistory');
    Route::post('/history/delete', [UserController::class, 'deleteHistory'])->name('deleteHistory');
});
