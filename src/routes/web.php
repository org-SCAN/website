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
    return view('welcome');
});
Route::get('/analyse_data', function () {
    return view('analyse_data');
});
Route::get('/change_fields', function () {
    return view('change_fields');
});
Route::get('/change_password', function () {
    return view('change_password');
});
Route::get('/edit_users', function () {
    return view('edit_users');
});
Route::get('/home', function () {
    return view('home');
});

Route::get('/modify_data', function () {
    return view('modify_data');
});
Route::get('/new_user', function () {
    return view('new_user');
});
