<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\LogController;
use App\Http\Controllers\API\InfoController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/import', [ItemController::class, 'import']);
Route::post('/update', [ItemController::class, 'allupdate']);
Route::apiResource('item', ItemController::class);
Route::apiResource('log', LogController::class);
Route::apiResource('info', InfoController::class);
Route::put('/item/favorite/{id}', [ItemController::class, 'favorite']);
Route::post('/item/delete', [ItemController::class, 'delete']);
Route::post('/item/stock', [ItemController::class, 'stock']);
Route::post('/log/delete', [LogController::class, 'delete']);
Route::get('/analysis', [LogController::class, 'analysis']);
