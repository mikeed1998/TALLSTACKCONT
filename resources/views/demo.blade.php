<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Stack Completo</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                Demo Stack Completo
            </h1>
            <p class="text-gray-600">
                Laravel 12 + Livewire + Alpine.js + Tailwind CSS
            </p>
            <nav class="mt-4">
                <a href="/" class="text-blue-500 hover:text-blue-700 mx-2">Inicio</a>
                <a href="/demo" class="text-blue-500 hover:text-blue-700 mx-2">Demo</a>
            </nav>
        </header>

        <main>
            <livewire:counter />
        </main>
    </div>
    
    @livewireScripts
</body>
</html>