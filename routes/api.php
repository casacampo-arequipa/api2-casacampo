<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CottageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\OpinionController;
use App\Http\Controllers\Api\PackegeController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Tienda\HomeController;
use Illuminate\Support\Facades\Route;

// Rutas de autentificacion
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'logincookies'])->name('logincookies');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('cookie.token')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::get('/me', [AuthController::class, 'me'])->middleware('cookie.token')->name('me');
});

// Rutas para el publico
Route::group([
    'middleware' => ['api']
], function ($router) {
    //api inicio
    Route::get('/home', [HomeController::class, "home"]);
    //api cabañas
    Route::get('/cottage', [CottageController::class, "index"]);
    //aplicar promociones
    Route::get('/apply_promotion', [PromotionController::class, "apply_promotion"]);
    //api lista reservaciones
    Route::get('/reservation', [ReservationController::class,  "index"]);
    //api crear reservaciones
    Route::post('/reservation', [ReservationController::class,  "store"]);
    //api opinion
    Route::resource('/opinion', OpinionController::class);
    //api opinion
    Route::resource('/contact', ContactController::class);
    //api lista paquetes
    Route::get('/packages', [PackegeController::class, "index"]);
});

Route::group([
    'middleware' => ['api', 'auth:api', 'role']
], function ($router) {
    //api cabañas
    Route::resource('/cottage-admin', CottageController::class);
    Route::resource('/users-admin', UserController::class);
    Route::get('/reservation-admin/helps', [ReservationController::class, 'helps']);
    Route::resource('/reservation-admin', ReservationController::class);
    Route::resource('/packages-admin', PackegeController::class);
    Route::post('/packages-admin/{id}', [PackegeController::class, "update"]);
    //api promociones
    Route::resource('/promotion-admin', PromotionController::class);
    Route::get('/dashboard', [DashboardController::class,  "infoDashboard"]);
});
