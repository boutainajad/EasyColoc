<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AdminController; // ← ZDAT HADA
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ← BDLT HADA
Route::get('/dashboard', function () {
    if (auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('colocations.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Colocations
    Route::get('/colocations', [ColocationController::class, 'index'])->name('colocations.index');
    Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::get('/colocations/{colocation}/edit', [ColocationController::class, 'edit'])->name('colocations.edit');
    Route::put('/colocations/{colocation}', [ColocationController::class, 'update'])->name('colocations.update');
    Route::post('/colocations/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('colocations.cancel');
    Route::post('/colocations/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
    Route::post('/colocations/{colocation}/kick/{user}', [ColocationController::class, 'kick'])->name('colocations.kick');

    // Invitations
    Route::post('/colocations/{colocation}/invite', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invitations/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::get('/invitations/{token}/refuse', [InvitationController::class, 'refuse'])->name('invitations.refuse');

    // Categories
    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Expenses
    Route::post('/colocations/{colocation}/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/users/{user}/ban', [AdminController::class, 'ban'])->name('ban');
        Route::post('/users/{user}/unban', [AdminController::class, 'unban'])->name('unban');
    });

});

require __DIR__.'/auth.php';