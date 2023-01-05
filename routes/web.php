<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\TipsController;
use App\Http\Controllers\LaddersController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\Auth\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
| TC Note: see name at end. Good for using in links, buttons using route(name)
*/

Auth::routes();

/*
Route::get('/', function () {
     return view('home');
});
*/

// home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', function() { return redirect('/'); });

// rules page
Route::get('/rules', function () {
     return view('rules');
})->name('rules');

// tip
Route::get('/tip', [TipsController::class, 'index'])->name('tip');

// save tip
Route::post('tip/save', [TipsController::class, 'saveTip']);

// action on click of round button in Tips screen
Route::get('/tip/round/{round}',[TipsController::class, 'showRound']);

//ladder
Route::get('/ladder', [LaddersController::class, 'index'])->name('ladder');

// profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/save', [ProfileController::class, 'saveProfile']);


// ---- ADMIN FUNCTIONALITY -------------------
// games admin
Route::get('/games', [GamesController::class, 'index'])->name('games');

// action on click of round button in Games screen
Route::get('/games/round/{round}',[GamesController::class, 'showRound']);

// saving of game score
Route::post('games/{id}', [GamesController::class, 'saveGame']);

// games import for admins
Route::get('/games/import', function () {
     return view('admin.import');
})->name('import');

// importing of games file
Route::post('games/import/save', [GamesController::class, 'importGames']);

// user admin
Route::get('/user', [UserAdminController::class, 'index'])->name('userAdmin');

// save user admin
Route::post('user/{id}', [UserAdminController::class, 'saveUser']);

// delete user admin
Route::get('user/delete/{id}', [UserAdminController::class, 'deleteUser']);

// ladder admin
Route::get('ladder/admin', [LaddersController::class, 'ladderAdmin'])->name('ladderAdmin');

// save ladder admin
Route::post('ladder/admin/{id}', [LaddersController::class, 'saveLadderAdmin']);