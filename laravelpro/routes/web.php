<?php

use App\Http\Controllers\Auth\AuthTokenController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Profile\IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Profile\TwoFactorAuthController;
use App\Http\Controllers\Profile\TokenAuthController;
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

    return view('welcome');
});

Auth::routes(['verify' => true]);
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/auth/google' ,[GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback' ,[GoogleAuthController::class, 'callback']);

Route::get('/auth/token' ,[AuthTokenController::class, 'getToken'])->name('2fa.token');
Route::post('/auth/token' ,[AuthTokenController::class, 'postToken']);

Route::get('/secret' , function() {
    return 'secret';
})->middleware(['auth' , 'password.confirm']);

Route::middleware('auth')->group(function() {
    Route::get('profile' , [IndexController::class, 'index'])->name('profile');
    Route::get('profile/twofactor' , [TwoFactorAuthController::class, 'manageTwoFactor'])->name('profile.2fa.manage');
    Route::post('profile/twofactor' , [TwoFactorAuthController::class, 'postManageTwoFactor']);

    Route::get('profile/twofactor/phone' , [TokenAuthController::class, 'getPhoneVerify'])->name('profile.2fa.phone');
    Route::post('profile/twofactor/phone' , [TokenAuthController::class, 'postPhoneVerify']);
});

