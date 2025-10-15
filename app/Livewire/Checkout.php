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
    public $isSuccessPage = false;
    public $successOrder = null;

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

        // Verificar si estamos en la página de éxito
        if (request()->has('payment_intent') && request('redirect_status') === 'succeeded') {
            $this->isSuccessPage = true;
            $this->handleSuccessPage();
            return;
        }

        // Flujo normal de checkout
        $this->loadCartData();
        
        if ($this->cartItems->isEmpty()) {
            return redirect()->route("cart");
        }

        $this->initializeStripe();
    }

    private function handleSuccessPage()
    {
        $paymentIntentId = request('payment_intent');
        
        \Log::info("=== MANEJANDO PÁGINA DE ÉXITO ===");
        \Log::info("Payment Intent: " . $paymentIntentId);
        
        $this->isProcessing = true;

        try {
            $order = $this->createOrderFromPayment($paymentIntentId);
            
            if ($order) {
                $this->successOrder = $order;
                \Log::info("Orden procesada exitosamente: #" . $order->id);
                
                // Redirigir a la vista de la orden después de 2 segundos
                // return redirect()->route('orders.show', $order->id);
            } else {
                $this->stripeError = "No se pudo crear la orden después del pago.";
                \Log::error("No se pudo crear la orden para payment intent: " . $paymentIntentId);
            }

        } catch (\Exception $e) {
            $this->stripeError = "Error procesando el pago: " . $e->getMessage();
            \Log::error("Error en handleSuccessPage: " . $e->getMessage());
        }

        $this->isProcessing = false;
    }

    private function createOrderFromPayment($paymentIntentId)
    {
        \Log::info("Creando orden desde payment: " . $paymentIntentId);
        
        return DB::transaction(function () use ($paymentIntentId) {
            // Verificar si ya existe una orden
            $existingOrder = Order::where('stripe_payment_intent_id', $paymentIntentId)->first();
            
            if ($existingOrder) {
                \Log::info("Orden existente encontrada: #" . $existingOrder->id);
                $existingOrder->update(['status' => 'paid']);
                return $existingOrder;
            }

            // Cargar datos del carrito actual
            $this->loadCartData();
            
            if ($this->cartItems->isEmpty()) {
                \Log::error("No hay items en el carrito para crear la orden");
                throw new \Exception("No hay items en el carrito");
            }

            \Log::info("Creando nueva orden con " . $this->cartItems->count() . " items");

            // Crear nueva orden
            $order = Order::create([
                "user_id" => auth()->id(),
                "stripe_payment_intent_id" => $paymentIntentId,
                "total_amount" => $this->total,
                "status" => "paid",
                "shipping_address" => $this->shippingAddress
            ]);

            \Log::info("Orden creada: #" . $order->id);

            // Crear items de la orden
            foreach ($this->cartItems as $cartItem) {
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $cartItem->product_id,
                    "quantity" => $cartItem->quantity,
                    "unit_price" => $cartItem->product->price
                ]);

                // Actualizar stock
                $cartItem->product->decrement("stock", $cartItem->quantity);
                
                \Log::info("Item creado: " . $cartItem->product->name . " x " . $cartItem->quantity);
            }

            // Vaciar carrito
            $deletedCount = auth()->user()->cartItems()->delete();
            \Log::info("Carrito vaciado. Items eliminados: " . $deletedCount);

            // Disparar evento para actualizar contador
            $this->dispatch('cart-count-updated');

            return $order;
        });
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
                "amount" => round($this->total * 100),
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

    // Método para probar sin Stripe
    public function testCreateOrder()
    {
        $this->validate();
        
        try {
            DB::transaction(function () {
                $order = Order::create([
                    "user_id" => auth()->id(),
                    "stripe_payment_intent_id" => "test_" . uniqid(),
                    "total_amount" => $this->total,
                    "status" => "paid",
                    "shipping_address" => $this->shippingAddress
                ]);

                \Log::info("Orden de prueba creada: #" . $order->id);

                foreach ($this->cartItems as $cartItem) {
                    OrderItem::create([
                        "order_id" => $order->id,
                        "product_id" => $cartItem->product_id,
                        "quantity" => $cartItem->quantity,
                        "unit_price" => $cartItem->product->price
                    ]);

                    $cartItem->product->decrement("stock", $cartItem->quantity);
                }

                // Vaciar carrito
                auth()->user()->cartItems()->delete();
                $this->dispatch('cart-count-updated');
                
                \Log::info("Carrito vaciado en prueba");
                
                session()->flash('success', 'Orden de prueba #' . $order->id . ' creada exitosamente!');
                return redirect()->route('orders.show', $order->id);
            });
        } catch (\Exception $e) {
            $this->stripeError = "Error: " . $e->getMessage();
            \Log::error("Test Order Error: " . $e->getMessage());
        }
    }

    public function redirectToOrder()
    {
        if ($this->successOrder) {
            return redirect()->route('orders.show', $this->successOrder->id);
        }
        return redirect()->route('orders.index');
    }

    public function render()
    {
        // Si estamos en la página de éxito, mostrar vista diferente
        if ($this->isSuccessPage) {
            return view('livewire.checkout-success')->layout('components.app-layout');
        }

        // Vista normal de checkout
        return view('livewire.checkout')->layout('components.app-layout');
    }
}