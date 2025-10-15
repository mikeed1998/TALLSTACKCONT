<?php

use App\Livewire\ProductList;
use App\Livewire\ShoppingCart;
use App\Livewire\Checkout;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get("/", function () {
    return view("home");
})->name("home");

Route::get("/products", ProductList::class)->name("products");

// Rutas públicas
Route::get("/register", [RegisterController::class, "showRegistrationForm"])->name("register");
Route::post("/register", [RegisterController::class, "register"]);
Route::get("/login", [LoginController::class, "showLoginForm"])->name("login");
Route::post("/login", [LoginController::class, "login"]);

// Rutas de recuperación de contraseña
Route::get("/forgot-password", [ForgotPasswordController::class, "showLinkRequestForm"])->name("password.request");
Route::post("/forgot-password", [ForgotPasswordController::class, "sendResetLinkEmail"])->name("password.email");
Route::get("/reset-password/{token}", [ResetPasswordController::class, "showResetForm"])->name("password.reset");
Route::post("/reset-password", [ResetPasswordController::class, "reset"])->name("password.update");

// Demo route
Route::get("/demo", function () {
    return view("demo");
})->name("demo");

// Ruta para obtener el conteo del carrito
Route::get("/cart/count", function () {
    if (auth()->check()) {
        return response()->json([
            "count" => auth()->user()->cart_items_count
        ]);
    }
    return response()->json(["count" => 0]);
})->name("cart.count");

// Rutas protegidas
Route::middleware(["auth"])->group(function () {
    Route::get("/cart", ShoppingCart::class)->name("cart");
    Route::get("/checkout", Checkout::class)->name("checkout");
    Route::get("/orders/{order}", [OrderController::class, "show"])->name("orders.show");
    Route::get("/dashboard", function () {
        return view("dashboard");
    })->name("dashboard");
    
    Route::post("/logout", function () {
        Auth::logout();
        return redirect("/");
    })->name("logout");
});
