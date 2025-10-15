<div>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Finalizar Compra</h1>

        @if($stripeError)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-red-800">
                    <strong>Error:</strong> {{ $stripeError }}
                </p>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Información de Envío -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Información de Envío</h2>
                <div class="space-y-4">
                    <!-- Tus campos de dirección existentes (los mismos que tenías) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                        <input type="text" wire:model="shippingAddress.name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error("shippingAddress.name") 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" wire:model="shippingAddress.email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error("shippingAddress.email") 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                        <input type="text" wire:model="shippingAddress.phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error("shippingAddress.phone") 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                        <input type="text" wire:model="shippingAddress.address" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        @error("shippingAddress.address") 
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                            <input type="text" wire:model="shippingAddress.city" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error("shippingAddress.city") 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                            <input type="text" wire:model="shippingAddress.state" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error("shippingAddress.state") 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código Postal *</label>
                            <input type="text" wire:model="shippingAddress.zip_code" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error("shippingAddress.zip_code") 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">País *</label>
                            <input type="text" wire:model="shippingAddress.country" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="US" readonly>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Resumen del Pedido y Pago -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Resumen del Pedido</h2>
                
                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-600">Cantidad: {{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 mb-6">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Stripe Payment Form -->
                @if($clientSecret && !$stripeError)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-4">Información de Pago</h3>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-blue-800 text-sm">
                                <strong>Modo Sandbox:</strong> Usa tarjeta de prueba: 
                                <code class="bg-blue-100 px-1 rounded">4242 4242 4242 4242</code>
                            </p>
                            <p class="text-blue-700 text-xs mt-1">
                                Fecha: 12/34 | CVC: 567
                            </p>
                        </div>

                        <div id="payment-form">
                            <div id="payment-element" class="mb-4">
                                <!-- Stripe Elements será insertado aquí -->
                            </div>
                            
                            <button 
                                id="submit-button"
                                type="button"
                                class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-lg transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed font-medium"
                                onclick="handlePayment()"
                                @if($isProcessing) disabled @endif
                            >
                                <span id="button-text">
                                    @if($isProcessing)
                                        <span class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Procesando...
                                        </span>
                                    @else
                                        Pagar ${{ number_format($total, 2) }}
                                    @endif
                                </span>
                            </button>
                            
                            <div id="payment-message" class="hidden mt-4 p-3 rounded-lg text-sm"></div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800">
                            Error inicializando el sistema de pagos. Por favor intenta más tarde.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Agrega esto DESPUÉS del formulario de pago -->
<div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
    <h3 class="text-lg font-semibold text-blue-800 mb-2">Prueba de Orden</h3>
    <p class="text-blue-700 text-sm mb-4">
        Usa este botón para probar la creación de órdenes sin procesar pago con Stripe.
    </p>
    
    <button 
        wire:click="testCreateOrder"
        wire:loading.attr="disabled"
        class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-200 disabled:bg-gray-400"
    >
        <span wire:loading.remove>Probar Creación de Orden</span>
        <span wire:loading>
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creando Orden...
        </span>
    </button>
    
    @error('shippingAddress.*')
        <p class="text-red-500 text-sm mt-2">
            Completa todos los campos de envío primero
        </p>
    @enderror
</div>

    <!-- Al final del archivo, después del form -->
    @if($clientSecret && !$stripeError)
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            
            const elements = stripe.elements({
                clientSecret: "{{ $clientSecret }}",
                appearance: { theme: 'stripe' }
            });

            const paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');

            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                
                if (!validateForm()) {
                    showMessage('Por favor completa todos los campos requeridos', 'error');
                    return;
                }

                submitButton.disabled = true;
                buttonText.innerHTML = `
                    <span class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Procesando pago...
                    </span>
                `;

                try {
                    const { error } = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: "{{ route('checkout.success') }}?payment_intent={{ $paymentIntentId }}",
                        },
                    });

                    if (error) {
                        showMessage(error.message, 'error');
                        submitButton.disabled = false;
                        buttonText.textContent = 'Pagar ${{ number_format($total, 2) }}';
                    }
                    // Si es exitoso, Stripe redirige automáticamente
                } catch (error) {
                    showMessage('Error: ' + error.message, 'error');
                    submitButton.disabled = false;
                    buttonText.textContent = 'Pagar ${{ number_format($total, 2) }}';
                }
            });

            function showMessage(message, type) {
                const messageElement = document.getElementById('payment-message');
                messageElement.textContent = message;
                messageElement.className = 'mt-4 p-3 rounded-lg text-sm ' + 
                    (type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700');
                messageElement.classList.remove('hidden');
                
                setTimeout(() => {
                    messageElement.classList.add('hidden');
                }, 5000);
            }

            function validateForm() {
                const fields = [
                    "shippingAddress.name",
                    "shippingAddress.email",
                    "shippingAddress.phone", 
                    "shippingAddress.address",
                    "shippingAddress.city",
                    "shippingAddress.state",
                    "shippingAddress.zip_code"
                ];
                
                let isValid = true;
                
                fields.forEach(field => {
                    const selector = '[wire\\:model="' + field + '"]';
                    const element = document.querySelector(selector);
                    if (!element || !element.value.trim()) {
                        isValid = false;
                        element.classList.add('border-red-500');
                    } else {
                        element.classList.remove('border-red-500');
                    }
                });
                
                return isValid;
            }

            // Verificar si estamos en la página de éxito
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                const paymentIntent = urlParams.get('payment_intent');
                const redirectStatus = urlParams.get('redirect_status');
                
                if (paymentIntent && redirectStatus === 'succeeded') {
                    console.log('Pago exitoso detectado, procesando orden...');
                    // Llamar a Livewire para procesar el pago exitoso
                    @this.call('processSuccessfulPayment');
                }
            });
        </script>
    @endif
</div>