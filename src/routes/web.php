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
Route::get('manage_refugees/json/create', [
    'as' => 'manage_refugees.json.create',
    'uses' => '\App\Http\Controllers\ManageRefugeesController@createFromJson'
]);
Route::post('manage_refugees/json/store', [
    'as' => 'manage_refugees.json.store',
    'uses' => '\App\Http\Controllers\ManageRefugeesController@storeFromJson'
]);

Route::resource("manage_refugees", \App\Http\Controllers\ManageRefugeesController::class);
Route::resource("fields", \App\Http\Controllers\FieldsController::class);
Route::resource("lists_control", \App\Http\Controllers\ListControlController::class);
Route::resource("links", \App\Http\Controllers\LinkController::class);
