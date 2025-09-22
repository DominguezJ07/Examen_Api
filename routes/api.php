<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatSuportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\imageController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('roles',RoleController::class);
Route::apiResource('categories',CategoryController::class);
Route::apiResource('ChatSuport',ChatSuportController::class);
Route::apiResource('user',UserController::class);
Route::apiResource('comment',commentController::class);
Route::apiResource('sellers',SellerController::class);
Route::apiResource('complaint',ComplaintController::class);
Route::apiResource('publication',PublicationController::class);
Route::apiResource('image',imageController::class);