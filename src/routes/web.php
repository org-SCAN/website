<?php

use App\Http\Controllers\ApiLogController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\DuplicateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FieldsController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ListControlController;
use App\Http\Controllers\ManageUsersController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\RefugeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SourceController;
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
    return view('dashboard');
})->name("/")->middleware('auth');

Route::get('/content.json',
    function () {
        return Storage::disk('public')->get('content.json');
    })->middleware('auth');

Route::middleware([
    'auth:sanctum',
    'verified',
])->get('/dashboard',
    function () {
        return view('dashboard');
    })->name('dashboard');

Route::get('cytoscape', [
    'as' => 'cytoscape.index',
    'uses' => '\App\Http\Controllers\CytoscapeController@index',
])->middleware('auth');

Route::get('person/json/create',
    [
        'as' => 'person.create_from_json',
        'uses' => '\App\Http\Controllers\RefugeeController@createFromJson',
    ])->middleware('auth');
Route::get('links/json/create',
    [
        'as' => 'links.create_from_json',
        'uses' => '\App\Http\Controllers\LinkController@createFromJson',
    ])->middleware('auth');
Route::post('person/json/store',
    [
        'as' => 'person.store_from_json',
        'uses' => '\App\Http\Controllers\RefugeeController@import',
    ])->middleware('auth');
Route::post('links/json/store',
    [
        'as' => 'links.store_from_json',
        'uses' => '\App\Http\Controllers\LinkController@storeFromJson',
    ])->middleware('auth');
Route::post('user/request_role/{id}',
    [
        'as' => 'user.request_role',
        'uses' => '\App\Http\Controllers\ManageUsersController@RequestRole',
    ])->middleware('auth');

Route::post('user/change_language/{id}',
    [
        'as' => 'user.change_language',
        'uses' => '\App\Http\Controllers\ManageUsersController@ChangeLanguage',
    ])->middleware('auth');

Route::post('user/change_team/{id}',
    [
        'as' => 'user.change_team',
        'uses' => '\App\Http\Controllers\ManageUsersController@ChangeTeam',
    ])->middleware('auth');



Route::get('user/grant_role/{id}',
    [
        'as' => 'user.grant_role',
        'uses' => '\App\Http\Controllers\ManageUsersController@GrantRole',
    ])->middleware('auth');
Route::get('user/reject_role/{id}',
    [
        'as' => 'user.reject_role',
        'uses' => '\App\Http\Controllers\ManageUsersController@RejectRole',
    ])->middleware('auth');

Route::get('lists_control/add_to_list/{list_control}',
    [
        'as' => 'lists_control.add_to_list',
        'uses' => '\App\Http\Controllers\ListControlController@AddToList',
    ])->middleware('auth');

Route::post('lists_control/update_list/{listControl}',
    [
        'as' => 'lists_control.update_list',
        'uses' => '\App\Http\Controllers\ListControlController@UpdateList',
    ])->middleware('auth');

Route::put('person/fix_duplicated_reference/{id} ',
    [
        'as' => 'person.fix_duplicated_reference',
        'uses' => '\App\Http\Controllers\RefugeeController@fixDuplicatedReference',
    ])->middleware('auth');

Route::get('lists_control/create_fields/{listControl}',
    [
        'as' => 'lists_control.create_fields',
        'uses' => '\App\Http\Controllers\ListControlController@createFields',
    ])->middleware('auth');

Route::post('lists_control/store_fields/{listControl}',
    [
        'as' => 'lists_control.store_fields',
        'uses' => '\App\Http\Controllers\ListControlController@storeFields',
    ])->middleware('auth');

Route::post('lists_control/store_displayed_value/{listControl}',
    [
        'as' => 'lists_control.store_displayed_value',
        'uses' => '\App\Http\Controllers\ListControlController@storeDisplayedValue',
    ])->middleware('auth');

Route::delete('lists_control/{listControl}/delete_list_elem/{element}',
    [
        'as' => 'lists_control.delete_list_elem',
        'uses' => '\App\Http\Controllers\ListControlController@destroyListElem',
    ])->middleware('auth');

Route::get('lists_control/{listControl}/edit/{element}',
    [
        'as' => 'lists_control.edit_list_elem',
        'uses' => '\App\Http\Controllers\ListControlController@editListElem',
    ])->middleware('auth');

Route::post('lists_control/{listControl}/edit/{element}',
    [
        'as' => 'lists_control.update_list_elem',
        'uses' => '\App\Http\Controllers\ListControlController@updateListElem',
    ])->middleware('auth');

Route::put('crew/{crew}/addUser',
    [
        'as' => 'crew.addUser',
        'uses' => '\App\Http\Controllers\CrewController@addUser',
    ])->middleware('auth');

Route::resource("person",
    RefugeeController::class)->middleware('auth');
Route::resource("fields",
    FieldsController::class)->middleware('auth');
Route::resource("lists_control",
    ListControlController::class)->middleware('auth');

Route::get("links/create/{origin?}/{refugee?}",
    [
        "as" => 'links.create',
        "uses" => '\App\Http\Controllers\LinkController@create',
    ])->middleware('auth');
Route::resource("links",
    LinkController::class)->middleware('auth')->except(['create']);


Route::resource("user",
    ManageUsersController::class)->middleware('auth');
Route::post("user/invite/{user}",
    [
        "as" => 'user.invite',
        "uses" => '\App\Http\Controllers\ManageUsersController@invite',
    ])->middleware('auth');


Route::get('duplicate/compute',
    [
        "as" => 'duplicate.compute',
        "uses" => "\App\Http\Controllers\DuplicateController@compute",
    ])->middleware('auth');

Route::get('duplicate/resolve/{duplicate}',
    [
        "as" => 'duplicate.resolve',
        "uses" => "\App\Http\Controllers\DuplicateController@resolve",
    ])->middleware('auth');

Route::get('duplicate/multiple_resolve',
    [
        "as" => 'duplicate.multiple_resolve',
        "uses" => "\App\Http\Controllers\DuplicateController@multiple_resolve",
    ])->middleware('auth');

Route::get('duplicate/choose_algorithm',
    [
        "as" => 'duplicate.choose_algorithm',
        "uses" => "\App\Http\Controllers\DuplicateController@choose_algorithm",
    ])->middleware('auth');


Route::resource("duplicate",
    DuplicateController::class)->middleware('auth');

Route::get('api_logs', [
    ApiLogController::class,
    'index',
])->name('api_logs.index')->middleware('auth');
Route::get('api_logs/{fileIndex}/{lineIndex}',
    [
        ApiLogController::class,
        'show',
    ])->name('api_logs.show')->middleware('auth');

Route::resource("crew",
    CrewController::class)->middleware('auth');
Route::resource("roles",
    RoleController::class)->middleware('auth');
Route::resource("permissions",
    PermissionController::class)->middleware('auth');
Route::resource("event",
    EventController::class)->middleware('auth');
Route::resource("source",
    SourceController::class)->middleware('auth');
Route::resource("place",
    PlaceController::class)->middleware('auth');
