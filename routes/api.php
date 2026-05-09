<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UrlController;

Route::post('/shorten', [UrlController::class, 'store'])->middleware('throttle:5,1');
Route::get('/go/{shortCode}', [UrlController::class, 'show']);
Route::get('/stats/{shortCode}', [UrlController::class, 'stats']);
