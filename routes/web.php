<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\Admin\VoteLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    /*
    |--------------------------------------------------------------------------
    | Vote Routes (Mahasiswa only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
        Route::get('/vote/{event}', [VoteController::class, 'show'])->name('vote.show');
        Route::post('/vote/{event}', [VoteController::class, 'store'])->name('vote.store');
        Route::get('/vote/{event}/candidate/{candidate}', [VoteController::class, 'candidate'])->name('vote.candidate');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('master-data')->name('masterData.')->group(function () {
            Route::get('/user', [UserController::class, 'index'])->name('user.index');

            Route::get('/event', [EventController::class, 'index'])->name('event.index');
            Route::post('/event', [EventController::class, 'store'])->name('event.store');
            Route::put('/event/{id}', [EventController::class, 'update'])->name('event.update');
            Route::delete('/event/{id}', [EventController::class, 'destroy'])->name('event.destroy');

            Route::get('/candidate', [CandidateController::class, 'index'])->name('candidate.index');
            Route::post('/candidate', [CandidateController::class, 'store'])->name('candidate.store');
            Route::put('/candidate/{id}', [CandidateController::class, 'update'])->name('candidate.update');
            Route::delete('/candidate/{id}', [CandidateController::class, 'destroy'])->name('candidate.destroy');
        });

        Route::get('/vote-log', [VoteLogController::class, 'index'])->name('voteLog.index');
    });
});
