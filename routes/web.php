<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgressBoardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::middleware(RedirectIfAuthenticated::class)->group(function () {
	Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
	Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');
	Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
	Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:registration')->name('register.store');
});

Route::middleware(Authenticate::class)->group(function () {
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
	Route::resource('tasks', TaskController::class)->except(['show'])->middleware('throttle:task-mutations');
	Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
	Route::get('/tasks/{task}/attachment', [TaskController::class, 'preview'])->name('tasks.attachment');
	Route::get('/tasks/{task}/attachment/download', [TaskController::class, 'download'])->name('tasks.attachment.download');
	Route::get('/progress-board', [ProgressBoardController::class, 'index'])->name('progress-board');
	Route::patch('/tasks/{task}/status', [ProgressBoardController::class, 'updateStatus'])->middleware('throttle:task-mutations')->name('tasks.status');
	Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
	Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
	Route::post('/subjects', [SubjectController::class, 'store'])->middleware('throttle:task-mutations')->name('subjects.store');
});

Route::view('/', 'landing')->name('landing');
