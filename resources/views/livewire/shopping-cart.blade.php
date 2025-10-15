<!-- resources/views/livewire/shopping-cart.blade.php -->
<div>
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Tu Carrito de Compras</h2>
        </div>

        @if($cartItems->count() > 0)
            <div class="p-6">
                @foreach($cartItems as $item)
                    <div class="flex items-center space-x-4 py-4 border-b last:border-b-0">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}"
                                 class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Sin imagen</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                            <p class="text-gray-600">{{ $item->product->formatted_price }}</p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <button 
                                wire:click="decrement({{ $item->id }})"
                                class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300"
                            >
                                -
                            </button>
                            <span class="w-8 text-center">{{ $item->quantity }}</span>
                            <button 
                                wire:click="increment({{ $item->id }})"
                                class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300"
                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                            >
                                +
                            </button>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-semibold">{{ $item->formatted_subtotal }}</p>
                            <button 
                                wire:click="remove({{ $item->id }})"
                                class="text-red-500 hover:text-red-700 text-sm"
                            >
                                Eliminar
                            </button>
                        </div>
                    </div>
                @endforeach
                
                <div class="mt-6 pt-6 border-t">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-xl font-bold">Total:</span>
                        <span class="text-xl font-bold text-green-600">${{ number_format($cartTotal, 2) }}</span>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button 
                            wire:click="clearCart"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition duration-200"
                        >
                            Vaciar Carrito
                        </button>
                        <button 
                            wire:click="checkout"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-lg transition duration-200"
                        >
                            Proceder al Pago
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Tu carrito está vacío</h3>
                <p class="text-gray-500 mb-4">Agrega algunos productos para comenzar a comprar</p>
                <a href="{{ route('products') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg transition duration-200">
                    Ver Productos
                </a>
            </div>
        @endif
    </div>
</div>