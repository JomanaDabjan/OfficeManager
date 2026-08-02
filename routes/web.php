<?php

use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\admin\TaskController;
use App\Http\Controllers\admin\DashController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;

/*Route::get('/', function () {
    return view('welcome');
});*/

/* Start Website Routes */

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('dash', DashController::class);
    Route::resource('project', ProjectController::class);

    // Custom Routes for Task Accept and Reject
    Route::patch('task/{task}/accept', [TaskController::class, 'accept'])->name('task.accept');
    Route::patch('task/{task}/reject', [TaskController::class, 'reject'])->name('task.reject');

    Route::resource('task', TaskController::class);
    Route::resource('user', UserController::class);
});

/* End Website Routes */


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';