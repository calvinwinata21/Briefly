<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BriefController;
use App\Http\Controllers\DesignerReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.guidelines'),
            'designer' => redirect()->route('designer.dashboard'),
            default => redirect()->route('requester.create'),
        };
    }

    return redirect()->route('login');
});

// Authentication & Quick Switcher
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/switch-role', [AuthController::class, 'switchRole'])->name('switch.role');

// Requester Group (Panitia / Member)
Route::middleware(['auth', 'role:requester,designer,admin'])->prefix('requester')->name('requester.')->group(function () {
    Route::get('/create', [BriefController::class, 'create'])->name('create');
    Route::post('/briefs', [BriefController::class, 'store'])->name('store');
    Route::get('/history', [BriefController::class, 'index'])->name('history');
    Route::get('/briefs/{brief}', [BriefController::class, 'show'])->name('show');
});

// Designer Group (Lead Designer Specialist / HITL Review)
Route::middleware(['auth', 'role:designer,admin'])->prefix('designer')->name('designer.')->group(function () {
    Route::get('/dashboard', [DesignerReviewController::class, 'index'])->name('dashboard');
    Route::get('/drafts/{draft}', [DesignerReviewController::class, 'show'])->name('detail');
    Route::post('/drafts/{draft}/approve', [DesignerReviewController::class, 'approve'])->name('approve');
    Route::post('/drafts/{draft}/reject', [DesignerReviewController::class, 'reject'])->name('reject');
    Route::post('/drafts/{draft}/update', [DesignerReviewController::class, 'update'])->name('update');
    Route::get('/drafts/{draft}/export/{format}', [DesignerReviewController::class, 'export'])->name('export');
});

// Administrator Group (Head of Media Admin / RAG & Config)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/guidelines', [AdminController::class, 'guidelines'])->name('guidelines');
    Route::post('/guidelines', [AdminController::class, 'updateGuidelines'])->name('guidelines.update');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
});
