<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\DeliveryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\RestaurantController;
use Illuminate\Support\Facades\Route;

// Public homepage only
Route::get('/', function () {
    return view('welcome');
})->name('home');


// Guest routes - prevent authenticated users from accessing login pages
Route::middleware('guest:admin')->group(function () {
    Route::match(['get', 'post'], '/admin/login', [AdminController::class, 'login'])
        ->name('admin.login');
});

Route::middleware('guest:restaurant')->group(function () {
    Route::match(['get', 'post'], '/restaurant/login', [RestaurantController::class, 'restaurantLogin'])
        ->name('restaurant.login');
});

Route::middleware('guest:delivery')->group(function () {
    Route::get('/delivery/registration', [DeliveryController::class, 'deliveryRegistrationForm'])->name('delivery.registration');
    Route::post('/delivery/registrationStore', [DeliveryController::class, 'deliveryRegistration'])
        ->name('delivery.registrationStore');
    Route::match(['get', 'post'], '/delivery/login', [DeliveryController::class, 'deliveryLogin'])
        ->name('delivery.login');
});

Route::middleware('guest:web')->group(function () {
    Route::prefix('user')->name('user.')->controller(CustomerController::class)->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register');
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
    });
});

// Protected routes with specific guards and middleware
Route::middleware(['auth:admin', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::resource('restaurants', RestaurantController::class);
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});

Route::middleware(['auth:restaurant', 'restaurant'])->group(function () {
    Route::prefix('restaurant')->name('restaurant.')->group(function () {
        Route::get('/dashboard', [RestaurantController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [RestaurantController::class, 'profile'])->name('profile');
        Route::post('/logout', [RestaurantController::class, 'logout'])->name('logout');
    });
});

Route::middleware(['auth:delivery', 'delivery'])->group(function () {
    Route::prefix('delivery')->name('delivery.')->group(function () {
        Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [DeliveryController::class, 'profile'])->name('profile');
        Route::post('/logout', [DeliveryController::class, 'logout'])->name('logout');
    });
});

Route::middleware(['auth:web', 'customer'])->group(function () {
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::get('/restaurants', [CustomerController::class, 'listRestaurants'])->name('restaurants');
        Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');
    });
});

require __DIR__.'/auth.php';