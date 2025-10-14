<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Stack</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                ¡Stack Configurado Correctamente! 🎉
            </h1>
        </header>

        <main class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Laravel Card -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-blue-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Laravel 12</h3>
                    <p class="text-gray-600">Framework PHP backend funcionando correctamente.</p>
                </div>

                <!-- Livewire Card -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-orange-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Livewire</h3>
                    <p class="text-gray-600">Componentes dinámicos sin escribir JavaScript.</p>
                </div>

                <!-- Alpine.js + Tailwind Card -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-green-500 mb-4">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12m0 0h4m-4 0H7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Alpine.js + Tailwind</h3>
                    <p class="text-gray-600">Interactividad y diseño moderno.</p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="/demo" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold transition duration-200 inline-block">
                   Ver Demo Interactivo →
                </a>
            </div>

            <div class="mt-8 text-center text-gray-600">
                <p>Vite Dev Server: <code class="bg-gray-200 px-2 py-1 rounded">http://localhost:5173</code></p>
                <p class="mt-2">Laravel App: <code class="bg-gray-200 px-2 py-1 rounded">http://localhost:8000</code></p>
            </div>
        </main>
    </div>
    
    @livewireScripts
</body>
</html>