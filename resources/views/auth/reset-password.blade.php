<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Restablecer contraseña
                </h2>
            </div>
            <form class="mt-8 space-y-6" method="POST" action="{{ route(\"password.update\") }}">
                @csrf
                <input type="hidden" name="token" value="{{ \$token }}">

                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" name="email" type="email" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                               placeholder="Email" value="{{ \$email ?? old(\"email\") }}" readonly>
                    </div>
                    <div>
                        <label for="password" class="sr-only">Nueva contraseña</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                               placeholder="Nueva contraseña">
                        @error(\"password\") <span class="text-red-500 text-sm">{{ \$message }}</span> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="sr-only">Confirmar contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                               placeholder="Confirmar contraseña">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Restablecer contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
