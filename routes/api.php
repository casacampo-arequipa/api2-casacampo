<?php

use App\Http\Controllers\Api\CottageController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\OpinionController;
use App\Http\Controllers\Api\PackegeController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::group([
    // 'middleware' => ['api', 'auth:api']
    'middleware' => ['api']
], function ($router) {
    //api cabañas
    Route::resource('/cottage', CottageController::class);
    //api descuentos
    Route::resource('/discount', DiscountController::class);
    //api promociones
    Route::resource('/promotion', PromotionController::class);
    //api reservaciones
    Route::resource('/reservation', ReservationController::class);
    //api reservaciones
    Route::resource('/opinion', OpinionController::class);

    Route::resource('/packages', PackegeController::class);
    Route::post('/packages-admin/{id}', [PackegeController::class, "update"]);
});
Route::group([
    'middleware' => ['api', 'auth:api']
], function ($router) {
    //api cabañas
    Route::resource('/cottage-admin', CottageController::class);
    Route::resource('/users-admin', UserController::class);
    Route::resource('/reservation-admin', ReservationController::class);
    Route::resource('/packages-admin', PackegeController::class);
    // Route::post('/packages-admin/{id}', [PackegeController::class, "update"]);
    //api descuentos
    //  Route::resource('/discount', DiscountController::class);
    //  //api promociones
    //  Route::resource('/promotion', PromotionController::class);
    // //api reservaciones
    // Route::resource('/reservation', ReservationController::class);
    //  //api reservaciones
    //  Route::resource('/opinion', OpinionController::class);
});
