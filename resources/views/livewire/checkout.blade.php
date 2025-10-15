<!-- resources/views/livewire/checkout.blade.php -->
<div>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Finalizar Compra</h1>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Información de Envío -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Información de Envío</h2>
                <form wire:submit.prevent="processPayment">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                            <input type="text" wire:model="shippingAddress.name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('shippingAddress.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model="shippingAddress.email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('shippingAddress.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" wire:model="shippingAddress.phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('shippingAddress.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <input type="text" wire:model="shippingAddress.address" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('shippingAddress.address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                                <input type="text" wire:model="shippingAddress.city" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @error('shippingAddress.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                <input type="text" wire:model="shippingAddress.state" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @error('shippingAddress.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Código Postal</label>
                                <input type="text" wire:model="shippingAddress.zip_code" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @error('shippingAddress.zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
                                <input type="text" wire:model="shippingAddress.country" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @error('shippingAddress.country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Resumen del Pedido -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Resumen del Pedido</h2>
                
                <div class="space-y-3 mb-4">
                    @foreach($cartItems as $item)
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-600">Cantidad: {{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold">{{ $item->formatted_subtotal }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Stripe Elements -->
                <div class="mt-6">
                    <div id="card-element" class="p-3 border border-gray-300 rounded-lg"></div>
                    <div id="card-errors" class="text-red-500 mt-2"></div>
                    
                    <button 
                        wire:click="processPayment"
                        id="submit-button"
                        class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-lg mt-4 transition duration-200"
                    >
                        Pagar ${{ number_format($total, 2) }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        cardElement.on('change', ({error}) => {
            const displayError = document.getElementById('card-errors');
            if (error) {
                displayError.textContent = error.message;
            } else {
                displayError.textContent = '';
            }
        });

        document.getElementById('submit-button').addEventListener('click', async () => {
            const {paymentIntent, error} = await stripe.confirmCardPayment(
                '{{ $clientSecret }}', {
                    payment_method: {
                        card: cardElement,
                    }
                }
            );

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                // El pago fue exitoso
                @this.processPayment();
            }
        });
    </script>
    @endpush
</div>