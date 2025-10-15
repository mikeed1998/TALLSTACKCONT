<div>
    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            
            @if($isProcessing)
                <!-- Mostrar loading mientras procesa -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">Procesando tu orden...</h1>
                    <p class="text-gray-600 mb-6">Estamos creando tu orden y vaciando el carrito.</p>
                </div>

            @elseif($successOrder)
                <!-- Mostrar éxito -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">¡Pago Exitoso!</h1>
                    <p class="text-gray-600 mb-6">Tu orden #{{ $successOrder->id }} ha sido creada exitosamente.</p>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-sm text-gray-600 mb-2">ID de Transacción:</p>
                        <p class="text-sm font-mono text-gray-800 break-all">{{ $successOrder->stripe_payment_intent_id }}</p>
                    </div>

                    <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                        <button 
                            wire:click="redirectToOrder"
                            class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg transition duration-200 font-medium"
                        >
                            Ver Mi Orden
                        </button>
                        <a href="{{ route('products') }}" 
                           class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-lg transition duration-200 font-medium block">
                            Seguir Comprando
                        </a>
                    </div>
                </div>

            @elseif($stripeError)
                <!-- Mostrar error -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">Error en el Procesamiento</h1>
                    <p class="text-gray-600 mb-6">{{ $stripeError }}</p>
                    
                    <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                        <a href="{{ route('cart') }}" 
                           class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg transition duration-200 font-medium block">
                            Volver al Carrito
                        </a>
                        <a href="{{ route('products') }}" 
                           class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition duration-200 font-medium block">
                            Seguir Comprando
                        </a>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>