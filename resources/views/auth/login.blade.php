<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            UniTask
        </h1>

        <p class="mt-2 text-gray-500">
            Organiza tus tareas y estudios de forma simple
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div x-data="{
            email: '{{ old('email') }}',
            touched: false,
            get isValid() {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)
            }
        }">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Correo electrónico
            </label>

            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5H4.5A2.25 2.25 0 0 0 2.25 6.75m19.5 0-8.69 5.52a2.25 2.25 0 0 1-2.12 0L2.25 6.75" />
                    </svg>
                </span>

                <input id="email" type="email" name="email" x-model="email" @blur="touched = true"
                    value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="ejemplo@correo.com"
                    :class="touched
                        ?
                        (isValid ? 'border-green-400 focus:ring-green-400 focus:border-green-400' :
                            'border-red-400 focus:ring-red-400 focus:border-red-400') :
                        'border-gray-200 focus:ring-violet-400 focus:border-violet-400'"
                    class="w-full rounded-xl border bg-white px-12 py-3 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400">
            </div>

            <p x-show="touched && !isValid" x-transition class="mt-2 text-sm text-red-500">
                Ingresá un correo válido.
            </p>

            <p x-show="touched && isValid" x-transition class="mt-2 text-sm text-green-600">
                Correo válido.
            </p>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5" x-data="{
            password: '',
            showPassword: false,
            touched: false,
            get isValid() {
                return this.password.length >= 8
            }
        }">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Contraseña
            </label>

            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H6a1.5 1.5 0 0 1-1.5-1.5V12a1.5 1.5 0 0 1 1.5-1.5Z" />
                    </svg>
                </span>

                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" x-model="password"
                    @blur="touched = true" required autocomplete="current-password" placeholder="Mínimo 8 caracteres"
                    :class="touched
                        ?
                        (isValid ? 'border-green-400 focus:ring-green-400 focus:border-green-400' :
                            'border-red-400 focus:ring-red-400 focus:border-red-400') :
                        'border-gray-200 focus:ring-violet-400 focus:border-violet-400'"
                    class="w-full rounded-xl border bg-white px-12 py-3 pr-14 text-gray-800 outline-none transition shadow-sm placeholder:text-gray-400">

                <button type="button" @click="showPassword = !showPassword"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-violet-600 transition">
                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>

                    <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3l18 18M10.58 10.58A2 2 0 0 0 13.42 13.42M9.88 5.09A9.77 9.77 0 0 1 12 5c4.48 0 8.27 2.94 9.54 7a10.45 10.45 0 0 1-4.12 5.19M6.23 6.23A10.44 10.44 0 0 0 2.46 12c1.27 4.06 5.06 7 9.54 7a9.7 9.7 0 0 0 4.11-.91" />
                    </svg>
                </button>
            </div>

            <p x-show="touched && !isValid" x-transition class="mt-2 text-sm text-red-500">
                La contraseña debe tener al menos 8 caracteres.
            </p>

            <p x-show="touched && isValid" x-transition class="mt-2 text-sm text-green-600">
                Contraseña válida.
            </p>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-5">
            <label for="remember_me" class="flex items-center gap-3 cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember"
                    class="h-4 w-4 rounded border-gray-300 text-violet-600 focus:ring-violet-500">

                <span class="text-sm text-gray-600">
                    Mantener sesión iniciada
                </span>
            </label>
        </div>

        <div class="mt-6 space-y-4">

            @if (Route::has('password.request'))
                <div class="text-center">
                    <a href="{{ route('password.request') }}"
                        class="text-sm text-violet-600 hover:text-violet-700 hover:underline transition">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            @endif

            <button type="submit"
                class="w-full rounded-xl bg-violet-600 px-5 py-3 font-semibold text-white shadow-md transition hover:bg-violet-700 hover:-translate-y-0.5 hover:shadow-lg">
                Iniciar sesión
            </button>

        </div>
    </form>
</x-guest-layout>
