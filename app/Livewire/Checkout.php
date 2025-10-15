<?php
// app/Livewire/Checkout.php
namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class Checkout extends Component
{
    public $cartItems;
    public $total;
    public $shippingAddress = [
        'name' => '',
        'email' => '',
        'phone' => '',
        'address' => '',
        'city' => '',
        'state' => '',
        'zip_code' => '',
        'country' => ''
    ];
    public $clientSecret;
    public $paymentIntentId;

    protected $rules = [
        'shippingAddress.name' => 'required|string|max:255',
        'shippingAddress.email' => 'required|email',
        'shippingAddress.phone' => 'required|string|max:20',
        'shippingAddress.address' => 'required|string|max:255',
        'shippingAddress.city' => 'required|string|max:100',
        'shippingAddress.state' => 'required|string|max:100',
        'shippingAddress.zip_code' => 'required|string|max:20',
        'shippingAddress.country' => 'required|string|max:100'
    ];

    public function mount()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $this->cartItems = auth()->user()->cartItems()->with('product')->get();
        $this->total = auth()->user()->cart_total;

        if ($this->cartItems->isEmpty()) {
            return redirect()->route('cart');
        }

        // Crear Payment Intent de Stripe
        $paymentIntent = PaymentIntent::create([
            'amount' => $this->total * 100, // Stripe usa centavos
            'currency' => 'usd',
            'metadata' => [
                'user_id' => auth()->id()
            ]
        ]);

        $this->clientSecret = $paymentIntent->client_secret;
        $this->paymentIntentId = $paymentIntent->id;
    }

    public function processPayment()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // Crear orden
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'stripe_payment_intent_id' => $this->paymentIntentId,
                    'total_amount' => $this->total,
                    'status' => 'paid',
                    'shipping_address' => $this->shippingAddress
                ]);

                // Crear items de la orden
                foreach ($this->cartItems as $cartItem) {
                    $order->items()->create([
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'unit_price' => $cartItem->product->price
                    ]);

                    // Actualizar stock
                    $cartItem->product->decrement('stock', $cartItem->quantity);
                }

                // Vaciar carrito
                auth()->user()->cartItems()->delete();
            });

            session()->flash('success', '¡Pago procesado exitosamente! Tu orden ha sido creada.');
            return redirect()->route('orders.show', ['order' => $order->id]);

        } catch (\Exception $e) {
            session()->flash('error', 'Hubo un error procesando tu pago. Por favor intenta nuevamente.');
            return redirect()->route('checkout');
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}