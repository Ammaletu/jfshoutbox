<?php

use App\Http\Controllers\AnswerController;
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

Route::middleware('api')->group(function () {
	Route::get('/answers', [AnswerController::class, 'index'])->name('answers.index');
	Route::post('/answers', [AnswerController::class, 'store'])->name('answers.store');
	Route::post('/answers/{answer}/block', [AnswerController::class, 'block'])->name('answers.block');
	Route::post('/answers/{answer}/publish', [AnswerController::class, 'publish'])->name('answers.publish');
});
