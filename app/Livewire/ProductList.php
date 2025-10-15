<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public function addToCart($productId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($productId);

        // Verificar stock
        if ($product->stock < 1) {
            $this->dispatch('notify', message: 'Producto sin stock', type: 'error');
            return;
        }

        $cartItem = auth()->user()->cartItems()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity < $product->stock) {
                $cartItem->increment('quantity');
            } else {
                $this->dispatch('notify', message: 'Stock máximo alcanzado', type: 'warning');
                return;
            }
        } else {
            auth()->user()->cartItems()->create([
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        // Disparar evento para actualizar el contador
        $this->dispatch('cart-count-updated');
        
        // Notificación
        $this->dispatch('notify', 
            message: 'Producto agregado al carrito', 
            type: 'success'
        );
    }

    public function render()
    {
        return view('livewire.product-list', [
            'products' => Product::active()->inStock()->paginate(8)
        ])->layout('components.app-layout');
    }
}
