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
Route::middleware('auth:sanctum')->get('/demo', function (Request $request) {
    if($request->user()->tokenCan("read")){
        return response("Yooo ".$request->user()->name." t'es le maxipoto");
    }
});
/*
Route::middleware('auth:sanctum')->post('/manage_refugees', function (Request $request) {
    if($request->user()->tokenCan("update")){

        return response("Yooo ".$request->user()->name." voici le body : ".$request->getContent());
    }
    return response("Your token can't be use to send datas", 403);
});*/

Route::middleware('auth:sanctum')->post('/manage_refugees', "\App\Http\Controllers\ManageRefugeesController@handleApiRequest");
