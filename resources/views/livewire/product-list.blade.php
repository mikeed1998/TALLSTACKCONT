<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">Sin imagen</span>
                    </div>
                @endif
                
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                    
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl font-bold text-green-600">{{ $product->formatted_price }}</span>
                        <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                    </div>
                    
                    <button 
                        wire:click="addToCart({{ $product->id }})"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                        {{ $product->stock == 0 ? 'disabled' : '' }}
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ $product->stock > 0 ? 'Agregar al carrito' : 'Sin stock' }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>