<?php

use App\Http\Controllers\ShopPageController;
use Illuminate\Support\Facades\Route;

Route::get('/store/{slug}', [ShopPageController::class, 'show'])
    ->name('shop.page')
    ->where('slug', '[a-zA-Z0-9_-]+');

Route::get('{any?}', function () {
    return view('application');
})->where('any', '^(?!api\/).*');