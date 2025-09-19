<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardKetuaController;
use App\Http\Controllers\DashboardOperatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;

// -------------------------------------------------------------------
// Halaman Home
// -------------------------------------------------------------------
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// -------------------------------------------------------------------
// Auth & Verifikasi
// -------------------------------------------------------------------
require __DIR__ . '/auth.php';

Route::get('/check-auth', function () {
    if (Auth::check()) {
        return 'User is authenticated: ' . Auth::user()->name;
    } else {
        return 'User is not authenticated.';
    }
})->name('check-auth');

// -------------------------------------------------------------------
// Halaman error jika unauthorized
// -------------------------------------------------------------------
Route::get('/notfound', function () {
    return view('error.unauthorized');
})->name('error.unauthorized');

// -------------------------------------------------------------------
// Lolos 'auth' dan 'verified'
// -------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware('role:1,2,3')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/dashboardKetua', [DashboardKetuaController::class, 'index'])
            ->name('dashboard.ketua');

        Route::get('/dashboardOperator', [DashboardOperatorController::class, 'index'])
            ->name('dashboard.operator');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/edit-profile', [ProfileController::class, 'setPhotoProfile'])->name('edit.profile');
        Route::put('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');

        Route::name('form.')->prefix('/form')->group(function () {
            Route::get('/', [FormController::class, 'index'])->name('index');
            Route::get('/create', [FormController::class, 'create'])->name('create');
            Route::post('/', [FormController::class, 'store'])->name('store');

            Route::get('/list', [FormController::class, 'list'])->name('list');

            Route::get('/{form}/edit', [FormController::class, 'edit'])->name('edit');
            Route::put('/{form}', [FormController::class, 'update'])->name('update');
            Route::delete('/{form}', [FormController::class, 'destroy'])->name('destroy');

            Route::post('/{id}/answer', [FormController::class, 'storeAnswer'])->name('storeAnswer');

            Route::get('/{form}', [FormController::class, 'show'])->name('show');
        });
    });
});
