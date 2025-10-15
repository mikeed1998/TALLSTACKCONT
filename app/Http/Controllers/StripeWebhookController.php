<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook error: Invalid payload');
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook error: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSucceeded($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;
            default:
                Log::info('Received unknown event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentSucceeded($paymentIntent)
    {
        try {
            DB::transaction(function () use ($paymentIntent) {
                // Buscar si ya existe una orden con este payment_intent
                $existingOrder = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
                
                if (!$existingOrder) {
                    // Aquí deberías tener la lógica para crear la orden
                    // y vaciar el carrito basado en los metadata que enviaste
                    $userId = $paymentIntent->metadata->user_id ?? null;
                    
                    if ($userId) {
                        $user = User::find($userId);
                        if ($user) {
                            // Vaciar el carrito del usuario
                            $user->cartItems()->delete();
                            Log::info("Carrito vaciado para usuario: {$userId} después de pago exitoso");
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error processing successful payment: ' . $e->getMessage());
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        Log::info('Payment failed: ' . $paymentIntent->id);
    }
}