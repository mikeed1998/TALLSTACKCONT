<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso - Mi Tienda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <!-- Si app-layout tiene una estructura específica, replica lo esencial -->
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                            Mi Tienda
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Inicio</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-900">Pedidos</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <div class="container mx-auto px-4 py-8">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">¡Pago Exitoso!</h1>
                        <p class="text-gray-600 mb-6 text-lg">Tu pedido ha sido procesado correctamente.</p>
                        
                        @if(request()->get('payment_intent'))
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <p class="text-sm text-gray-600 mb-2">ID de Transacción:</p>
                            <p class="text-sm font-mono text-gray-800 break-all">{{ request()->get('payment_intent') }}</p>
                        </div>
                        @endif

                        <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                            <a href="{{ route('home') }}" 
                               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                                Continuar Comprando
                            </a>
                            <a href="{{ route('orders.index') }}" 
                               class="inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                                Ver Mis Pedidos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>