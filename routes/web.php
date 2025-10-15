<?php

use App\Livewire\ProductList;
use App\Livewire\ShoppingCart;
use App\Livewire\Checkout;
use App\Livewire\OrderList;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get("/", function () {
    return view("home");
})->name("home");

Route::get("/products", ProductList::class)->name("products");

Route::middleware(['guest'])->group(function () {
    Route::get("/register", [RegisterController::class, "showRegistrationForm"])->name("register");
    Route::post("/register", [RegisterController::class, "register"]);
    Route::get("/login", [LoginController::class, "showLoginForm"])->name("login");
    Route::post("/login", [LoginController::class, "login"]);

    // Rutas de recuperación de contraseña
    Route::get("/forgot-password", [ForgotPasswordController::class, "showLinkRequestForm"])->name("password.request");
    Route::post("/forgot-password", [ForgotPasswordController::class, "sendResetLinkEmail"])->name("password.email");
    Route::get("/reset-password/{token}", [ResetPasswordController::class, "showResetForm"])->name("password.reset");
    Route::post("/reset-password", [ResetPasswordController::class, "reset"])->name("password.update");
});

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

// Ruta de éxito de pago
Route::get("/checkout/success", function () {
    return redirect()->route("dashboard")->with("success", "¡Pago procesado exitosamente!");
})->name("checkout.success");

// Webhook de Stripe - SOLO POST
Route::post("/stripe/webhook", [StripeWebhookController::class, "handleWebhook"])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// En routes/web.php
Route::middleware(["auth"])->group(function () {
    Route::get("/cart", ShoppingCart::class)->name("cart");
    Route::get("/checkout", Checkout::class)->name("checkout");
    Route::get('/checkout/success', Checkout::class)->name('checkout.success');

        
    // Usar el componente Livewire para órdenes
    Route::get('/orders', \App\Livewire\OrderList::class)->name('orders.index');
    
    Route::get("/orders/{order}", [OrderController::class, "show"])->name("orders.show");
    Route::get("/dashboard", function () {
        return view("dashboard");
    })->name("dashboard");
    
    Route::post("/logout", function () {
        Auth::logout();
        return redirect("/");
    })->name("logout");

    Route::get('/user-status', function () {
        $user = auth()->user();
        return [
            'user_id' => $user->id,
            'cart_items_count' => $user->cartItems()->count(),
            'cart_items' => $user->cartItems()->with('product')->get()->map(function($item) {
                return [
                    'product' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ];
            }),
            'orders_count' => $user->orders()->count(),
            'orders' => $user->orders()->with('items')->get()->map(function($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total_amount,
                    'status' => $order->status,
                    'items_count' => $order->items->count()
                ];
            }),
            'cart_total' => $user->cart_total
        ];
    });
    
    // Crear orden de prueba
    Route::get('/test-order', function () {
        return app(\App\Livewire\Checkout::class)->testCreateOrder();
    });
});

// Webhook debe estar fuera del middleware auth
Route::post("/stripe/webhook", [StripeWebhookController::class, "handleWebhook"])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);