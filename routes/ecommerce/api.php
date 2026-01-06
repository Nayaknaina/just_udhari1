<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ecomm\SiteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

$api_branch = app('branch') ;

Route::post("/{$api_branch}",[SiteController::class,'home']);
Route::post("/{$api_branch}about",[SiteController::class,'home']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

