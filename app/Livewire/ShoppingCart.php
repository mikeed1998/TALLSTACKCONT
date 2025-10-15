<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;

class ShoppingCart extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function increment($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        if ($cartItem->product->stock > $cartItem->quantity) {
            $cartItem->increment('quantity');
            $this->dispatch('cart-count-updated');
        }
    }

    public function decrement($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } else {
            $this->remove($cartItemId);
        }
        $this->dispatch('cart-count-updated');
    }

    public function remove($cartItemId)
    {
        CartItem::findOrFail($cartItemId)->delete();
        $this->dispatch('cart-count-updated');
    }

    public function clearCart()
    {
        auth()->user()->cartItems()->delete();
        $this->dispatch('cart-count-updated');
    }

    public function checkout()
    {
        if (auth()->user()->cartItems->isEmpty()) {
            $this->dispatch('notify', message: 'Tu carrito está vacío', type: 'error');
            return;
        }

        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.shopping-cart', [
            'cartItems' => auth()->user()->cartItems()->with('product')->get(),
            'cartTotal' => auth()->user()->cart_total
        ])->layout('components.app-layout');
    }
}