<x-app-layout>
    <div class="py-6">
        <div class="max-w-sm mx-auto sm:max-w-md">
            <div class="bg-white shadow-md rounded-xl p-4 sm:p-6">
                
                <!-- Encabezado centrado -->
                <div class="text-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 font-nunito">
                        Registrar nueva asignatura
                    </h2>
                    <p class="mt-1 text-gray-500 font-nunito text-sm">
                        Completa los datos para agregar una nueva asignatura
                    </p>
                </div>

                <form method="POST" action="{{ route('subjects.store') }}" class="space-y-4">
                    @csrf
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Nombre de la asignatura
                        </label>
                        <input type="text" id="nombre" name="name" required value="{{ old('name') }}"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Docente (Opcional) -->
                    <div>
                        <label for="teacher" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Docente <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                        </label>
                        <input type="text" id="teacher" name="teacher" value="{{ old('teacher') }}" placeholder="Ej: Ing. Juan López"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                        <x-input-error :messages="$errors->get('teacher')" class="mt-2" />
                    </div>

                    <!-- Aula (Opcional) -->
                    <div>
                        <label for="classroom" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Aula <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                        </label>
                        <input type="text" id="classroom" name="classroom" value="{{ old('classroom') }}" placeholder="Ej: Aula 12, Piso 2"
                               class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito
                               focus:border-violet-400 focus:ring-violet-400 transition">
                        <x-input-error :messages="$errors->get('classroom')" class="mt-2" />
                    </div>

                    <!-- Datos Académicos (Aprobación y Nota) -->
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label for="approval_type" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                                Aprobación <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <div x-data="{ 
                                open: false, 
                                selected: '{{ old('approval_type', '') }}', 
                                options: {
                                    '': 'Sin definir',
                                    'promocional': 'Promocional',
                                    'regular': 'Regular (Con Final)',
                                    'libre': 'Libre'
                                }
                            }" class="relative w-full font-nunito">
                                <input type="hidden" name="approval_type" x-model="selected">
                                
                                <button type="button" @click="open = !open"
                                    class="flex w-full items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:border-violeta-moderno"
                                    :class="selected === '' ? 'text-gray-400' : 'text-gray-800'">
                                    
                                    <span x-text="options[selected] || 'Sin definir'" class="truncate flex-1 text-left"></span>

                                    <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute z-50 mt-2 w-full rounded-lg border border-gray-100 bg-white p-2 shadow-xl"
                                    style="display:none;">
                                    
                                    <template x-for="(label, value) in options" :key="value">
                                        <button type="button" @click="selected = value; open = false" 
                                            class="w-full truncate rounded-md px-3 py-2 text-left text-sm hover:bg-violet-50 transition"
                                            :class="selected === value ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'"
                                            x-text="label">
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('approval_type')" class="mt-2" />
                        </div>

                        <div class="flex-1">
                            <label for="final_grade" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                                Nota Final <span class="text-xs text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <input type="number" step="0.1" min="0" max="10" id="final_grade" name="final_grade" value="{{ old('final_grade') }}" placeholder="Ej: 9.5"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-800 shadow-sm placeholder:text-gray-400 font-nunito focus:border-violet-400 focus:ring-violet-400 transition">
                            <x-input-error :messages="$errors->get('final_grade')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Horarios de cursada (Schedules) -->
                    <div x-data="{
                        schedules: [],
                        days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                        addSchedule() {
                            this.schedules.push({ day_of_week: 1, start_time: '08:00', end_time: '10:00', classroom: '' });
                        },
                        removeSchedule(index) {
                            this.schedules.splice(index, 1);
                        }
                    }">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-bold text-gray-700 font-nunito">Horarios de cursada</label>
                            <button type="button" @click="addSchedule" class="text-xs font-bold text-violet-600 hover:text-violet-800 transition bg-violet-50 hover:bg-violet-100 px-2 py-1 rounded">
                                + Añadir horario
                            </button>
                        </div>
                        
                        <div class="space-y-3">
                            <template x-for="(schedule, index) in schedules" :key="index">
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg relative">
                                    <button type="button" @click="removeSchedule(index)" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-2 gap-3 mb-3 pr-6">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Día</label>
                                            <select x-model="schedule.day_of_week" :name="`schedules[${index}][day_of_week]`" required class="w-full text-sm rounded-md border border-gray-300 py-1.5 px-2 focus:ring-violet-400 focus:border-violet-400 bg-white">
                                                <template x-for="(day, dIndex) in days" :key="dIndex">
                                                    <option :value="dIndex" x-text="day"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Aula (Opcional)</label>
                                            <input type="text" x-model="schedule.classroom" :name="`schedules[${index}][classroom]`" placeholder="Ej: Aula 10" class="w-full text-sm rounded-md border border-gray-300 py-1.5 px-2 focus:ring-violet-400 focus:border-violet-400 bg-white">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Inicio</label>
                                            <input type="time" x-model="schedule.start_time" :name="`schedules[${index}][start_time]`" required class="w-full text-sm rounded-md border border-gray-300 py-1.5 px-2 focus:ring-violet-400 focus:border-violet-400 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">Fin</label>
                                            <input type="time" x-model="schedule.end_time" :name="`schedules[${index}][end_time]`" required class="w-full text-sm rounded-md border border-gray-300 py-1.5 px-2 focus:ring-violet-400 focus:border-violet-400 bg-white">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="schedules.length === 0">
                                <p class="text-xs text-gray-400 italic text-center py-2 bg-gray-50 rounded-lg border border-gray-200">Sin horarios definidos. No aparecerán en el calendario.</p>
                            </template>
                        </div>
                    </div>

                    <!-- Carrera custom dropdown -->
                    <div>
                        <label for="career_id" class="block text-sm font-bold text-gray-700 mb-1 font-nunito">Carrera</label>
                        <div x-data='{ open: false, selected: "{{ old('career_id', $selectedCareer ?? '') }}", careers: @json($careers->mapWithKeys(fn($c) => [$c->id => $c->name])), get selectedName() {
                            return this.careers[this.selected] || "Seleccione una carrera";
                        } }' class="relative w-full">
                            <input type="hidden" name="career_id" x-model="selected" required>
                            
                            <button type="button" @click="open = !open"
                                class="flex w-full items-center justify-between gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:border-violeta-moderno">
                                
                                <span x-text="selectedName" class="truncate flex-1 text-left" :class="selected === '' ? 'text-gray-400' : 'text-gray-700'"></span>

                                <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 mt-2 w-full max-h-60 overflow-y-auto rounded-lg border border-gray-100 bg-white p-2 shadow-xl"
                                style="display:none;">
                                
                                <button type="button" @click="selected = ''; open = false" 
                                    class="w-full truncate rounded-md px-3 py-2 text-left text-sm hover:bg-violet-50 transition"
                                    :class="selected === '' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'">
                                    Seleccione una carrera
                                </button>

                                @foreach($careers as $career)
                                    <button type="button" @click="selected = '{{ $career->id }}'; open = false" 
                                        class="w-full truncate rounded-md px-3 py-2 text-left text-sm hover:bg-violet-50 transition"
                                        :class="selected === '{{ $career->id }}' ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'">
                                        {{ $career->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('career_id')" class="mt-2" />
                    </div>

                    <!-- Color Identificador en Dropdown -->
                    <div x-data="{ 
                        openColor: false,
                        selectedColor: '{{ old('color_code', '#8B5CF6') }}',
                        customColors: ['#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB'],
                        tempColor: '#000000',
                        palette: [
                            '#8B5CF6', '#EC4899', '#EF4444', '#F97316', 
                            '#F59E0B', '#10B981', '#06B6D4', '#3B82F6', 
                            '#4F46E5', '#D946EF', '#84CC16', '#F43F5E'
                        ],
                        init() {
                            let stored = JSON.parse(localStorage.getItem('unitask_custom_colors'));
                            if (stored && Array.isArray(stored)) {
                                stored.slice(0, 4).forEach((c, i) => this.customColors[i] = c);
                            }
                        },
                        saveCustomColor() {
                            let index = this.customColors.indexOf('#E5E7EB');
                            if (index !== -1) {
                                this.customColors[index] = this.tempColor;
                            } else {
                                this.customColors.unshift(this.tempColor);
                                this.customColors.pop();
                            }
                            this.selectedColor = this.tempColor;
                            localStorage.setItem('unitask_custom_colors', JSON.stringify(this.customColors));
                        }
                    }">
                        <label class="block text-sm font-bold text-gray-700 mb-1 font-nunito">
                            Color Identificador
                        </label>
                        
                        <input type="hidden" name="color_code" :value="selectedColor">
                        
                        <div class="relative w-fit min-w-[14rem]">
                            <!-- Botón del Dropdown -->
                            <button type="button" @click="openColor = !openColor"
                                    class="flex w-full items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white px-3 py-2 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:border-violeta-moderno">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-md border shadow-sm shrink-0" :style="`background-color: ${selectedColor}`"></div>
                                    <span class="text-sm font-medium text-gray-700 font-nunito truncate">Color seleccionado</span>
                                </div>

                                <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Contenido del Dropdown -->
                            <div x-show="openColor" @click.away="openColor = false" x-transition
                                 class="absolute z-50 mt-2 w-full rounded-xl border border-gray-100 bg-white p-3 shadow-xl"
                                 style="display:none;">
                                
                                <!-- Paleta (3 filas x 4 columnas) -->
                                <div class="grid grid-cols-4 gap-3 place-items-center mb-3">
                                    <template x-for="color in palette" :key="color">
                                        <button type="button" @click="selectedColor = color; openColor = false"
                                                class="w-6 h-6 rounded-md shadow-sm border-2 transition-transform focus:outline-none"
                                                :class="selectedColor === color ? 'border-gray-800 scale-110' : 'border-transparent hover:scale-110'"
                                                :style="`background-color: ${color}`"></button>
                                    </template>
                                </div>
                                
                                <!-- Custom Colors & Button -->
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <!-- Slots libres (1 fila x 4 columnas) -->
                                    <div class="grid grid-cols-4 gap-3 place-items-center mb-3">
                                        <template x-for="(color, index) in customColors" :key="index">
                                            <button type="button" @click="if(color !== '#E5E7EB') { selectedColor = color; openColor = false; }"
                                                    class="w-6 h-6 rounded-md shadow-sm border-2 transition-transform focus:outline-none"
                                                    :class="[
                                                        selectedColor === color && color !== '#E5E7EB' ? 'border-gray-800 scale-110' : 'border-transparent',
                                                        color !== '#E5E7EB' ? 'hover:scale-110 cursor-pointer' : 'cursor-default opacity-40'
                                                    ]"
                                                    :style="`background-color: ${color}`"></button>
                                        </template>
                                    </div>

                                    <!-- Controles para Añadir Personalizado -->
                                    <div class="flex items-center gap-2 mt-3">
                                        <!-- Círculo calibrador -->
                                        <div class="relative w-8 h-8 rounded-full shadow-sm border-2 border-gray-200 overflow-hidden shrink-0 cursor-pointer transition hover:border-violet-400 hover:scale-105" :style="`background-color: ${tempColor}`" title="Elegir color">
                                            <input type="color" x-model="tempColor" class="absolute top-[-10px] left-[-10px] w-16 h-16 cursor-pointer opacity-0">
                                        </div>
                                        <!-- Botón Añadir explícito -->
                                        <button type="button" @click="saveCustomColor()" class="flex-1 h-8 rounded-md bg-gray-50 border border-gray-200 hover:bg-violet-50 text-gray-600 hover:text-violet-700 hover:border-violet-300 text-xs font-bold font-nunito transition shadow-sm focus:outline-none focus:ring-1 focus:ring-violet-400">
                                            Añadir color
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('color_code')" class="mt-2" />
                    </div>

                    <!-- Botones -->
                    <div class="pt-4 flex gap-3">
                        <a href="{{ route('subjects.index') }}" class="w-1/3 flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 font-semibold text-gray-600 shadow-sm transition hover:bg-gray-200 hover:-translate-y-0.5 font-nunito text-sm">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="w-2/3 rounded-lg bg-violet-600 px-4 py-2 font-semibold text-white shadow-sm transition
                                hover:bg-violet-700 hover:-translate-y-0.5 hover:shadow-md font-nunito text-sm">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>





