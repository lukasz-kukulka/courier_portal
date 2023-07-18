<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeclarationPrintController;

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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->name('main');

Route::get('/hello', function () {
    return 'TEST COS';
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//############################### DECLARATION ########################

Route::get('/cn22', function () {
    return view('cn22');
})->name('cn22')->middleware('auth');

Route::get('/cn23', function () {
    return view('cn23');
})->name('cn23')->middleware('auth');

Route::post('/pdf_gen', function (Request $request) {
    $postData = $request->all();

    $pdf = app()->makeWith(DeclarationPrintController::class, ['post' => $postData]);
    return $pdf->generatePDFDocument();
})->name('pdf_gen')->middleware('auth'); // nie wiem czy bedzie działać trzeba przetestować

//############################### ACCOUNT TYPES ########################

Route::get('/accounts/account_register', function () {
    return view('accounts.account_register');
})->name('account_register')->middleware('auth');


Route::post('/accounts/confirmed_account', function () {
    return view('accounts.confirmed_account');
})->name('confirmed_account')->middleware('auth');

Route::get('/accounts/confirmed_account', function () {
    return view('accounts.confirmed_account');
})->name('account_confirmed_get')->middleware('auth');

Route::post('/person_data', 'App\Http\Controllers\AccountTypeController@create')->name('create_person_data')->middleware('auth');

Route::get('/accounts/confirmed_account_last', function () {
    return view('accounts.confirmed_account_last');
})->name('account_last_confirmed')->middleware('auth');

// Route::get('/', function () {
//     return response( '<h1>xxxxx</h1>' )->header( 'Content-Type', 'text/plain' );
// });

// Route::get('/post/{id}', function ($id) {
//     dd( $id );
//     return response( 'Post' . $id );
// })->where( 'id', '[0-9]+' );

// Route::get('search', function ( Request $request ) {
//     dd( $request );
//     return response( 'Post' . $id );
// })->where( 'id', '[0-9]+' );

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
