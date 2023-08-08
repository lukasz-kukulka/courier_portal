<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeclarationPrintController;
use App\Http\Controllers\UserAnnouncementController;

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

Auth::routes( ['verify' => true] );

Route::get('/', function () {
    return view('welcome');
})->name('main');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//############################### DECLARATION ########################

Route::get('/cn22', function () {
    return view('cn22');
})->name('cn22')->middleware( ['auth', 'verified'] );

Route::get('/cn23', function () {
    return view('cn23');
})->name('cn23')->middleware( ['auth', 'verified'] );

Route::post('/pdf_gen', function (Request $request) {
    $postData = $request->all();

    $pdf = app()->makeWith(DeclarationPrintController::class, ['post' => $postData]);
    return $pdf->generatePDFDocument();
})->name('pdf_gen')->middleware( ['auth', 'verified'] ); // nie wiem czy bedzie działać trzeba przetestować

//############################### ACCOUNT TYPES ########################

Route::get('/accounts/account_register', function () {
    return view('accounts.account_register');
})->name('account_register')->middleware( ['auth', 'verified'] );


Route::post('/accounts/confirmed_account', function () {
    return view('accounts.confirmed_account');
})->name('confirmed_account')->middleware( ['auth', 'verified'] );

Route::get('/accounts/confirmed_account', function () {
    return view('accounts.confirmed_account');
})->name('account_confirmed_get')->middleware( ['auth', 'verified'] );

Route::post('/person_data', 'App\Http\Controllers\CustomUserController@create')->name('create_person_data')->middleware( ['auth', 'verified'] );

Route::get('/accounts/confirmed_account_last', function () {
    return view('accounts.confirmed_account_last');
})->name('account_last_confirmed')->middleware( ['auth', 'verified'] );

// ############################### OTHERS ########################################endregion

Route::resource('user_announcement', UserAnnouncementController::class)->middleware(['auth', 'verified']);
Route::post('cargo_generator', [UserAnnouncementController::class, 'cargoDataGenerator'])->middleware(['auth', 'verified'])->name('user_announcement.cargoDataGenerator');
Route::post('user_announcement_summary', [UserAnnouncementController::class, 'summary'])->middleware(['auth', 'verified'])->name('user_announcement.summary');
