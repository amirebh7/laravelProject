<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Route::get('/', function () {
    auth()->user()->activeCode()->create([
        'code'=>111111,
        'expired_at'=>now()->addMinutes(10)
    ]);
    return view('welcome');
});

Auth::routes(['verify' => true]);
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/auth/google' ,[GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback' ,[GoogleAuthController::class, 'callback']);

Route::get('/secret' , function() {
    return 'secret';
})->middleware(['auth' , 'password.confirm']);

Route::middleware('auth')->group(function() {
    Route::get('profile' , [ProfileController::class, 'index'])->name('profile');
    Route::get('profile/twofactor' , [ProfileController::class, 'manageTwoFactor'])->name('profile.2fa.manage');
    Route::post('profile/twofactor' , [ProfileController::class, 'postManageTwoFactor']);

    Route::get('profile/twofactor/phone' , [ProfileController::class, 'getPhoneVerify'])->name('profile.2fa.phone');
    Route::post('profile/twofactor/phone' , [ProfileController::class, 'postPhoneVerify']);
});

