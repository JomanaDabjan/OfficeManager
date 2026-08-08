<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GUEST AUTHENTICATION ROUTES (Accessible only to unauthenticated visitors)
|--------------------------------------------------------------------------
| These routes handle registration, login, and password recovery workflows
| for users who are not currently logged into the application.
*/

Route::middleware('guest')->group(function () {

    // Display the user registration view
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    // Handle incoming user registration submissions
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Display the secure login view (connected to our updated AuthenticatedSessionController)
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Process incoming login requests (triggers our hardened LoginRequest & Session Controller)
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Display the request password reset link form
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Send the password reset email link to the user
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Display the new password reset form using the secure token
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    // Handle saving the newly updated password to the database
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (Accessible only to logged-in users)
|--------------------------------------------------------------------------
| These routes manage email verification, password confirmation,
| password updates, and secure user logouts.
*/
Route::middleware('auth')->group(function () {

    // Display email verification notice prompt
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Handle email verification link clicks with signature and rate limiting
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Resend the email verification notification
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Display password confirmation form for sensitive actions
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    // Process password confirmation submission
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Handle updating the user's active password
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Handle secure logout (destroys session and clears tokens)
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

// This route is commented out because it is not currently needed,
// but it can be enabled if email verification notice functionality is required in the future.

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');
