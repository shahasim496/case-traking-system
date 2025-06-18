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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



// Evidence Management API Routes
Route::get('/v1/evidences/{key}', [App\Http\Controllers\Api\EvidenceApiController::class, 'getAllEvidences']);

Route::get('/v1/evidences/{id}/{key2}', [App\Http\Controllers\Api\EvidenceApiController::class, 'getEvidenceById']);
Route::get('/v1/evidences/reports/statistics/{key}', [App\Http\Controllers\Api\EvidenceApiController::class, 'reports']);
