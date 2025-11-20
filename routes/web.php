<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// =======================
// PROFILE (auth required)
// =======================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =======================
// STORE – public
// =======================
Route::get('/store', [StoreController::class, 'index'])->name('store.index');


// =======================
// CART + CHECKOUT
// =======================

// 🔹 KOSÁR DARABSZÁM – shop.js miatt **PUBLIKUS**
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// 🔹 TÖBBI KOSÁRMŰVELET – csak belépve
Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

    Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');

    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
});


// =======================
// ADVICE – public
// =======================
Route::get('/advice', [AdviceController::class, 'index'])->name('advice.index');
Route::post('/advice/bmi', [AdviceController::class, 'calculateBMI'])->name('advice.bmi');

require __DIR__.'/auth.php';


// =======================
// GOOGLE LOGIN
// =======================
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');


// =======================
// FACEBOOK LOGIN
// =======================
Route::get('/auth/facebook/redirect', [FacebookController::class, 'redirect'])
    ->name('facebook.redirect');

Route::get('/auth/facebook/callback', [FacebookController::class, 'callback'])
    ->name('facebook.callback');


// =======================
// ORDERS (auth required)
// =======================
Route::middleware(['auth'])->group(function () {
    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
});
