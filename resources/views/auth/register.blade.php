<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 font-nunito">
            UniTask
        </h1>

        <p class="mt-2 text-gray-500 font-nunito">
            Crea tu cuenta y organiza tus tareas
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="font-nunito">
        @csrf

        <!-- Nombre -->
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nombre
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Tu nombre completo"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400 focus:ring-violeta-moderno focus:border-violeta-moderno">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-rosa-creativo" />
        </div>

        <!-- Correo electrónico -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="ejemplo@correo.com"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400 focus:ring-violeta-moderno focus:border-violeta-moderno">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rosa-creativo" />
        </div>

        <!-- Contraseña -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Contraseña
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="Mínimo 8 caracteres"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400 focus:ring-violeta-moderno focus:border-violeta-moderno">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rosa-creativo" />
        </div>

        <!-- Confirmar contraseña -->
        <div class="mb-5">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Confirmar contraseña
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="Repite tu contraseña"
                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400 focus:ring-violeta-moderno focus:border-violeta-moderno">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rosa-creativo" />
        </div>

        <!-- Enlace y botón -->
        <div class="mt-6 space-y-4">
            <div class="text-center">
                <a href="{{ route('login') }}"
                    class="text-sm text-violeta-moderno hover:text-violeta-moderno hover:underline transition">
                    ¿Ya tienes cuenta?
                </a>
            </div>

            <button type="submit"
                class="w-full rounded-xl bg-violeta-moderno px-5 py-3 font-semibold text-white shadow-md transition hover:bg-violeta-moderno hover:-translate-y-0.5 hover:shadow-lg">
                Registrarse
            </button>
        </div>
    </form>
</x-guest-layout>

