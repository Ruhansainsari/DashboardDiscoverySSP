<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminAdController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::redirect('/', '/admin/login');
Route::get('admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin dashboard route
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/advertisement-dashboard', [AdminController::class, 'showAdvertisementDashboard'])
     ->name('admin.advertisement_dashboard');
     Route::get('admin/revenue', [AdminController::class, 'revenue'])->name('admin.revenue');


    // Category management routes
    Route::get('admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index'); // List categories
    Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create'); // Show create form
    Route::post('admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store'); // Store new category
    Route::get('admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit'); // Show edit form
    Route::put('admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update'); // Update category
    Route::delete('admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy'); // Delete category

    // Route for viewing pending ads
    Route::get('admin/ads', [AdminController::class, 'index'])->name('admin.ads.index');

    //search for approved ads
    Route::get('admin/ads/search', [AdminController::class, 'searchAds'])->name('admin.ads.search');
    
    // Routes for approving and rejecting ads
    Route::post('admin/ads/{id}/approve', [AdminController::class, 'approve'])->name('admin.ads.approve');
    Route::post('admin/ads/{id}/reject', [AdminController::class, 'reject'])->name('admin.ads.reject');

    //show aproved ads
    Route::get('admin/ads/approved', [AdminController::class, 'approvedAds'])->name('admin.ads.approved');

    //get all users 
    Route::get('admin/users', [AdminController::class, 'users'])->name('admin.users.index');

    // Route to view all ratings and reviews for a specific ad
    Route::get('admin/ads/{adId}/reviews', [AdminController::class, 'showReviews'])->name('admin.ads.reviews');
    Route::delete('admin/ratings/{id}', [AdminController::class, 'deleteRating'])->name('admin.ratings.delete');


});







