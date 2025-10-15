<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Recuperar contraseña
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.
                </p>
            </div>
            <form class="mt-8 space-y-6" method="POST" action="{{ route(\"password.email\") }}">
                @csrf
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Email" value="{{ old(\"email\") }}">
                    @error(\"email\") <span class="text-red-500 text-sm">{{ \$message }}</span> @enderror
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Enviar enlace de recuperación
                    </button>
                </div>
                
                <div class="text-center">
                    <a href="{{ route(\"login\") }}" class="text-indigo-600 hover:text-indigo-500">
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
