<?php

use App\Http\Controllers\PasskeyController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    // Route pendaftaran passkey
    Route::post('/passkeys', [PasskeyController::class, 'store'])->name('passkeys.store');
    Route::delete('/passkeys/{id}', [PasskeyController::class, 'destroy'])->name('passkeys.destroy');
});

Route::get('/admin-dashboard', function(){
    return view('admin.admin-dashboard');
})->middleware('admin');

Route::get('/gate-admin-dashboard', function(){
    Gate::authorize('is-admin');
    return view('admin.gate-dashboard');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('posts', PostController::class);

Route::passkeys();

// Route untuk manajemen Passkey di halaman Profil (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/passkeys/register-options', [PasskeyController::class, 'registerOptions'])
        ->name('passkeys.register-options');
    Route::post('/passkeys', [PasskeyController::class, 'store'])
        ->name('passkeys.store');
    Route::delete('/passkeys/{id}', [PasskeyController::class, 'destroy'])
        ->name('passkeys.destroy');
});

Route::post('/login/passkey-success', function (Request $request) {
    // Cari user tujuan (atau sesuaikan dengan email akunmu)
    $user = User::where('email', 'wilbert@gmail.com')->first();

    if (! $user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User tidak ditemukan.'
        ], 404);
    }

    // Force login user & regenerate session Laravel
    Auth::login($user);
    $request->session()->regenerate();

    return response()->json([
        'status' => 'success',
        'redirect' => route('posts.index')
    ]);
})->name('login.passkey-success');


require __DIR__.'/auth.php';
