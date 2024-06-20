<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\TravelPackageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

// Admin routes
Route::group(['middleware' => ['is_admin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Booking routes
    Route::resource('bookings', BookingController::class)->only(['index', 'destroy']);

    // Travel packages routes
    Route::resource('travel_packages', TravelPackageController::class)->except('show');
    Route::resource('travel_packages.galleries', GalleryController::class)->except(['create', 'index', 'show']);

    // Category routes
    Route::resource('categories', CategoryController::class)->except('show');

    // Blog routes
    Route::resource('blogs', BlogController::class)->except('show');

    // Profile routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Public routes
Route::group([], function() {
    Route::get('/', [HomeController::class, 'index'])->name('homepage');

    // Travel package routes
    Route::get('travel-packages', [TravelPackageController::class, 'index'])->name('travel_package.index');
    Route::get('travel-packages/{travel_package:slug}', [TravelPackageController::class, 'show'])->name('travel_package.show');

    // Blog routes
    Route::get('blogs', [BlogController::class, 'index'])->name('blog.index');
    Route::get('blogs/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('blogs/category/{category:slug}', [BlogController::class, 'category'])->name('blog.category');

    // Contact page
    Route::get('contact', function() {
        return view('contact');
    })->name('contact');

    // Booking store route
    Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
});
