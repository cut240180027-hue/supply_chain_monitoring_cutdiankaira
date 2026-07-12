<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WeatherController;

Route::get('/', [DashboardController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/tracking', [TrackingController::class,'index'])
    ->name('tracking.index');

Route::get('/weather', [WeatherController::class, 'index'])
    ->name('weather.index');

Route::resource('shipments', ShipmentController::class);

Route::get('/countries/sync', [CountryController::class, 'sync'])
    ->name('countries.sync');

Route::resource('countries', CountryController::class);

Route::resource('ports', PortController::class);

Route::resource('suppliers', SupplierController::class);