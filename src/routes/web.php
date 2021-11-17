<?php

use App\Http\Controllers\ApiLogController;
use App\Http\Controllers\DuplicateController;
use App\Http\Controllers\FieldsController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\CrewController;
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
})->middleware('auth');
Route::get('/content.json', function () {
    return Storage::disk('public')->get('content.json');
})->middleware('auth');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('cytoscape', [
    'as' => 'cytoscape.index',
    'uses' => '\App\Http\Controllers\CytoscapeController@index'
])->middleware('auth');

Route::get('manage_refugees/json/create', [
    'as' => 'manage_refugees.json.create',
    'uses' => '\App\Http\Controllers\ManageRefugeesController@createFromJson'
])->middleware('auth');
Route::get('links/json/create', [
    'as' => 'links.json.create',
    'uses' => '\App\Http\Controllers\LinkController@createFromJson'
])->middleware('auth');
Route::post('manage_refugees/json/store', [
    'as' => 'manage_refugees.json.store',
    'uses' => '\App\Http\Controllers\ManageRefugeesController@storeFromJson'
])->middleware('auth');
Route::post('links/json/store', [
    'as' => 'links.json.store',
    'uses' => '\App\Http\Controllers\LinkController@storeFromJson'
])->middleware('auth');
Route::post('user/request_role/{id}', [
    'as' => 'user.request_role',
    'uses' => '\App\Http\Controllers\ManageUsersController@RequestRole'
])->middleware('auth');
Route::post('user/change_team/{id}', [
    'as' => 'user.change_team',
    'uses' => '\App\Http\Controllers\ManageUsersController@ChangeTeam'
])->middleware('auth');
Route::get('user/grant_role/{id}', [
    'as' => 'user.grant_role',
    'uses' => '\App\Http\Controllers\ManageUsersController@GrantRole'
])->middleware('auth');
Route::get('user/reject_role/{id}', [
    'as' => 'user.reject_role',
    'uses' => '\App\Http\Controllers\ManageUsersController@RejectRole'
])->middleware('auth');


Route::put('manage_refugees/fix_duplicated_reference/{id} ', [
    'as' => 'manage_refugees.fix_duplicated_reference',
    'uses' => '\App\Http\Controllers\ManageRefugeesController@fixDuplicatedReference'
])->middleware('auth');


Route::resource("manage_refugees", ManageRefugeesController::class)->middleware('auth');
Route::resource("fields", FieldsController::class)->middleware('auth');
Route::resource("lists_control", ListControlController::class)->middleware('auth');
Route::resource("links", LinkController::class)->middleware('auth');
Route::resource("user", ManageUsersController::class)->middleware('auth');
Route::resource("duplicate", DuplicateController::class)->middleware('auth');
Route::resource("api_logs", ApiLogController::class)->middleware('auth');
Route::resource("crew", CrewController::class)->middleware('auth');
