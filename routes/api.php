<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\FoodCategoryApiController;
use App\Http\Controllers\Api\MenuItemApiController;

// Passport-protected route to fetch authenticated user data
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//0. Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//1. customer routes
Route::post('/user/registrationStore', [CustomerController::class, 'registrationStore']);
Route::post('/user/send-otp', [CustomerController::class, 'sendOTP']);
Route::post('/user/verify-otp', [CustomerController::class, 'verifyOTPAndLogin']);

// Route::post('/login', [AuthController::class, 'login']);

//2. public  Admin routes
Route::post('/admin/login', [AdminController::class, 'login'])
    ->name('admin.login');

//3. public restaurant routes
Route::post('/restaurant/login', [RestaurantController::class, 'restaurantLoginApi'])
    ->name('restaurant.login');

//4. public delivery

Route::post('/delivery/registrationStore', [DeliveryController::class, 'deliveryRegistrationApi'])
    ->name('delivery.registrationStore');
Route::post('/delivery/login', [DeliveryController::class, 'deliveryLoginApi'])
    ->name('delivery.login');

// Protected delivery routes


// // Protected delivery routes
// Route::middleware(['auth:delivery-api'])->prefix('delivery')->group(function () {
//     Route::get('/dashboard', [DeliveryController::class, 'dashboard']);
//     Route::get('/profile', [DeliveryController::class, 'profile']);
// });



//1. user Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User/Customer routes
    Route::middleware('customer')->group(function () {
        Route::get('/user/dashboard', [CustomerController::class, 'dashboard']);
        Route::get('/profile', [CustomerController::class, 'profile']);
    });

    //2. Admin Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/profile', [AdminController::class, 'profile']);

        // Restaurant CRUD operations for admin
        Route::post('/restaurants/store', [RestaurantController::class, 'store']);
        Route::get('/restaurants/get', [RestaurantController::class, 'get']);
        Route::get('/restaurants/edit/{id}', [RestaurantController::class, 'edit']);
        Route::put('/restaurants/update/{id}', [RestaurantController::class, 'update']);
    });

});

//3. //10:53 Protected routes Restaurant API routes
// Restaurant API routes
Route::middleware(['restaurant'])->prefix('restaurant')->group(function () {
    // Route::middleware(['auth:restaurant-api', 'restaurant'])->prefix('restaurant')->group(function () {
    Route::get('/dashboard', [RestaurantController::class, 'dashboard']);
    Route::get('/profile', [RestaurantController::class, 'profile']);

    //Menu Management : restaurant/food-categories
    Route::get('/food-categories', [FoodCategoryApiController::class, 'getFoodCategories']);
    Route::post('/store-food-category', [FoodCategoryApiController::class, 'storeFoodCategory']);
    // Route::put('/update-food-categories/{id}' , [FoodCategoryApiController::class, 'updateFoodCategories'])
    // ->middleware(\Illuminate\Routing\Middleware\HandlePutFormData::class);;
    Route::get('/edit-food-category/{id}', [FoodCategoryApiController::class, 'editFoodCategory']);
    Route::post('/update-food-category/{id}', [FoodCategoryApiController::class, 'updateFoodCategory']);
    Route::delete('/delete-food-category/{id}', [FoodCategoryApiController::class, 'deleteFoodCategory']);

    // menu-items
    Route::get('/menu-items', [MenuItemApiController::class, 'getMenuItems']);
});

//4.  Delivery API routes
Route::middleware(['delivery'])->prefix('delivery')->group(function () {
    Route::get('/dashboard', [DeliveryController::class, 'dashboard']);
    Route::get('/profile', [DeliveryController::class, 'profile']);
});
