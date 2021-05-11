<?php

use App\Http\Controllers\ApiLogController;
use App\Http\Controllers\DuplicateController;
use App\Http\Controllers\FieldsController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ListControlController;
use App\Http\Controllers\ManageRefugeesController;
use App\Http\Controllers\ManageUsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    return Storage::disk('public')->get('content.json');
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


Route::put('manage_refugees/fix_duplicated_reference/{id} ', [
    'as' => 'manage_refugees.fix_duplicated_reference',
    'uses' => '\App\Http\Controllers\ManageRefugeesController@fixDuplicatedReference'
]);


Route::resource("manage_refugees", ManageRefugeesController::class);
Route::resource("fields", FieldsController::class);
Route::resource("lists_control", ListControlController::class);
Route::resource("links", LinkController::class);
Route::get('request', [\App\Http\Controllers\RequestRole::class,'getrequest']);
Route::post('request', [\App\Http\Controllers\RequestRole::class,'request']);
Route::post('request/grant', [\App\Http\Controllers\RequestRole::class,'grant']);
Route::resource("user", ManageUsersController::class);
Route::resource("duplicate", DuplicateController::class);
Route::resource("api_logs", ApiLogController::class);
