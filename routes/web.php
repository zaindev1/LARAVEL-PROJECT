<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// --- Auth Routes ---

// Login aur Register ka aik hi page hai (Welcome)
Route::get('/', [AuthController::class, 'showAuthPage'])->name('login'); 
Route::get('/register', [AuthController::class, 'showAuthPage'])->name('register');

// Form submissions (Backend logic)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login.Post');
// --- Dashboard Routes ---

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

// --- Extras ---

Route::get('/forgot-password', function() { 
    return "Password reset system baad mein banayenge."; 
})->name('password.request');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::put('/user/update', [AuthController::class, 'update'])->name('user.update');

Route::post('/toggle-status', [AuthController::class, 'toggleStatus'])->name('status.toggle');

Route::get('/dashboard', function () {
    $userCount = \App\Models\User::count(); // Saare users ko count karega
    return view('dashboard', compact('userCount'));
})->middleware('auth')->name('dashboard');