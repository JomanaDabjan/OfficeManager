<?php

use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\DashController;
use App\Http\Controllers\Admin\ReportController; // Import the Report Controller
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROOT WEB ROUTE (HOME PAGE REDIRECTION)
|--------------------------------------------------------------------------
| This route intercepts requests to the root URL (/) and immediately
| returns the login view if the user is a guest, or redirects to the
| admin dashboard if they are already authenticated.
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
| users, and analytical reports.
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

    /*
    | =====================================================================
    | REPORTS MANAGEMENT ROUTES (RESOURCE + CUSTOM ANALYTICS)
    | =====================================================================
    | These routes handle analytical data and reporting views.
    */

    // ==========================================
    // 1. TASK REPORT & EXPORT ROUTES
    // ==========================================
    Route::get('report/task-report', [ReportController::class, 'taskreport'])->name('report.task-report');
    Route::get('report/task-report/pdf', [ReportController::class, 'exportTasksPdf'])->name('report.task-report.pdf');
    Route::get('report/task-report/excel', [ReportController::class, 'exportTasksExcel'])->name('report.task-report.excel');
    Route::get('report/task-report/print', [ReportController::class, 'printTasksReport'])->name('report.task-report.print');

    // ==========================================
    // 2. PROJECT REPORT & EXPORT ROUTES
    // ==========================================
    Route::get('report/project-report', [ReportController::class, 'projectreport'])->name('report.project-report');
    Route::get('report/project-report/pdf', [ReportController::class, 'exportProjectsPdf'])->name('report.project-report.pdf');
    Route::get('report/project-report/excel', [ReportController::class, 'exportProjectsExcel'])->name('report.project-report.excel');
    Route::get('report/project-report/print', [ReportController::class, 'printProjectsReport'])->name('report.project-report.print');
    
    // ==========================================
    // 3. GENERAL SYSTEM REPORTS & HUB
    // ==========================================
    Route::get('report/system-overview', [ReportController::class, 'systemOverview'])->name('report.overview');

    // Standard resource route for reports hub (index, create, store, show, edit, update, destroy)
    Route::resource('report', ReportController::class);
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