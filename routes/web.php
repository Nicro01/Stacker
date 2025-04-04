<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Passwords\Confirm;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Verify;
use App\Livewire\Pages\Home;
use App\Livewire\Panel\Home as PanelHome;
use App\Livewire\Panel\Configs as PanelConfigs;
use App\Livewire\Panel\Profile;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', Home::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)
        ->name('login');

    Route::get('register', Register::class)
        ->name('register');
});

// Route::get('password/reset', Email::class)
//     ->name('password.request');

// Route::get('password/reset/{token}', Reset::class)
//     ->name('password.reset');

// Route::middleware('auth')->group(function () {
//     Route::get('email/verify', Verify::class)
//         ->middleware('throttle:6,1')
//         ->name('verification.notice');

//     Route::get('password/confirm', Confirm::class)
//         ->name('password.confirm');
// });

Route::get('/github/redirect', function () {
    return Socialite::driver('github')->scopes(['repo'])->redirect();
});

Route::get('/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    // Salvar token no banco (criptografado)
    auth()->user()->update([
        'github_token' => encrypt($githubUser->token),
        'github_username' => $githubUser->nickname,
        'github_avatar' => $githubUser->avatar,
    ]);

    return redirect('/stacker')->with('status', 'GitHub conectado!');
});

Route::middleware('auth')->group(function () {
    // Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
    //     ->middleware('signed')
    //     ->name('verification.verify');

    Route::prefix('stacker')->group(function () {
        Route::get('/', PanelHome::class)
            ->name('panel.home');

        Route::get('profile', Profile::class)->name('profile');

        Route::get('configs', PanelConfigs::class)
            ->name('panel.configs');
    });

    Route::get('logout', LogoutController::class)
        ->name('logout');
});
