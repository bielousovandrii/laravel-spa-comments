<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommentController;
use App\Events\MessageSent;
use App\Http\Controllers\CaptchaServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//})
Route::get('/', [CommentController::class, 'home'])->name('home')->middleware('auth:sanctum');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth.redirect');

Route::get('/send-message', function () {
    $message = 'This is a test message';
    broadcast(new MessageSent($message))->toOthers();

    return 'Message sent!';
});
Route::get('/test-captcha', function () {
    return [
        'captcha_src' => Captcha::src(),
        'captcha_status' => Captcha::check('test-captcha-value') ? 'valid' : 'invalid'
    ];
});

Route::get('/captcha/{config}', function ($config) {
    return Captcha::create($config);
});

Route::get('/contact-form', [CaptchaServiceController::class, 'index']);
Route::post('/captcha-validation', [CaptchaServiceController::class, 'capthcaFormValidate']);
Route::get('/reload-captcha', [CaptchaServiceController::class, 'reloadCaptcha']);

Auth::routes();
