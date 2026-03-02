<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LetterController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard redirect sesuai role
Route::get('/dashboard', function () {
    $user = Auth::user();

    return match ($user->role) {
        'staff'   => redirect('/dashboard-staff'),
        'kasubag' => redirect('/dashboard-kasubag'),
        'kabag1'  => redirect('/dashboard-kabag1'),
        'kabag2'  => redirect('/dashboard-kabag2'),
        default   => abort(403, 'Unauthorized'),
    };
})->middleware(['auth'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard per role
Route::get('/dashboard-staff', fn() => view('dashboard.staff'))->middleware(['auth', 'role:staff']);
Route::get('/dashboard-kasubag', fn() => view('dashboard.kasubag'))->middleware(['auth', 'role:kasubag']);
Route::get('/dashboard-kabag1', fn() => view('dashboard.kabag1'))->middleware(['auth', 'role:kabag1']);
Route::get('/dashboard-kabag2', fn() => view('dashboard.kabag2'))->middleware(['auth', 'role:kabag2']);

// ✅ Routes untuk surat
Route::middleware(['auth'])->group(function () {
    Route::get('/letters', [LetterController::class, 'index'])->name('letters.index');
    Route::post('/letters', [LetterController::class, 'store'])->name('letters.store');
    Route::post('/letters/{letter}/status', [LetterController::class, 'updateStatus'])->name('letters.updateStatus');
});

// ✏️ Edit & Update Surat (khusus staff)
Route::get('/letters/{letter}/edit', [LetterController::class, 'edit'])->middleware(['auth', 'role:staff'])->name('letters.edit');
Route::put('/letters/{letter}', [LetterController::class, 'update'])->middleware(['auth', 'role:staff'])->name('letters.update');

require __DIR__.'/auth.php';