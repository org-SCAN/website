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
Route::get('/content.json', function () {
    return \Illuminate\Support\Facades\Storage::disk('public')->get('content.json');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('cytoscape', [
    'as' => 'cytoscape.index',
    'uses' => '\App\Http\Controllers\CytoscapeController@index'
]);

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
Route::resource("user", \App\Http\Controllers\ManageUsersController::class);
Route::resource("api_logs", \App\Http\Controllers\ApiLogController::class);
