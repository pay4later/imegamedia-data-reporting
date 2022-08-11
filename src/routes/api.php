<?php

use Illuminate\Support\Facades\Route;
use Imega\DataReporting\Http\Controllers\Api\OrdersController;

Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
