<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\ServiceProviderWebController;
use App\Http\Controllers\Web\CategoryWebController;

// Halaman utama (landing page) - Public access
Route::get('/', function () {
    $serviceProviders = \App\Models\ServiceProvider::with('category')
        ->latest()
        ->take(6)
        ->get();
    $categories = \App\Models\Category::all();
    return view('welcome', compact('serviceProviders', 'categories'));
})->name('home');

// Authentication routes (Login jwt only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthWebController::class, 'login']);
    Route::get('/register', [AuthWebController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthWebController::class, 'register']);
});

// Protected routes (require authentication)
Route::middleware('auth:web')->group(function () {
    // Auth
    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

    // Dashboard - accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Service Providers - View only for regular users, full CRUD for admin
    Route::prefix('service-providers')->name('service-providers.')->group(function () {
        // CRUD routes - admin only (harus di atas route dengan parameter)
        Route::middleware('admin')->group(function () {
            Route::get('/create', [ServiceProviderWebController::class, 'create'])->name('create');
            Route::post('/', [ServiceProviderWebController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [ServiceProviderWebController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [ServiceProviderWebController::class, 'update'])->name('update');
            Route::delete('/{uuid}', [ServiceProviderWebController::class, 'destroy'])->name('destroy');
        });
        
        // View routes - accessible by all authenticated users
        Route::get('/', [ServiceProviderWebController::class, 'index'])->name('index');
        Route::get('/{uuid}', [ServiceProviderWebController::class, 'show'])->name('show');
    });

    // Categories - View only for regular users, full CRUD for admin
    Route::prefix('categories')->name('categories.')->group(function () {
        // CRUD routes - admin only (harus di atas route dengan parameter)
        Route::middleware('admin')->group(function () {
            Route::get('/create', [CategoryWebController::class, 'create'])->name('create');
            Route::post('/', [CategoryWebController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [CategoryWebController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [CategoryWebController::class, 'update'])->name('update');
            Route::delete('/{uuid}', [CategoryWebController::class, 'destroy'])->name('destroy');
        });
        
        // View routes - accessible by all authenticated users
        Route::get('/', [CategoryWebController::class, 'index'])->name('index');
        Route::get('/{uuid}', [CategoryWebController::class, 'show'])->name('show');
    });

    // Bookings - User can create and view their own, Admin can view all
    Route::prefix('bookings')->name('bookings.')->group(function () {
        // User routes - create booking and view own bookings
        Route::get('/create', [\App\Http\Controllers\Web\BookingController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Web\BookingController::class, 'store'])->name('store');
        Route::get('/my-bookings', [\App\Http\Controllers\Web\BookingController::class, 'myBookings'])->name('my');
        Route::post('/{booking}/cancel', [\App\Http\Controllers\Web\BookingController::class, 'cancel'])->name('cancel');
        
        // Admin routes - view all bookings and update status
        Route::middleware('admin')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\BookingController::class, 'index'])->name('index');
            Route::post('/{booking}/status', [\App\Http\Controllers\Web\BookingController::class, 'updateStatus'])->name('update-status');
        });

        // Shared routes - view booking detail (admin or owner)
        Route::get('/{booking}', [\App\Http\Controllers\Web\BookingController::class, 'show'])->name('show');
    });
});
