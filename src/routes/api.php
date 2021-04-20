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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/manage_refugees', "\App\Http\Controllers\ManageRefugeesController@handleApiRequest");
Route::middleware('auth:sanctum')->post('/links', "\App\Http\Controllers\LinkController@handleApiRequest");
Route::middleware('auth:sanctum')->get('/fields', "\App\Http\Controllers\FieldsController@handleApiRequest");
