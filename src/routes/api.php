<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user',
    function (Request $request) {
        return $request->user();
    });

Route::middleware('auth:sanctum')->get('/persons/{crew?}',
    "\App\Http\Controllers\RefugeeController@apiGetPerson");
Route::middleware('auth:sanctum')->post('/person',
    "\App\Http\Controllers\RefugeeController@handleApiRequest");
Route::middleware('auth:sanctum')->post('/links',
    "\App\Http\Controllers\LinkController@handleApiRequest");
Route::middleware('auth:sanctum')->get('/links/{crew?}',
    "\App\Http\Controllers\LinkController@apiGetRelations");
Route::middleware('auth:sanctum')->get('/fields',
    "\App\Http\Controllers\FieldsController@handleApiRequest");

Route::middleware('auth:sanctum')->get('/lists',
    "\App\Http\Controllers\ListControlController@apiGetLists");
Route::middleware('auth:sanctum')->get('/list/{listControl}',
    "\App\Http\Controllers\ListControlController@apiGetList");
