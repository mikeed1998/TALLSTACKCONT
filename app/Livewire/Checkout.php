<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class Checkout extends Component
{
    public $cartItems;
    public $total;
    public $shippingAddress = [
        "name" => "",
        "email" => "",
        "phone" => "",
        "address" => "",
        "city" => "",
        "state" => "",
        "zip_code" => "",
        "country" => "US"
    ];
    public $clientSecret;
    public $paymentIntentId;
    public $stripeError;
    public $isProcessing = false;

    protected $rules = [
        "shippingAddress.name" => "required|string|max:255",
        "shippingAddress.email" => "required|email",
        "shippingAddress.phone" => "required|string|max:20",
        "shippingAddress.address" => "required|string|max:255",
        "shippingAddress.city" => "required|string|max:100",
        "shippingAddress.state" => "required|string|max:100",
        "shippingAddress.zip_code" => "required|string|max:20",
        "shippingAddress.country" => "required|string|max:100"
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route("login");
        }

        $this->loadCartData();
        
        if ($this->cartItems->isEmpty()) {
            return redirect()->route("cart");
        }

        $this->initializeStripe();
    }

    private function loadCartData()
    {
        $this->cartItems = auth()->user()->cartItems()->with("product")->get();
        $this->total = $this->cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });
    }

    private function initializeStripe()
    {
        try {
            Stripe::setApiKey(config("services.stripe.secret"));

            $paymentIntent = PaymentIntent::create([
                "amount" => round($this->total * 100), // Stripe usa centavos
                "currency" => "usd",
                "metadata" => [
                    "user_id" => auth()->id(),
                    "total" => $this->total
                ],
                "automatic_payment_methods" => ["enabled" => true],
            ]);

            $this->clientSecret = $paymentIntent->client_secret;
            $this->paymentIntentId = $paymentIntent->id;

        } catch (\Exception $e) {
            $this->stripeError = "Error: " . $e->getMessage();
            \Log::error("Stripe Error: " . $e->getMessage());
        }
    }

    // Método para crear orden después de pago exitoso
    public function processSuccessfulPayment()
    {
        $this->isProcessing = true;
        
        try {
            DB::transaction(function () {
                // Verificar si ya existe una orden para este payment intent
                $existingOrder = Order::where('stripe_payment_intent_id', $this->paymentIntentId)->first();
                
                if ($existingOrder) {
                    // Si ya existe, solo actualizar status
                    $existingOrder->update(['status' => 'paid']);
                    $order = $existingOrder;
                    \Log::info("Orden existente actualizada: #" . $order->id);
                } else {
                    // CREAR NUEVA ORDEN
                    $order = Order::create([
                        "user_id" => auth()->id(),
                        "stripe_payment_intent_id" => $this->paymentIntentId,
                        "total_amount" => $this->total,
                        "status" => "paid",
                        "shipping_address" => $this->shippingAddress
                    ]);

                    \Log::info("Nueva orden creada: #" . $order->id);

                    // CREAR ITEMS DE LA ORDEN
                    foreach ($this->cartItems as $cartItem) {
                        OrderItem::create([
                            "order_id" => $order->id,
                            "product_id" => $cartItem->product_id,
                            "quantity" => $cartItem->quantity,
                            "unit_price" => $cartItem->product->price
                        ]);

                        \Log::info("Item creado para orden #{$order->id}: {$cartItem->product->name} x {$cartItem->quantity}");

                        // ACTUALIZAR STOCK
                        $cartItem->product->decrement("stock", $cartItem->quantity);
                        \Log::info("Stock actualizado: {$cartItem->product->name} -{$cartItem->quantity}");
                    }
                }

                // VACIAR EL CARRITO - ESTO ES CLAVE
                $cartItemsCount = auth()->user()->cartItems()->count();
                auth()->user()->cartItems()->delete();
                \Log::info("Carrito vaciado. Items eliminados: " . $cartItemsCount);

                // Disparar evento para actualizar el contador
                $this->dispatch('cart-count-updated');
                
                \Log::info("Proceso completado para usuario: " . auth()->id());

                // Redirigir a la orden
                session()->flash('success', '¡Pago exitoso! Orden #' . $order->id . ' creada.');
                return redirect()->route("orders.show", $order->id);
            });

        } catch (\Exception $e) {
            $this->isProcessing = false;
            $this->stripeError = "Error procesando la orden: " . $e->getMessage();
            \Log::error("Order Processing Error: " . $e->getMessage());
            
            session()->flash('error', 'Error al procesar la orden: ' . $e->getMessage());
            return redirect()->route('cart');
        }
    }

    // Método para probar sin Stripe - VERSIÓN CORREGIDA
    public function testCreateOrder()
    {
        // Validar primero
        $this->validate();
        
        \Log::info("=== INICIANDO TEST CREATE ORDER ===");
        \Log::info("Usuario: " . auth()->id());
        \Log::info("Items en carrito: " . $this->cartItems->count());
        \Log::info("Total: " . $this->total);

        try {
            $result = DB::transaction(function () {
                \Log::info("Creando orden...");
                
                // CREAR LA ORDEN
                $order = Order::create([
                    "user_id" => auth()->id(),
                    "stripe_payment_intent_id" => "test_" . uniqid(),
                    "total_amount" => $this->total,
                    "status" => "paid",
                    "shipping_address" => $this->shippingAddress
                ]);

                \Log::info("Orden creada: #" . $order->id);

                // CREAR ITEMS DE LA ORDEN
                foreach ($this->cartItems as $cartItem) {
                    \Log::info("Creando item: " . $cartItem->product->name . " x " . $cartItem->quantity);
                    
                    OrderItem::create([
                        "order_id" => $order->id,
                        "product_id" => $cartItem->product_id,
                        "quantity" => $cartItem->quantity,
                        "unit_price" => $cartItem->product->price
                    ]);

                    // ACTUALIZAR STOCK
                    $cartItem->product->decrement("stock", $cartItem->quantity);
                    \Log::info("Stock actualizado: " . $cartItem->product->name . " -" . $cartItem->quantity);
                }

                // VACIAR CARRITO
                $deletedCount = auth()->user()->cartItems()->delete();
                \Log::info("Carrito vaciado. Items eliminados: " . $deletedCount);

                // Disparar evento
                $this->dispatch('cart-count-updated');
                
                \Log::info("=== TEST COMPLETADO EXITOSAMENTE ===");

                return $order;
            });

            // Redirigir después de la transacción
            session()->flash('success', 'Orden de prueba #' . $result->id . ' creada exitosamente!');
            return redirect()->route('orders.show', $result->id);

        } catch (\Exception $e) {
            \Log::error("ERROR en testCreateOrder: " . $e->getMessage());
            \Log::error("Trace: " . $e->getTraceAsString());
            
            $this->stripeError = "Error: " . $e->getMessage();
            session()->flash('error', 'Error creando orden de prueba: ' . $e->getMessage());
            return redirect()->route('checkout');
        }
    }

    public function render()
    {
        return view('livewire.checkout')->layout('components.app-layout');
    }
}