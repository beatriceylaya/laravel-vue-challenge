<?php

use App\Http\Controllers\CoffeeMachineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix('/machine')->group(function () {
    Route::get('/status', [CoffeeMachineController::class, 'status']);
    Route::post('/brew', [CoffeeMachineController::class, 'brew']);
    Route::post('/fill-water', [CoffeeMachineController::class, 'fillWater']);
    Route::post('/fill-coffee', [CoffeeMachineController::class, 'fillCoffee']);
});