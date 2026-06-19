<x-app-layout>
    <div class="py-6">
        <div class="max-w-sm mx-auto sm:max-w-md">
            <div class="bg-white shadow-md rounded-xl p-4 sm:p-6">
                
                <!-- Encabezado centrado -->
                <div class="text-center mb-6">
                    <div class="inline-block bg-violet-100 p-3 rounded-full mb-3">
                        <span class="text-2xl block">🏛️</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 font-nunito">
                        Registrar Universidad
                    </h2>
                    <p class="mt-1 text-gray-500 font-nunito text-sm">
                        Agrega una nueva universidad a tu perfil
                    </p>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-bold text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('universities.store') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Nombre de la Universidad
                        </label>
                        <input type="text" id="name" name="name" required placeholder="Ej: Universidad Tecnológica Nacional"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Acrónimo (Opcional) -->
                    <div>
                        <label for="acronym" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Acrónimo <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                        </label>
                        <input type="text" id="acronym" name="acronym" placeholder="Ej: UTN"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                        <x-input-error :messages="$errors->get('acronym')" class="mt-2" />
                    </div>

                    <!-- Botones -->
                    <div class="pt-4 flex gap-3">
                        <a href="{{ route('dashboard') }}" class="w-1/3 flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 font-semibold text-gray-600 shadow-sm transition hover:bg-gray-200 hover:-translate-y-0.5 font-nunito text-sm">
                            Volver
                        </a>
                        <button type="submit"
                                class="w-2/3 rounded-lg bg-violet-600 px-4 py-2 font-semibold text-white shadow-sm transition
                                hover:bg-violet-700 hover:-translate-y-0.5 hover:shadow-md font-nunito text-sm">
                            Guardar Universidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
