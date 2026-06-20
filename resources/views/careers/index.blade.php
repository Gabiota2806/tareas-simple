<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 font-nunito flex-shrink-0">
                    Mis Carreras
                </h1>
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">

                    <a href="{{ route('careers.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-all font-nunito flex-shrink-0">
                        + Nueva Carrera
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-bold text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($careers as $career)
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-violet-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-violet-100 p-3 rounded-full flex-shrink-0">
                                <span class="text-2xl block">🎓</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800 font-nunito leading-tight">
                                    {{ $career->name }}
                                </h2>
                                <span class="block text-gray-500 text-sm font-nunito mt-1">
                                    {{ $career->university->name }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <span class="text-sm font-bold text-gray-700 font-nunito">Duración:</span>
                            <span class="text-sm text-gray-600 font-nunito">{{ $career->duration_years ? $career->duration_years . ' años' : 'No especificada' }}</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-nunito">
                                Creada: {{ $career->created_at->format('d/m/Y') }}
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('careers.edit', $career) }}" class="p-1.5 text-gray-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-all" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <form method="POST" action="{{ route('careers.destroy', $career) }}" class="inline-block"
                                    x-data
                                    @submit.prevent="
                                        Swal.fire({
                                            title: '¿Eliminar carrera?',
                                            text: 'Esta acción no se puede deshacer.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            cancelButtonColor: '#9ca3af',
                                            confirmButtonText: 'Sí, eliminar',
                                            cancelButtonText: 'Cancelar',
                                            customClass: {
                                                popup: 'font-nunito rounded-2xl shadow-xl border-t-4 border-red-500',
                                                title: 'font-bold text-gray-800',
                                                confirmButton: 'rounded-lg font-semibold shadow-md px-5 py-2.5',
                                                cancelButton: 'rounded-lg font-semibold shadow-sm px-5 py-2.5'
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $el.submit();
                                            }
                                        })
                                    ">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                        <span class="text-5xl mb-4 block">🎓</span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 font-nunito">No tienes carreras registradas</h3>
                        <p class="text-gray-500 mb-6 font-nunito">Aún no has agregado ninguna carrera a tu perfil.</p>
                        <a href="{{ route('careers.create') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md transition-all font-nunito">
                            Agregar mi primera carrera
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
