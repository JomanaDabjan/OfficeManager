<?php

use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\admin\TaskController;
use App\Http\Controllers\admin\DashController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
//use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| ROOT WEB ROUTE (HOME PAGE REDIRECTION)
|--------------------------------------------------------------------------
| This route intercepts requests to the root URL (/) and immediately
| returns the Now UI login blade view, making the authentication page
| the primary landing page of the application.
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dash.index');
    }
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD & MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| These routes are protected by the 'auth' middleware and prefixed with
| 'admin'. They manage resources such as dashboard, projects, tasks,
| and users, including custom task moderation actions.
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Resource route for administrative dashboard control
    Route::resource('dash', DashController::class);

    // Resource route for managing company or user projects
    Route::resource('project', ProjectController::class);

    // Custom administrative route to accept specific task requests
    Route::patch('task/{task}/accept', [TaskController::class, 'accept'])->name('task.accept');

    // Custom administrative route to reject specific task requests
    Route::patch('task/{task}/reject', [TaskController::class, 'reject'])->name('task.reject');

    // Resource route for general task CRUD operations
    Route::resource('task', TaskController::class);

    // Resource route for managing system users and roles
    Route::resource('user', UserController::class);
});

/*
|--------------------------------------------------------------------------
| REDIRECT OLD DASHBOARD TO NEW ADMIN DASH
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('admin.dash.index');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| USER PROFILE MANAGEMENT ROUTES
|--------------------------------------------------------------------------
| Protected routes that allow authenticated users to view, edit,
| update, or delete their own profile information.
*/
Route::middleware('auth')->group(function () {
    // Display the profile edit form
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // Handle updating user profile data in the database
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Handle deleting the user's account permanently
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES FILE INCLUSION
|--------------------------------------------------------------------------
| Pulls in all standard Laravel Breeze auth routes (login, register,
| password reset, and secure logout handlers).
*/
require __DIR__ . '/auth.php';
