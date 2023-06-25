<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return 'TEST COS';
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return response( '<h1>xxxxx</h1>' )->header( 'Content-Type', 'text/plain' );
});

Route::get('/post/{id}', function ($id) {
    dd( $id );
    return response( 'Post' . $id );
})->where( 'id', '[0-9]+' );

Route::get('search', function ( Request $request ) {
    dd( $request );
    return response( 'Post' . $id );
})->where( 'id', '[0-9]+' );
