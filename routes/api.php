<?php

use App\Http\Controllers\Common\LogApiController;
use App\Http\Controllers\Inv\GoodsIssueController;
use App\Http\Controllers\Inv\GoodsReceiptController;
use App\Http\Controllers\Md\ProductController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'common'], function () {
    Route::group(['prefix' => '/log-api'], function () {
        Route::post('/', [LogApiController::class, 'index']);
    });
});

Route::group(['prefix' => 'md'], function () {
    Route::group(['prefix' => '/product'], function () {
        Route::post('/', [ProductController::class, 'index']);
        Route::post('/create', [ProductController::class, 'create']); 
        Route::post('update/{id}', [ProductController::class, 'update']);
        Route::delete('{id}', [ProductController::class, 'delete']);
    });
});

Route::group(['prefix' => 'inv/'], function () {
    Route::group(['prefix' => 'goods-receipt/'], function () {
        Route::post('create', [GoodsReceiptController::class, 'create']);
    });

    Route::group(['prefix' => 'goods-issue/'], function () {
        Route::post('create', [GoodsIssueController::class, 'create']);
    });    
});
