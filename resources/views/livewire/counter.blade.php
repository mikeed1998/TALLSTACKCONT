<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-8" 
     x-data="{ showHelp: false }">
    
    <!-- Alpine.js Example - Toggle Help -->
    <div class="mb-6">
        <button 
            @click="showHelp = !showHelp"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200"
        >
            <span x-show="!showHelp">¿Necesitas ayuda?</span>
            <span x-show="showHelp" x-cloak>Ocultar ayuda</span>
        </button>
        
        <div x-show="showHelp" x-cloak class="mt-2 p-4 bg-blue-50 rounded-lg">
            <p class="text-blue-800">Este componente demuestra:</p>
            <ul class="list-disc list-inside text-blue-700 mt-2">
                <li><strong>Livewire:</strong> Contador y lista interactiva</li>
                <li><strong>Alpine.js:</strong> Toggle de ayuda y animaciones</li>
                <li><strong>Tailwind CSS:</strong> Estilos y diseño responsivo</li>
            </ul>
        </div>
    </div>

    <!-- Livewire Counter Example -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Contador Livewire</h2>
        
        <div class="flex items-center justify-center space-x-4 mb-4">
            <button 
                wire:click="decrement"
                type="button"
                class="bg-red-500 hover:bg-red-600 text-white w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold transition duration-200"
            >
                -
            </button>
            
            <span class="text-3xl font-bold text-gray-800 min-w-20 text-center">
                {{ $count }}
            </span>
            
            <button 
                wire:click="increment"
                type="button"
                class="bg-green-500 hover:bg-green-600 text-white w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold transition duration-200"
            >
                +
            </button>
        </div>
        
        <p class="text-center text-gray-600">
            El contador está en: <span class="font-semibold">{{ $count }}</span>
        </p>
    </div>

    <!-- Livewire List Example -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Lista de Items</h2>
        
        <!-- Add Item Form -->
        <div class="flex space-x-2 mb-4">
            <input 
                type="text" 
                wire:model.live="name"
                wire:keydown.enter="addItem"
                placeholder="Escribe un item y presiona Enter..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <button 
                wire:click="addItem"
                type="button"
                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200"
            >
                Agregar
            </button>
        </div>

        <!-- Items List -->
        <div class="space-y-2">
            @forelse($items as $index => $item)
                <div 
                    class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200 shadow-sm"
                    x-data
                >
                    <span class="text-gray-800">{{ $item }}</span>
                    <button 
                        wire:click="removeItem({{ $index }})"
                        type="button"
                        class="text-red-500 hover:text-red-700 transition duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    No hay items en la lista. ¡Agrega algunos!
                </div>
            @endforelse
        </div>

        <!-- Stats -->
        <div class="mt-4 text-sm text-gray-600 flex justify-between">
            <p>Total de items: <span class="font-semibold">{{ count($items) }}</span></p>
            @if(count($items) > 0)
                <button 
                    wire:click="$set('items', [])"
                    type="button"
                    class="text-red-500 hover:text-red-700 text-sm"
                >
                    Limpiar lista
                </button>
            @endif
        </div>
    </div>

    <!-- Alpine.js Interactive Element -->
    <div class="mt-6 text-center">
        <div x-data="{ rotation: 0 }">
            <button 
                @click="rotation += 45"
                type="button"
                class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg transition duration-200 transform"
                :style="`transform: rotate(${rotation}deg)`"
            >
                ¡Hazme girar con Alpine.js!
            </button>
            <p class="text-gray-600 mt-2" x-text="`Rotación: ${rotation}°`"></p>
        </div>
    </div>

    <!-- Debug Info -->
    <div class="mt-6 p-4 bg-yellow-50 rounded-lg text-sm">
        <p class="text-yellow-800">
            <strong>Debug:</strong> Si los botones no funcionan, revisa la consola del navegador.
        </p>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>