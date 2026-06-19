<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Crear nueva contraseña
        </h1>

        <p class="mt-2 text-gray-500">
            Ingresá tu correo y definí una nueva contraseña para recuperar tu acceso.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('password.store') }}"
        x-data="{
            email: '{{ old('email', $request->email) }}',
            password: '',
            passwordConfirmation: '',
            touchedEmail: false,
            touchedPassword: false,
            touchedConfirmation: false,
            showPassword: false,
            showConfirmation: false,
            get emailValid() {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)
            },
            get passwordValid() {
                return this.password.length >= 8
            },
            get passwordsMatch() {
                return this.passwordConfirmation.length > 0 && this.password === this.passwordConfirmation
            }
        }"
    >
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Correo electrónico
            </label>

            <input
                id="email"
                type="email"
                name="email"
                x-model="email"
                @blur="touchedEmail = true"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
                placeholder="ejemplo@correo.com"
                :class="touchedEmail
                    ? (emailValid ? 'border-green-400 focus:ring-green-400 focus:border-green-400' : 'border-red-400 focus:ring-red-400 focus:border-red-400')
                    : 'border-gray-200 focus:ring-violeta-moderno focus:border-violeta-moderno'"
                class="w-full rounded-xl border bg-white px-4 py-3 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400"
            >

            <p x-show="touchedEmail && !emailValid" x-transition class="mt-2 text-sm text-red-500">
                Ingresá un correo válido.
            </p>

            <p x-show="touchedEmail && emailValid" x-transition class="mt-2 text-sm text-green-600">
                Correo válido.
            </p>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Nueva contraseña
            </label>

            <div class="relative">
                <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    x-model="password"
                    @blur="touchedPassword = true"
                    required
                    autocomplete="new-password"
                    placeholder="Mínimo 8 caracteres"
                    :class="touchedPassword
                        ? (passwordValid ? 'border-green-400 focus:ring-green-400 focus:border-green-400' : 'border-red-400 focus:ring-red-400 focus:border-red-400')
                        : 'border-gray-200 focus:ring-violeta-moderno focus:border-violeta-moderno'"
                    class="w-full rounded-xl border bg-white px-4 py-3 pr-12 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400"
                >

                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-violeta-moderno transition"
                >
                    <span x-text="showPassword ? 'Ocultar' : 'Ver'" class="text-xs font-medium"></span>
                </button>
            </div>

            <p x-show="touchedPassword && !passwordValid" x-transition class="mt-2 text-sm text-red-500">
                La contraseña debe tener al menos 8 caracteres.
            </p>

            <p x-show="touchedPassword && passwordValid" x-transition class="mt-2 text-sm text-green-600">
                Contraseña válida.
            </p>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Confirmar contraseña
            </label>

            <div class="relative">
                <input
                    id="password_confirmation"
                    :type="showConfirmation ? 'text' : 'password'"
                    name="password_confirmation"
                    x-model="passwordConfirmation"
                    @blur="touchedConfirmation = true"
                    required
                    autocomplete="new-password"
                    placeholder="Repetí tu nueva contraseña"
                    :class="touchedConfirmation
                        ? (passwordsMatch ? 'border-green-400 focus:ring-green-400 focus:border-green-400' : 'border-red-400 focus:ring-red-400 focus:border-red-400')
                        : 'border-gray-200 focus:ring-violeta-moderno focus:border-violeta-moderno'"
                    class="w-full rounded-xl border bg-white px-4 py-3 pr-12 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400"
                >

                <button
                    type="button"
                    @click="showConfirmation = !showConfirmation"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-violeta-moderno transition"
                >
                    <span x-text="showConfirmation ? 'Ocultar' : 'Ver'" class="text-xs font-medium"></span>
                </button>
            </div>

            <p x-show="touchedConfirmation && !passwordsMatch" x-transition class="mt-2 text-sm text-red-500">
                Las contraseñas no coinciden.
            </p>

            <p x-show="touchedConfirmation && passwordsMatch" x-transition class="mt-2 text-sm text-green-600">
                Las contraseñas coinciden.
            </p>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
            type="submit"
            class="mt-6 w-full rounded-xl bg-violeta-moderno px-5 py-3 font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg"
        >
            Restablecer contraseña
        </button>

        <div class="text-center mt-5">
            <a
                href="{{ route('login') }}"
                class="text-sm text-violeta-moderno hover:underline transition"
            >
                Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>