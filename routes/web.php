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

// Route::get('/', [BlogController::class, 'index'])->name('blogs.index');
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

// POSTS
Route::get('/', [PostController::class, 'index'])->name('posts.index'); // Display posts
Route::post('/community', [PostController::class, 'store'])->name('posts.store'); // Store a new post
Route::post('/community/{postId}/like', [PostController::class, 'likePost'])->name('posts.like');
Route::post('/community/{postId}/comment', [PostController::class, 'storeComment'])->name('post.comment.store');
Route::delete('/community/{postId}', [PostController::class, 'deletePost'])->name('post.delete');



// USER CONTROLLERS
// Route::get('/users', [UserController::class, 'index'])->name('users.index');

// BLOG CONTROLLER
/*
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('blogs.show');
*/

/*
Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create'); // Route for creating a new blog
Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store'); // Route for storing the new blog
 // Route for showing a specific blog
Route::get('/blogs/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
Route::post('/blogs/{id}', [BlogController::class, 'update'])->name('blogs.update');
Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy'); // Route for deleting a blog
Route::patch('/blogs/{id}/approve', [BlogController::class, 'approve'])->name('blogs.approve');
*/

