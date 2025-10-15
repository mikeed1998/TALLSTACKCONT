<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Header -->
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900">Orden #{{ $order->id }}</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Realizada el {{ $order->created_at->format('d/m/Y \\a \\l\\a\\s H:i') }}
                    </p>
                </div>

                <!-- Status & Total -->
                <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Estado del Pedido</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-2">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Total</h3>
                            <p class="text-2xl font-bold text-gray-900 mt-2">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dirección de Envío</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <p><strong>Nombre:</strong> {{ $order->shipping_address['name'] ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $order->shipping_address['email'] ?? 'N/A' }}</p>
                            <p><strong>Teléfono:</strong> {{ $order->shipping_address['phone'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p><strong>Dirección:</strong> {{ $order->shipping_address['address'] ?? 'N/A' }}</p>
                            <p><strong>Ciudad:</strong> {{ $order->shipping_address['city'] ?? 'N/A' }}</p>
                            <p><strong>Estado:</strong> {{ $order->shipping_address['state'] ?? 'N/A' }}</p>
                            <p><strong>Código Postal:</strong> {{ $order->shipping_address['zip_code'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Productos</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between py-4 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-center">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">Sin imagen</span>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($item->unit_price, 2) }}</p>
                                    <p class="text-sm text-gray-500">Subtotal: ${{ number_format($item->subtotal, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('orders.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-200">
                    ← Volver a Mis Pedidos
                </a>
            </div>
        </div>
    </div>
</x-app-layout>