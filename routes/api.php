<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RatingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [AuthController::class, 'getProfile']);
    Route::post('/user/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/user/profile/upload-picture', [AuthController::class, 'uploadProfilePicture']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ads', [AdController::class, 'store']);
    Route::get('/ads', [AdController::class, 'index']);
    Route::get('/user/pending-ads', [AdController::class, 'getUserPendingAds']); 
    Route::get('/user/approved-ads', [AdController::class, 'getUserApprovedAds']); 
    Route::get('/user/rejected-ads', [AdController::class, 'getUserRejectedAds']);
});


Route::get('categories', [CategoryController::class, 'getCategories']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ads/{adId}/rating', [RatingController::class, 'store']);  // Add rating and review
    Route::get('/ads/{adId}/ratings', [RatingController::class, 'show']);   // Get all ratings for an ad
});









