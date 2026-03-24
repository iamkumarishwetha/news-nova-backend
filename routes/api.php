<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;

Route::get('/top-headlines', [NewsController::class, 'index']);
Route::get('/everything', [NewsController::class, 'everything']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:api')->post('/react', [NewsController::class, 'react']);
Route::get('/reactions', [NewsController::class, 'getCounts']);