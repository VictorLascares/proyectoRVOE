<?php

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
    return view('pages.home');
});
Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/consult', function () {
    return view('pages.consult');
});
Route::get('/users', function () {
    return view('users.index');
});
Route::get('/editUser', function () {
    return view('users.edit');
});
Route::get('/showUser', function () {
    return view('users.show');
});
Route::get('/createUser', function () {
    return view('users.create');
});