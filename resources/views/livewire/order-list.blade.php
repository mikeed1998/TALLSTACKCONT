<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Mis Pedidos</h1>

            @if($orders->count() > 0)
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <li>
                                <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <p class="text-sm font-medium text-indigo-600 truncate">
                                                Orden #{{ $order->id }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <p class="text-lg font-bold text-gray-900">
                                                ${{ number_format($order->total_amount, 2) }}
                                            </p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $order->shipping_address['name'] ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            <p>
                                                {{ $order->items->count() }} {{ Str::plural('producto', $order->items->count()) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a 
                                            href="{{ route('orders.show', $order->id) }}" 
                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                                        >
                                            Ver detalles →
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No tienes pedidos aún</h3>
                    <p class="text-gray-500 mb-6">Realiza tu primera compra para ver tus pedidos aquí</p>
                    <a href="{{ route('products') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg transition duration-200">
                        Comenzar a Comprar
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>