<?php

use Illuminate\Support\Facades\Route;

// Public Controller
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;

// Auth Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;

// Dashboard Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\CommentManagementController;
use App\Http\Controllers\Dashboard\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC PAGES ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// --- AUTHENTICATION ROUTES ---
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/verify', [VerificationController::class, 'verify'])->name('auth.verify');
Route::get('/email/verify', [VerificationController::class, 'showNoticePage'])->name('verification.notice');
Route::post('/email/verify/resend', [VerificationController::class, 'resendVerificationEmail'])->name('verification.resend');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');


// --- DASHBOARD (Protected Area) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Post Management
    Route::resource('posts', PostController::class)->except(['index', 'show']);

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Comment Management
    Route::get('/dashboard/comments', [CommentManagementController::class, 'index'])->name('dashboard.comments.index');
    Route::patch('/dashboard/comments/{comment}/approve', [CommentManagementController::class, 'approve'])->name('dashboard.comments.approve');
    Route::delete('/dashboard/comments/{comment}', [CommentManagementController::class, 'destroy'])->name('dashboard.comments.destroy');

    // Ito ay gagawa ng routes para sa index, create, store, edit, update, destroy
    Route::resource('/dashboard/categories', CategoryController::class)
         ->except(['show']) // Alisin ang 'show' route
         ->names('dashboard.categories'); // Pangalanan natin (e.g., dashboard.categories.index)
});


// --- PUBLIC WILDCARD ROUTE ---
// Dapat laging HULI ito para mauna ang /posts/create
Route::get('/posts/{post}', [HomeController::class, 'show'])->name('posts.show');
Route::get('/comments/verify/{token}', [CommentController::class, 'verify'])->name('comments.verify');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');