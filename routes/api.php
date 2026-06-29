<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/catering-packages/{cateringPackage:slug}', [CateringPackageController::class, 'show']);
Route::apiResource('/catering-packages', CateringPackageController::class);