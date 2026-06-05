<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 font-sans">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 relative z-10">
            <h1 class="text-3xl font-bold text-center mb-6 text-violet-700">Crear cuenta</h1>

            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
                @csrf

                <!-- Nombre -->
                <div>
                    <x-input-label for="name" :value="__('Nombre')" class="text-gray-700 font-medium" />
                    <x-text-input id="name" class="block mt-2 w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-pink-600" />
                </div>

                <!-- Correo electrónico -->
                <div>
                    <x-input-label for="email" :value="__('Correo electrónico')" class="text-gray-700 font-medium" />
                    <x-text-input id="email" class="block mt-2 w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-600" />
                </div>

                <!-- Contraseña -->
                <div>
                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700 font-medium" />
                    <x-text-input id="password" class="block mt-2 w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500" type="password" name="password"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-600" />
                </div>

                <!-- Confirmar contraseña -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" class="text-gray-700 font-medium" />
                    <x-text-input id="password_confirmation" class="block mt-2 w-full border-gray-300 rounded-lg focus:border-violet-500 focus:ring-violet-500" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-pink-600" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-violet-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500"
                        href="{{ route('login') }}">
                        {{ __('¿Ya tienes cuenta?') }}
                    </a>

                    <x-primary-button class="bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg px-5 py-2 shadow-md">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

