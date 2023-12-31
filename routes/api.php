<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

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

Route::group(['middleware' => ['throttle:10000,1','azure.face.api.limiter']], function () {
    Route::post('/detect-faces',[UserController::class,'DetectFaces'])->name('user.detect-faces');
    Route::post('/verify-faces',[UserController::class,'VerifyFaces'])->name('user.verify-faces');
});
