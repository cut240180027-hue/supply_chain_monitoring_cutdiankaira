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

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/tracking', [TrackingController::class,'index'])
    ->name('tracking.index');

Route::get('/weather', [WeatherController::class, 'index'])
    ->name('weather.index');

Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');

Route::get('/economy', [EconomicController::class, 'index'])
    ->name('economy.index');

Route::get('/currency', [ExchangeRateController::class, 'index'])
    ->name('currency.index');

Route::resource('shipments', ShipmentController::class);

Route::get('/countries/sync', [CountryController::class, 'sync'])
    ->name('countries.sync');
Route::resource('countries', CountryController::class);

Route::get('/ports/sync', [PortController::class, 'sync'])
    ->name('ports.sync');
Route::resource('ports', PortController::class);

Route::resource('suppliers', SupplierController::class);

// ===== Risk Score Routes =====
Route::get('/risk', [RiskController::class, 'index'])->name('risk.index');
Route::get('/risk/recalculate-all', [RiskController::class, 'recalculateAll'])->name('risk.recalculate-all');
Route::get('/risk/{shipment}', [RiskController::class, 'show'])->name('risk.show');
Route::get('/risk/{shipment}/recalculate', [RiskController::class, 'recalculate'])->name('risk.recalculate');
Route::post('/risk/calculate', [RiskController::class, 'calculate'])->name('risk.calculate');

// ===== Notification Routes =====
Route::post('/notifications/read-all', [DashboardController::class, 'readAllNotifications'])->name('notifications.read-all');