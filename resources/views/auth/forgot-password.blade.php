<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Recuperar acceso
        </h1>

        <p class="mt-2 text-gray-500">
            Ingresá tu correo y te enviaremos un enlace para restablecer tu contraseña.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" x-data="{
        email: '{{ old('email') }}',
        touched: false,
        get isValid() {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)
        }
    }">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Correo electrónico
            </label>

            <input id="email" type="email" name="email" x-model="email" @blur="touched = true"
                value="{{ old('email') }}" required autofocus placeholder="ejemplo@correo.com"
                :class="touched
                    ?
                    (isValid ? 'border-green-400 focus:ring-green-400 focus:border-green-400' :
                        'border-red-400 focus:ring-red-400 focus:border-red-400') :
                    'border-gray-200 focus:ring-violeta-moderno focus:border-violeta-moderno'"
                class="w-full rounded-xl border bg-white px-4 py-3 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400">

            <p x-show="touched && !isValid" x-transition class="mt-2 text-sm text-red-500">
                Ingresá un correo válido.
            </p>

            <p x-show="touched && isValid" x-transition class="mt-2 text-sm text-green-600">
                Correo válido.
            </p>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit"
            class="mt-6 w-full rounded-xl bg-violeta-moderno px-5 py-3 font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
            Enviar enlace de recuperación
        </button>

        <div class="text-center mt-5">
            <a href="{{ route('login') }}" class="text-sm text-violeta-moderno hover:underline transition">
                Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>
