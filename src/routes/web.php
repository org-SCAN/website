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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
/*
Route::get('manage_refugees/create/{fields}', [
    'as' => 'manage_refugees.create',
    'uses' => 'ManageRefugeesController@create'
]);*/
Route::resource("manage_refugees", \App\Http\Controllers\ManageRefugeesController::class);
Route::resource("fields", \App\Http\Controllers\FieldsController::class);
