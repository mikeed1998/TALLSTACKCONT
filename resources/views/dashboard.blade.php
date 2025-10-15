<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Órdenes Recientes</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ auth()->user()->orders()->count() }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-green-800 mb-2">Items en Carrito</h3>
                            <p class="text-3xl font-bold text-green-600">{{ auth()->user()->cart_items_count }}</p>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-purple-800 mb-2">Total Gastado</h3>
                            <p class="text-3xl font-bold text-purple-600">${{ number_format(auth()->user()->orders()->sum(\'total_amount\'), 2) }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <a href="{{ route("products") }}" 
                           class="block w-full bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg text-center transition duration-200">
                            Continuar Comprando
                        </a>
                        
                        <a href="{{ route("cart") }}" 
                           class="block w-full bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-lg text-center transition duration-200">
                            Ver Carrito
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>