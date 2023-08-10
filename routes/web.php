<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\SyndicateController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

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

Route::get('/login', function () {
    return view('login');
})->middleware('guest')->name('login');

Route::get('/register', function () {
    return view('register');
})->middleware('guest')->name('register');

Route::controller(AuthenticationController::class)->group(function() {
    Route::post('/login', 'login')->middleware('guest');
    Route::post('/register', 'register')->middleware('guest');
    Route::post('/logout', 'logout')->middleware('auth');
    Route::get('/logout', 'logout')->middleware('auth');
});

Route::controller(SyndicateController::class)->group(function () {
    Route::get('/syndicates', 'all');
    Route::get('/syndicate', 'view');
    Route::get('/syndicate/invite', 'viewInvite')->name('invite');

    Route::post('/syndicate/edit', 'edit');
    Route::post('/syndicate/invite', 'invite');
    Route::post('/syndicate/create', 'create');
    Route::post('/syndicate/delete', 'delete');
    Route::post('/syndicate/user/remove', 'removeUser');
    Route::post('/syndicate/invite/accept', 'acceptInvite');
});

Route::controller(PasswordController::class)->group(function () {
    Route::post('/update-password', 'updatePassword')->middleware('auth');

    Route::get('/forgot-password', 'viewForgotPassword')->middleware('guest')->name('password.request');
    Route::post('/forgot-password', 'forgotPassword')->middleware('guest')->name('password.email');

    Route::get('/reset-password/{token}', 'viewResetPassword')->middleware('guest')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->middleware('guest')->name('password.update');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/profile', 'view');

    Route::post('/user/update', 'update');
    Route::post('/user/notification/delete', 'deleteNotification');
});

Route::controller(TicketController::class)->group(function () {
    Route::post('/ticket/create', 'create');
    Route::post('/ticket/delete', 'delete');
});

Route::get('/', function () {
    return view('index');
})->middleware('auth');