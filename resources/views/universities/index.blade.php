<x-app-layout>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8 gap-4">
                <h1 class="text-3xl font-bold text-gray-800 font-nunito">
                    Mis Universidades
                </h1>
                <a href="{{ route('universities.create') }}" class="inline-flex items-center justify-center bg-violet-600 hover:bg-violet-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-all font-nunito">
                    + Nueva Universidad
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-bold text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($universities as $university)
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-violet-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-violet-100 p-3 rounded-full flex-shrink-0">
                                <span class="text-2xl block">🏛️</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800 font-nunito leading-tight">
                                    {{ $university->name }}
                                </h2>
                                @if($university->acronym)
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-md font-bold mt-1">
                                        {{ $university->acronym }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-400 font-nunito">
                                Creada: {{ $university->created_at->format('d/m/Y') }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if($university->is_favorite)
                                    <div class="p-1.5 text-yellow-500 bg-yellow-50 rounded-lg cursor-default shadow-sm border border-yellow-200" title="Universidad Principal (No se puede desmarcar)">
                                        <svg class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('universities.favorite', $university) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 rounded-lg transition-all text-gray-300 hover:text-yellow-500 hover:bg-yellow-50" title="Marcar como favorita">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('universities.edit', $university) }}" class="p-1.5 text-gray-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-all" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <form method="POST" action="{{ route('universities.destroy', $university) }}" class="inline-block"
                                    x-data
                                    @submit.prevent="
                                        Swal.fire({
                                            title: '¿Eliminar universidad?',
                                            text: 'Esta acción no se puede deshacer y borrará la institución de tu perfil.',
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
                        <span class="text-5xl mb-4 block">🏛️</span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 font-nunito">No tienes universidades registradas</h3>
                        <p class="text-gray-500 mb-6 font-nunito">Aún no has agregado ninguna universidad o institución a tu perfil.</p>
                        <a href="{{ route('universities.create') }}" class="inline-block bg-violet-600 hover:bg-violet-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-md transition-all font-nunito">
                            Agregar mi primera universidad
                        </a>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>
