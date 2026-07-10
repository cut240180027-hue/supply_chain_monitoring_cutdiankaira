<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard.index')->name('dashboard');

Route::view('/shipment', 'shipment.index')->name('shipment.index');
Route::view('/tracking', 'tracking.index')->name('tracking.index');
Route::view('/weather', 'weather.index')->name('weather.index');
Route::view('/currency', 'currency.index')->name('currency.index');
Route::view('/countries', 'countries.index')->name('countries.index');
Route::view('/economy', 'economy.index')->name('economy.index');
Route::view('/news', 'news.index')->name('news.index');
Route::view('/risk', 'risk.index')->name('risk.index');