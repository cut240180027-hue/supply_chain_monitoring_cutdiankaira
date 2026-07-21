<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EconomicController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\RiskController;

// New Controllers
use App\Http\Controllers\CountryDashboardController;
use App\Http\Controllers\CountryComparisonController;
use App\Http\Controllers\VisualizationController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ApiController;

// ===== MAIN APPLICATION ROUTES =====

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/tracking', [TrackingController::class,'index'])->name('tracking.index');
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/economy', [EconomicController::class, 'index'])->name('economy.index');
Route::get('/currency', [ExchangeRateController::class, 'index'])->name('currency.index');

Route::resource('shipments', ShipmentController::class);

Route::get('/countries/sync', [CountryController::class, 'sync'])->name('countries.sync');
Route::resource('countries', CountryController::class);

Route::get('/ports/sync', [PortController::class, 'sync'])->name('ports.sync');
Route::resource('ports', PortController::class);

Route::resource('suppliers', SupplierController::class);

// Risk Score Routes
Route::get('/risk', [RiskController::class, 'index'])->name('risk.index');
Route::get('/risk/recalculate-all', [RiskController::class, 'recalculateAll'])->name('risk.recalculate-all');
Route::get('/risk/{shipment}', [RiskController::class, 'show'])->name('risk.show');
Route::get('/risk/{shipment}/recalculate', [RiskController::class, 'recalculate'])->name('risk.recalculate');
Route::post('/risk/calculate', [RiskController::class, 'calculate'])->name('risk.calculate');

// Notification Routes
Route::post('/notifications/read-all', [DashboardController::class, 'readAllNotifications'])->name('notifications.read-all');

// ===== NEW SCM PLATFORM FEATURES =====

Route::get('/country-dashboard', [CountryDashboardController::class, 'index'])->name('country-dashboard.index');
Route::get('/comparison', [CountryComparisonController::class, 'index'])->name('comparison.index');
Route::get('/visualizations', [VisualizationController::class, 'index'])->name('visualizations.index');

Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
Route::delete('/watchlist/{id}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

// ===== REST API ENDPOINTS =====
Route::prefix('api')->group(function () {
    Route::get('/countries', [ApiController::class, 'countries']);
    Route::get('/risk', [ApiController::class, 'risk']);
    Route::get('/ports', [ApiController::class, 'ports']);
    Route::get('/news', [ApiController::class, 'news']);
    Route::get('/currency', [ApiController::class, 'currency']);
});

// ===== ADMIN PANEL (Login Required) =====

// Public: Admin Login / Logout
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected: All admin pages require admin session
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('user.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('user.destroy');

    // Countries (read-only in admin — sync via /countries/sync)
    Route::get('/countries', [AdminController::class, 'countries'])->name('countries');

    // Ports (read-only in admin — sync via /ports/sync)
    Route::get('/ports', [AdminController::class, 'ports'])->name('ports');

    // Articles management
    Route::get('/articles', [AdminController::class, 'articles'])->name('articles');
    Route::post('/articles', [AdminController::class, 'storeArticle'])->name('article.store');
    Route::delete('/articles/{article}', [AdminController::class, 'destroyArticle'])->name('article.destroy');
});