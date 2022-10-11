<?php

use App\Http\Controllers\ApiLogController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\DuplicateController;
use App\Http\Controllers\FieldsController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ListControlController;
use App\Http\Controllers\ManageUsersController;
use App\Http\Controllers\RefugeeController;
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

Route::get('/', [
    'as' => 'person.index',
    'uses' => '\App\Http\Controllers\RefugeeController@index'
])->middleware('auth');
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

Route::get('person/json/create', [
    'as' => 'person.json.create',
    'uses' => '\App\Http\Controllers\RefugeeController@createFromJson'
])->middleware('auth');
Route::get('links/json/create', [
    'as' => 'links.json.create',
    'uses' => '\App\Http\Controllers\LinkController@createFromJson'
])->middleware('auth');
Route::post('person/json/store', [
    'as' => 'person.json.store',
    'uses' => '\App\Http\Controllers\RefugeeController@storeFromJson'
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

Route::get('lists_control/add_to_list/{list_control}', [
    'as' => 'lists_control.add_to_list',
    'uses' => '\App\Http\Controllers\ListControlController@AddToList'
])->middleware('auth');

Route::post('lists_control/update_list/{listControl}', [
    'as' => 'lists_control.update_list',
    'uses' => '\App\Http\Controllers\ListControlController@UpdateList'
])->middleware('auth');

Route::put('person/fix_duplicated_reference/{id} ', [
    'as' => 'person.fix_duplicated_reference',
    'uses' => '\App\Http\Controllers\RefugeeController@fixDuplicatedReference'
])->middleware('auth');


Route::resource("person", RefugeeController::class)->middleware('auth');
Route::resource("fields", FieldsController::class)->middleware('auth');
Route::resource("lists_control", ListControlController::class)->middleware('auth');
Route::resource("links", LinkController::class)->middleware('auth');
Route::resource("user", ManageUsersController::class)->middleware('auth');
Route::resource("duplicate", DuplicateController::class)->middleware('auth');
Route::resource("api_logs", ApiLogController::class)->middleware('auth');
Route::resource("crew", CrewController::class)->middleware('auth');
