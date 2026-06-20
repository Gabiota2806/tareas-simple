<x-app-layout>
    <div class="py-6">
        <div class="max-w-sm mx-auto sm:max-w-md">
            <div class="bg-white shadow-md rounded-xl p-4 sm:p-6">
                <div class="text-center mb-6">
                    <div class="inline-block bg-violet-100 p-3 rounded-full mb-3">
                        <span class="text-2xl block">✏️</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 font-nunito">
                        Editar Carrera
                    </h2>
                </div>

                <form method="POST" action="{{ route('careers.update', $career) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">Nombre de la Carrera</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $career->name) }}"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm font-nunito focus:border-violet-400 focus:ring-violet-400 transition">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 font-nunito">Universidad</label>
                        <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-gray-700 shadow-sm font-nunito flex items-center justify-between cursor-not-allowed">
                            <span class="truncate">
                                {{ $career->university->name }}
                            </span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input type="hidden" name="university_id" value="{{ $career->university_id }}">
                        <x-input-error :messages="$errors->get('university_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="duration_years" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">Duración (años) <span class="text-xs text-gray-400 font-normal">(Opcional)</span></label>
                        <input type="number" id="duration_years" name="duration_years" min="1" max="20" value="{{ old('duration_years', $career->duration_years) }}"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm font-nunito focus:border-violet-400 focus:ring-violet-400 transition">
                        <x-input-error :messages="$errors->get('duration_years')" class="mt-2" />
                    </div>

                    <div class="pt-4 flex gap-3">
                        <a href="{{ route('careers.index') }}" class="w-1/3 flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 font-semibold text-gray-600 shadow-sm transition hover:bg-gray-200 font-nunito text-sm">Cancelar</a>
                        <button type="submit" class="w-2/3 rounded-lg bg-violet-600 px-4 py-2 font-semibold text-white shadow-sm transition hover:bg-violet-700 font-nunito text-sm">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
