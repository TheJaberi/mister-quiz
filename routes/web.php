<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Questions\QuestionController;
use App\Http\Controllers\ResultsController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

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
    return view('home');
})->name('home');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);


Route::middleware([Authenticate::class])->group(function (){
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update']);
    
    Route::GET('/quiz', [QuizController::class, 'page'])->name('quiz');
    Route::POST('/quiz', [QuizController::class, 'store'])->name('storeQuiz');
    
    Route::GET('/results', [ResultsController::class, 'index'])->name('results');
});

Route::GET('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
