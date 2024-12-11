<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeekerController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DgroupController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('blogs.show');
Route::post('/blogs/{blogId}/comment', [BlogController::class, 'writeComment'])->name('blogs.writeComment');

// SUBSCRIBER CONTROLLER 
Route::get('/subscriber/signup', [SubscriberController::class, 'showSignupForm'])->name('subscribers.signup');
Route::post('/subscriber/signup', [SubscriberController::class, 'signup'])->name('subscribers.signup.submit');

/*
Route::get('/seekers', [SeekerController::class, 'index'])->name('seekers.index');
Route::get('/seekers/signup', [SeekerController::class, 'showSignupForm'])->name('seekers.signup');
Route::post('/seekers/signup', [SeekerController::class, 'signup'])->name('seekers.signup.submit');
Route::get('seekers/{id}', [SeekerController::class, 'show'])->name('seekers.view');
Route::post('/seekers/send-email', [SeekerController::class, 'sendSeekerEmail'])->name('seekers.sendEmail');
Route::post('/seekers/{id}/update-missionary', [SeekerController::class, 'updateMissionary'])->name('seekers.updateMissionary');
*/


// AUTH CONTOLLER 

Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// USER CONTROLLER
Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');
Route::post('/profile/update-picture', [UserController::class, 'updateProfilePicture'])->name('profile.updatePicture');

// DASHBOARD
Route::middleware(['auth'])->get('/d-group', [DashboardController::class, 'index'])->name('dashboard');

// DGROUP
Route::post('/dgroup/approve', [DgroupController::class, 'approve'])->name('dgroup.approve');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// USER ROUTES
Route::get('/password/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/password/forgot', [AuthController::class, 'sendPasswordResetLink'])->name('password.send');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');

// POSTS
Route::get('/', [PostController::class, 'index'])->name('posts.index'); // Display posts
Route::post('/community', [PostController::class, 'store'])->name('posts.store'); // Store a new post
Route::post('/community/{postId}/like', [PostController::class, 'likePost'])->name('posts.like');
Route::post('/community/{postId}/comment', [PostController::class, 'storeComment'])->name('post.comment.store');
Route::delete('/community/{commentId}/comment', [PostController::class, 'deleteComment'])->name('post.comment.delete');
Route::delete('/community/{postId}', [PostController::class, 'deletePost'])->name('post.delete');
Route::get('/community/{postId}/likers', [PostController::class, 'getLikers'])->name('post.likers');
Route::post('/community/{commentId}/replies', [PostController::class, 'storeReply'])->name('comments.replies.store');
Route::get('/community/edit/{postId}', [PostController::class, 'editPost'])->name('post.edit');
Route::post('/community/update/{postId}', [PostController::class, 'updatePost'])->name('post.update');
Route::get('/community/profile/{userId}', [PostController::class, 'viewProfile'])->name('profile.view');
Route::post('/community/profile/update-banner', [UserController::class, 'updateBanner'])->name('profile.updateBanner');


