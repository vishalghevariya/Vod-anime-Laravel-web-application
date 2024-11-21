<?php

use App\Http\Controllers\AnimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/anime/{animSlug}', [AnimeController::class, 'index'])->middleware(['throttle:api']);
