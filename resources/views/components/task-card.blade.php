@props(['id', 'status', 'title', 'subject', 'subjectId', 'type', 'priority', 'description' => '', 'dueDate' => '', 'rawDueDate' => '', 'teamMembers' => '', 'submissionFormat' => '', 'grade' => '', 'enrollmentDate' => '', 'examType' => '', 'subtasks' => []])

@php
    $typeColors = [
        'normal' => 'bg-gray-100 text-gray-700 border-gray-200',
        'tp' => 'bg-blue-100 text-blue-700 border-blue-200',
        'parcial' => 'bg-orange-100 text-orange-700 border-orange-200',
        'final' => 'bg-red-100 text-red-700 border-red-200',
    ];

    $priorityColors = [
        'low' => 'bg-green-100 text-green-700',
        'medium' => 'bg-orange-100 text-orange-700',
        'high' => 'bg-red-100 text-red-700',
    ];
@endphp

<div 
    x-data="taskCardData" 
    :data-has-subtasks="subtasks.length > 0 ? 'true' : 'false'"
    data-card-info="{{ base64_encode(json_encode([
        'id' => $id,
        'subjectId' => $subjectId,
        'status' => $status,
        'title' => $title,
        'description' => $description,
        'type' => $type,
        'priority' => $priority,
        'rawDueDate' => $rawDueDate,
        'teamMembers' => $teamMembers,
        'submissionFormat' => $submissionFormat,
        'enrollmentDate' => $enrollmentDate,
        'examType' => $examType,
        'grade' => $grade,
        'subtasks' => $subtasks
    ])) }}"
    @status-updated="currentStatus = $event.detail.newStatus">

@once
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('taskCardData', function() {
            return {
                init() {
                    const data = JSON.parse(atob(this.$el.dataset.cardInfo));
                    this.id = data.id;
                    this.subjectId = data.subjectId;
                    this.currentStatus = data.status;
                    this.editTitle = data.title || '';
                    this.editDescription = data.description || '';
                    this.editType = data.type;
                    this.editPriority = data.priority;
                    this.editDueDate = data.rawDueDate || '';
                    this.editTeamMembers = data.teamMembers || '';
                    this.editSubmissionFormat = data.submissionFormat || '';
                    this.editEnrollmentDate = data.enrollmentDate || '';
                    this.editExamType = data.examType || '';
                    this.editGrade = data.grade || '';
                    this.subtasks = data.subtasks || [];
                    
                    const isExam = ['parcial', 'final'].includes(data.type);
                    this.statuses = {
                        'pending': isExam ? '⏳ Pendiente' : '📋 Pendiente',
                        'in_progress': isExam ? '📖 Estudiando' : '⚡ En progreso',
                        'completed': isExam ? '✅ Rendido' : '✅ Completada'
                    };
                },
                open: false,
                isEditing: false,
                dropdownOpen: false,
                statuses: {},
                subtasks: [],
                newSubtaskTitle: '',
                nestedInputs: {},
                
                canComplete(st) {
                    if (st.status === 'completed') return true;
                    if (!st.nested_subtasks || st.nested_subtasks.length === 0) return true;
                    return !st.nested_subtasks.some(s => s.status !== 'completed');
                },

                showPendingChildrenAlert() {
                    Swal.fire({
                        title: 'Acción bloqueada',
                        text: 'Debes completar los pasos o subtareas internas primero.',
                        icon: 'warning',
                        confirmButtonColor: '#8b5cf6',
                        confirmButtonText: 'Entendido',
                        customClass: { popup: 'font-nunito rounded-2xl shadow-xl border-t-4 border-violet-500', title: 'font-bold text-gray-800', confirmButton: 'rounded-lg font-semibold shadow-md px-5 py-2.5' }
                    });
                },
                
                changeStatus(status, force = false) {
                    if (!force && this.subtasks && this.subtasks.length > 0) {
                        Swal.fire({
                            title: 'Estado Automático',
                            text: 'El estado se calcula automáticamente según sus subtareas. Completá o desmarcá las subtareas para cambiar el estado general.',
                            icon: 'info',
                            confirmButtonColor: '#8b5cf6',
                            confirmButtonText: 'Entendido',
                            customClass: { popup: 'font-nunito rounded-2xl shadow-xl border-t-4 border-violet-500', title: 'font-bold text-gray-800', confirmButton: 'rounded-lg font-semibold shadow-md px-5 py-2.5' }
                        });
                        this.dropdownOpen = false;
                        return;
                    }
                    this.currentStatus = status;
                    this.dropdownOpen = false;
                    window.apiFetch(`/tasks/${this.id}`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ status: status })
                    }).then(() => {
                        const cardElement = document.querySelector(`[data-id='${this.id}']`);
                        if (cardElement) {
                            const targetEstado = status === 'in_progress' ? 'proceso' : (status === 'completed' ? 'completada' : 'pendiente');
                            const row = cardElement.closest('.swimlane-row');
                            if (row) {
                                const targetContainer = row.querySelector(`[data-estado='${targetEstado}']`);
                                if (targetContainer) {
                                    targetContainer.appendChild(cardElement);
                                    window.dispatchEvent(new Event('actualizar-contadores'));
                                }
                            }
                        }
                    }).catch(err => console.error(err));
                },

                saveGradeOnly() {
                    if (this.editGrade === null || this.editGrade === '') return;
                    window.apiFetch(`/tasks/${this.id}`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ grade: this.editGrade })
                    }).then(() => {
                        Swal.fire({
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                            icon: 'success', title: 'Nota guardada con éxito',
                            customClass: { popup: 'font-nunito rounded-xl shadow-lg border-t-4 border-green-500' }
                        });
                        setTimeout(() => location.reload(), 1000);
                    }).catch(err => console.error(err));
                },

                async saveChanges() {
                    try {
                        let finalStatus = this.currentStatus;
                        if (this.editGrade && this.editGrade !== '') {
                            finalStatus = 'completed';
                        }
                        await window.apiFetch(`/tasks/${this.id}`, {
                            method: 'PATCH',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ 
                                title: this.editTitle, 
                                description: this.editDescription,
                                task_type: this.editType,
                                priority: this.editPriority,
                                due_date: this.editDueDate || null,
                                team_members: this.editTeamMembers || null,
                                submission_format: this.editSubmissionFormat || null,
                                enrollment_date: this.editEnrollmentDate || null,
                                exam_type: this.editExamType || null,
                                grade: this.editGrade || null,
                                status: finalStatus
                            })
                        });
                        location.reload();
                    } catch (err) { console.error(err); }
                },

                async confirmDeleteTask() {
                    const result = await Swal.fire({
                        title: '¿Eliminar tarea?',
                        text: 'Esta acción no se puede deshacer y borrará también todos los sub-pasos o temas asociados.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#9ca3af',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        customClass: { popup: 'font-nunito rounded-2xl shadow-2xl border-t-4 border-red-500' }
                    });

                    if (result.isConfirmed) {
                        try {
                            await window.apiFetch(`/tasks/${this.id}`, {
                                method: 'DELETE',
                                headers: { 'Content-Type': 'application/json' }
                            });
                            location.reload();
                        } catch (err) {
                            console.error(err);
                        }
                    }
                },

                async addSubtask() {
                    if (!this.newSubtaskTitle.trim()) return;
                    try {
                        const res = await window.apiFetch('/tasks', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({
                                title: this.newSubtaskTitle.trim(),
                                parent_id: this.id,
                                subject_id: this.subjectId,
                                task_type: 'normal',
                                priority: 'low',
                                reminder: false
                            })
                        });
                        const data = await res.json();
                        if (data && data.data) {
                            this.subtasks.push(data.data);
                            this.newSubtaskTitle = '';
                        }
                    } catch (e) { console.error(e); }
                },
                
                async syncCascadingStatus() {
                    const traverse = async (nodeArray, parent) => {
                        if (!nodeArray || nodeArray.length === 0) return;
                        let allCompleted = true;
                        for (let node of nodeArray) {
                            await traverse(node.nested_subtasks, node);
                            if (node.status !== 'completed') {
                                allCompleted = false;
                            }
                        }
                        if (parent) {
                            const newStatus = allCompleted ? 'completed' : 'pending';
                            if (parent.status !== newStatus) {
                                parent.status = newStatus;
                                try {
                                    await window.apiFetch(`/tasks/${parent.id}`, {
                                        method: 'PATCH',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ status: newStatus })
                                    });
                                } catch (e) { console.error(e); }
                            }
                        }
                    };

                    await traverse(this.subtasks, null);

                    if (this.subtasks && this.subtasks.length > 0) {
                        const completedCount = this.subtasks.filter(s => s.status === 'completed').length;
                        const totalCount = this.subtasks.length;
                        
                        if (completedCount === totalCount && this.currentStatus !== 'completed') {
                            this.changeStatus('completed', true);
                        } else if (completedCount === 0 && this.currentStatus !== 'pending') {
                            this.changeStatus('pending', true);
                        } else if (completedCount > 0 && completedCount < totalCount && this.currentStatus !== 'in_progress') {
                            this.changeStatus('in_progress', true);
                        }
                    }
                },

                async toggleSubtask(st) {
                    st.status = st.status === 'completed' ? 'pending' : 'completed';
                    try {
                        await window.apiFetch(`/tasks/${st.id}`, {
                            method: 'PATCH',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ status: st.status })
                        });
                        await this.syncCascadingStatus();
                    } catch (e) { console.error(e); }
                },
                
                async deleteNestedSubtask(stId, parentArray) {
                    const result = await Swal.fire({
                        title: '¿Eliminar subtarea?',
                        text: 'Esta acción eliminará también todas sus subtareas internas.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#9ca3af',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        customClass: { popup: 'font-nunito rounded-2xl shadow-xl border-t-4 border-red-500', title: 'font-bold text-gray-800', confirmButton: 'rounded-lg font-semibold shadow-md px-5 py-2.5', cancelButton: 'rounded-lg font-semibold shadow-sm px-5 py-2.5' }
                    });

                    if (result.isConfirmed) {
                        const index = parentArray.findIndex(s => s.id === stId);
                        if (index > -1) {
                            parentArray.splice(index, 1);
                            try {
                                await window.apiFetch(`/tasks/${stId}`, { method: 'DELETE' });
                            } catch (e) { console.error(e); }
                        }
                    }
                },

                getNestedProgress(st) {
                    if (!st.nested_subtasks || st.nested_subtasks.length === 0) return 0;
                    const comp = st.nested_subtasks.filter(s => s.status === 'completed').length;
                    return (comp / st.nested_subtasks.length) * 100;
                },

                async addNestedSubtask(parentSt) {
                    const title = this.nestedInputs[parentSt.id];
                    if (!title || !title.trim()) return;
                    try {
                        const res = await window.apiFetch('/tasks', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({
                                title: title.trim(),
                                parent_id: parentSt.id,
                                subject_id: this.subjectId,
                                task_type: 'normal',
                                priority: 'low',
                                reminder: false
                            })
                        });
                        const data = await res.json();
                        if (data && data.data) {
                            if (!parentSt.nested_subtasks) parentSt.nested_subtasks = [];
                            parentSt.nested_subtasks.push(data.data);
                            this.nestedInputs[parentSt.id] = '';
                        }
                    } catch (e) { console.error(e); }
                }
            };
        });
    });
</script>

<style>
    .modal-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .modal-scrollbar::-webkit-scrollbar-track {
        background: transparent;
        margin: 16px 0; /* Aleja la barra de los bordes superior/inferior */
    }
    .modal-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 10px;
    }
    .modal-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #d1d5db;
    }
</style>
@endonce

    <div @click="open = true"
        class="cursor-pointer rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg relative overflow-hidden group">

        <!-- Marca de agua gigante con la nota -->
        @if($grade && in_array($type, ['parcial', 'final']))
            <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:opacity-20 transition-opacity duration-300 pointer-events-none select-none">
                <span class="text-8xl font-black text-green-600 tracking-tighter">{{ $grade }}</span>
            </div>
        @endif

        <div class="flex items-center justify-between relative z-10">

            <div class="flex gap-2">
                <span class="rounded-full px-3 py-1 text-xs font-semibold border {{ $typeColors[$type] }}">
                    {{ strtoupper($type) }}
                </span>

                <span 
                    class="rounded-full px-3 py-1 text-xs font-semibold border transition-colors"
                    :class="{
                        'bg-gray-100 text-gray-600 border-gray-200': currentStatus === 'pending',
                        'bg-blue-100 text-blue-700 border-blue-200': currentStatus === 'in_progress',
                        'bg-green-100 text-green-700 border-green-200': currentStatus === 'completed'
                    }"
                    x-text="
                        currentStatus === 'pending' 
                            ? '{{ in_array($type, ['parcial', 'final']) ? '⏳ Pendiente' : '📋 Pendiente' }}'
                            : (currentStatus === 'in_progress'
                                ? '{{ in_array($type, ['parcial', 'final']) ? '📖 Estudiando' : '⚡ En proceso' }}'
                                : '{{ in_array($type, ['parcial', 'final']) ? '✅ Rendido' : '✅ Completada' }}'
                              )
                    "
                >
                </span>
            </div>

            @if($grade && in_array($type, ['parcial', 'final']))
                <span class="rounded-full bg-green-100 text-green-800 border border-green-200 px-3 py-1 text-xs font-bold flex items-center gap-1 shadow-sm">
                    ⭐ Nota: {{ $grade }}
                </span>
            @else
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $priorityColors[$priority] }}">
                    {{ strtoupper($priority) }}
                </span>
            @endif

        </div>

        <h3 class="mt-4 text-lg font-bold text-gray-800 relative z-10">
            {{ $title }}
        </h3>

        <p class="mt-2 text-sm text-gray-500 relative z-10">
            {{ $subject }}
        </p>

        @if(in_array($type, ['parcial', 'final']))
            <div class="mt-4 flex flex-col gap-1.5 text-xs text-gray-500 relative z-10">
                @if($examType)
                    <p class="flex items-center gap-1.5"><span class="text-sm">🎓</span> <span class="font-semibold text-gray-700">Modalidad:</span> {{ $examType }}</p>
                @endif
                @if($enrollmentDate && $type === 'final')
                    <p class="flex items-center gap-1.5"><span class="text-sm">📝</span> <span class="font-semibold text-orange-600">Inscripción límite:</span> {{ date('d M, Y', strtotime($enrollmentDate)) }}</p>
                @endif
                @if($status === 'completed' && !$grade)
                    <p class="flex items-center gap-1.5"><span class="text-sm">⏳</span> <span class="font-semibold text-blue-600">Esperando calificación...</span></p>
                @endif
            </div>
        @endif

        <!-- Subtareas expandibles e interactivas -->
        <template x-if="subtasks.length > 0">
            <div class="mt-4 border-t border-gray-100 pt-3 relative z-10" @click.stop>
                <!-- Barra de progreso -->
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="font-bold text-gray-500">Subtareas</span>
                    <span class="font-bold text-gray-700" x-text="Math.round((subtasks.filter(st => st.status === 'completed').length / subtasks.length) * 100) + '% (' + subtasks.filter(st => st.status === 'completed').length + '/' + subtasks.length + ')'"></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-violet-500 h-1.5 rounded-full transition-all duration-500" :style="'width: ' + (subtasks.filter(st => st.status === 'completed').length / subtasks.length * 100) + '%'"></div>
                </div>
            </div>
        </template>

        <div class="mt-5 flex items-center justify-between text-sm text-gray-400 relative z-10 border-t border-gray-100 pt-4">
            <span class="font-medium flex items-center gap-1.5 {{ $status !== 'completed' && $rawDueDate && \Carbon\Carbon::parse($rawDueDate)->isPast() ? 'text-red-500 font-bold' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $dueDate }}
            </span>
            <button type="button" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-violet-700 bg-violet-50 group-hover:bg-violet-100 group-hover:text-violet-800 px-3 py-1.5 rounded-lg transition-all shadow-sm">
                Ver detalles
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
            style="display:none;" @click.self="open = false">

            <!-- Contenedor padre estricto con overflow hidden para que la barra no rompa las esquinas -->
            <div class="w-full max-w-2xl rounded-3xl bg-white shadow-2xl max-h-[90vh] relative z-10 flex flex-col overflow-hidden">
                
                <!-- Área desplazable -->
                <div class="overflow-y-auto p-6 md:p-8 flex-1 modal-scrollbar">

                    <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-violeta-moderno">
                    {{ $subject }}
                </p>

                <div class="flex items-center gap-2">
                    <button @click="confirmDeleteTask()" type="button" x-show="!isEditing" class="text-xs font-bold text-red-500 hover:text-white border border-red-200 hover:bg-red-500 hover:border-red-500 bg-white rounded-lg py-1.5 px-3 shadow-sm transition">
                        🗑️ Borrar
                    </button>
                    <button @click="isEditing = !isEditing" type="button" class="text-xs font-bold text-gray-500 hover:text-violet-600 border border-gray-200 bg-white rounded-lg py-1.5 px-3 shadow-sm transition">
                        <span x-show="!isEditing">✏️ Editar</span>
                        <span x-show="isEditing" style="display:none;">❌ Cancelar</span>
                    </button>

                    <!-- Select de estado reactivo (Jira style) -->
                    <div x-show="!isEditing" class="relative">
                        <button type="button" @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center justify-between gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-bold text-gray-700 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno w-36">
                            <span x-text="statuses[currentStatus]"></span>
                            <svg class="h-3 w-3 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                            class="absolute z-50 mt-1 w-full rounded-lg border border-gray-100 bg-white p-2 shadow-xl"
                            style="display:none;">
                            <template x-for="(label, key) in statuses" :key="key">
                                <button type="button" @click="changeStatus(key)"
                                    class="w-full text-left truncate rounded-md px-3 py-1.5 text-xs hover:bg-violet-50 transition"
                                    :class="currentStatus === key ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'"
                                    x-text="label">
                                </button>
                            </template>
                        </div>
                    </div>

                    <button @click="open = false" class="text-gray-400 hover:text-gray-700 text-xl leading-none ml-2">
                        &times;
                    </button>
                </div>
            </div>

            <!-- MODO LECTURA -->
            <div x-show="!isEditing">
                <h2 class="mt-4 text-2xl font-bold text-gray-800">
                    {{ $title }}
                </h2>

                <div class="mt-4 flex flex-wrap gap-3">
                    <span class="rounded-full px-3 py-1 text-xs font-semibold border {{ $typeColors[$type] }}">
                        {{ strtoupper($type) }}
                    </span>
                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $priorityColors[$priority] }}">
                        {{ strtoupper($priority) }}
                    </span>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-700 text-sm">Fecha límite</h3>
                        <p class="text-gray-500 text-sm">{{ $dueDate }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-700 text-sm">Descripción</h3>
                        <div class="text-gray-600 text-sm whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100 mt-1">
                            {{ $description ?: 'Sin descripción disponible.' }}
                        </div>
                    </div>

                    <!-- LECTURA DE CAMPOS ESPECÍFICOS -->
                    <template x-if="editType === 'tp'">
                        <div class="bg-violet-50 p-4 rounded-xl border border-violet-100 space-y-2 mt-4">
                            <div x-show="editTeamMembers">
                                <span class="font-bold text-violet-800 text-xs uppercase tracking-wide">Compañeros:</span>
                                <span class="text-gray-700 text-sm ml-1" x-text="editTeamMembers"></span>
                            </div>
                            <div x-show="editSubmissionFormat">
                                <span class="font-bold text-violet-800 text-xs uppercase tracking-wide">Entrega:</span>
                                <span class="text-gray-700 text-sm ml-1" x-text="editSubmissionFormat"></span>
                            </div>
                        </div>
                    </template>

                    <template x-if="editType === 'final' || editType === 'parcial'">
                        <div class="bg-orange-50 p-4 sm:p-5 rounded-2xl border border-orange-100 shadow-inner mt-4 transition-all">
                            <div class="space-y-3">
                                <div x-show="editEnrollmentDate && editType === 'final'">
                                    <span class="font-bold text-orange-800 text-xs uppercase tracking-wide">Inscripción límite:</span>
                                    <span class="text-gray-700 text-sm ml-1" x-text="editEnrollmentDate"></span>
                                </div>
                                <div x-show="editExamType && editType === 'final'">
                                    <span class="font-bold text-orange-800 text-xs uppercase tracking-wide">Modalidad:</span>
                                    <span class="text-gray-700 text-sm ml-1" x-text="editExamType"></span>
                                </div>
                                
                                <div x-show="currentStatus === 'completed'" class="mt-4 pt-4 border-t border-orange-200/60" x-transition>
                                    <label class="block text-sm font-black text-green-700 mb-2 flex items-center gap-1.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                        Calificación del Examen
                                    </label>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <input type="number" step="0.1" min="0" max="10" x-model="editGrade" placeholder="Ej: 8.5" class="w-32 rounded-xl border-orange-200 bg-white focus:bg-white focus:border-green-500 focus:ring-green-500 text-gray-800 text-lg font-bold shadow-sm placeholder:text-gray-300 placeholder:font-normal placeholder:text-sm">
                                        <button type="button" @click="saveGradeOnly()" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-md transition transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-2">
                                            Guardar Nota
                                        </button>
                                    </div>
                                    <p class="text-xs text-orange-600 mt-2.5 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span x-show="!editGrade || editGrade === ''">Ingresá la nota para que impacte en tu promedio general.</span>
                                        <span x-show="editGrade && editGrade !== ''">Podés actualizar la nota si hubo un error.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Subtareas en Modal (con 4 niveles de anidación) -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-semibold text-gray-700 text-sm" x-text="['parcial', 'final'].includes(editType) ? 'Temas a estudiar / Sub-pasos' : 'Pasos / Subtareas'"></h3>
                            <div class="relative group flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-400 hover:text-violet-500 cursor-help transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-60 p-2.5 bg-gray-800 text-white text-xs rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all pointer-events-none z-50 text-center">
                                    <span x-show="['parcial', 'final'].includes(editType)">Agrega los temas del examen como sub-pasos para llevar un control de lo que ya estudiaste.</span>
                                    <span x-show="!['parcial', 'final'].includes(editType)">Usa los sub-pasos para dividir esta tarea en partes más pequeñas y manejables.</span>
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-gray-50 p-4 text-sm text-gray-600 border border-gray-100">
                            <template x-if="subtasks.length === 0">
                                <p class="text-center text-xs text-gray-400 italic mb-3" x-text="['parcial', 'final'].includes(editType) ? 'No hay temas registrados aún.' : 'No hay pasos registrados aún.'"></p>
                            </template>
                            <template x-if="subtasks.length > 0">
                                <ul class="space-y-4">
                                    <template x-for="st1 in subtasks" :key="st1.id">
                                        <li class="pl-0">
                                            <!-- Level 1 Node -->
                                            <div class="flex items-start gap-2 group/st1">
                                                <input type="checkbox" :checked="st1.status === 'completed'" @click="if(st1.status !== 'completed' && !canComplete(st1)) { $event.preventDefault(); showPendingChildrenAlert(); }" @change="toggleSubtask(st1)" class="mt-1 rounded border-gray-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                                                <div class="flex-1">
                                                    <div class="flex items-center">
                                                        <span :class="st1.status === 'completed' ? 'line-through text-gray-400' : 'text-gray-800'" x-text="st1.title" class="block font-bold"></span>
                                                        <button type="button" @click.stop="deleteNestedSubtask(st1.id, subtasks)" class="ml-2 text-gray-300 hover:text-red-500 opacity-0 group-hover/st1:opacity-100 transition-opacity"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                    </div>
                                                    
                                                    <!-- Progress Bar for st1 -->
                                                    <template x-if="st1.nested_subtasks && st1.nested_subtasks.length > 0">
                                                        <div class="w-full max-w-[200px] bg-gray-200 rounded-full h-1 mt-1.5 mb-3">
                                                            <div class="bg-violet-400 h-1 rounded-full transition-all" :style="'width: ' + getNestedProgress(st1) + '%'"></div>
                                                        </div>
                                                    </template>
                                                    
                                                    <!-- Level 2 List -->
                                                    <template x-if="st1.nested_subtasks && st1.nested_subtasks.length > 0">
                                                        <ul class="mt-2 space-y-3 pl-4 border-l-2 border-gray-200">
                                                            <template x-for="st2 in st1.nested_subtasks" :key="st2.id">
                                                                <li>
                                                                    <!-- Level 2 Node -->
                                                                    <div class="flex items-start gap-2 group/st2">
                                                                        <input type="checkbox" :checked="st2.status === 'completed'" @click="if(st2.status !== 'completed' && !canComplete(st2)) { $event.preventDefault(); showPendingChildrenAlert(); }" @change="toggleSubtask(st2)" class="mt-1 rounded border-gray-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                                                                        <div class="flex-1">
                                                                            <div class="flex items-center">
                                                                                <span :class="st2.status === 'completed' ? 'line-through text-gray-400' : 'text-gray-700'" x-text="st2.title" class="block font-semibold"></span>
                                                                                <button type="button" @click.stop="deleteNestedSubtask(st2.id, st1.nested_subtasks)" class="ml-2 text-gray-300 hover:text-red-500 opacity-0 group-hover/st2:opacity-100 transition-opacity"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                                            </div>
                                                                            
                                                                            <!-- Progress Bar for st2 -->
                                                                            <template x-if="st2.nested_subtasks && st2.nested_subtasks.length > 0">
                                                                                <div class="w-full max-w-[150px] bg-gray-200 rounded-full h-1 mt-1.5 mb-2">
                                                                                    <div class="bg-violet-400 h-1 rounded-full transition-all" :style="'width: ' + getNestedProgress(st2) + '%'"></div>
                                                                                </div>
                                                                            </template>
                                                                            
                                                                            <!-- Level 3 List -->
                                                                            <template x-if="st2.nested_subtasks && st2.nested_subtasks.length > 0">
                                                                                <ul class="mt-2 space-y-2 pl-4 border-l-2 border-gray-200">
                                                                                    <template x-for="st3 in st2.nested_subtasks" :key="st3.id">
                                                                                        <li>
                                                                                            <!-- Level 3 Node -->
                                                                                            <div class="flex items-start gap-2 group/st3">
                                                                                                <input type="checkbox" :checked="st3.status === 'completed'" @click="if(st3.status !== 'completed' && !canComplete(st3)) { $event.preventDefault(); showPendingChildrenAlert(); }" @change="toggleSubtask(st3)" class="mt-0.5 rounded border-gray-300 text-violet-600 focus:ring-violet-500 cursor-pointer">
                                                                                                <div class="flex-1">
                                                                                                    <div class="flex items-center">
                                                                                                        <span :class="st3.status === 'completed' ? 'line-through text-gray-400' : 'text-gray-600'" x-text="st3.title" class="block text-sm font-medium"></span>
                                                                                                        <button type="button" @click.stop="deleteNestedSubtask(st3.id, st2.nested_subtasks)" class="ml-2 text-gray-300 hover:text-red-500 opacity-0 group-hover/st3:opacity-100 transition-opacity"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                                                                    </div>
                                                                                                    
                                                                                                    <!-- Level 4 is max -->
                                                                                                    <template x-if="st3.nested_subtasks && st3.nested_subtasks.length > 0">
                                                                                                        <ul class="mt-2 space-y-1 pl-4 border-l-2 border-gray-200 text-xs">
                                                                                                            <template x-for="st4 in st3.nested_subtasks" :key="st4.id">
                                                                                                                <li class="flex items-start gap-1.5 group/st4">
                                                                                                                    <input type="checkbox" :checked="st4.status === 'completed'" @click="if(st4.status !== 'completed' && !canComplete(st4)) { $event.preventDefault(); showPendingChildrenAlert(); }" @change="toggleSubtask(st4)" class="rounded border-gray-300 text-violet-600 focus:ring-violet-500 cursor-pointer w-3 h-3 mt-0.5">
                                                                                                                    <span :class="st4.status === 'completed' ? 'line-through text-gray-400' : 'text-gray-500'" x-text="st4.title" class="flex-1"></span>
                                                                                                                    <button type="button" @click.stop="deleteNestedSubtask(st4.id, st3.nested_subtasks)" class="text-gray-300 hover:text-red-500 opacity-0 group-hover/st4:opacity-100 transition-opacity"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                                                                                </li>
                                                                                                            </template>
                                                                                                        </ul>
                                                                                                    </template>
                                                                                                    
                                                                                                    <!-- Add Level 4 Input -->
                                                                                                    <form @submit.prevent="addNestedSubtask(st3)" class="flex items-center mt-1 pl-4">
                                                                                                        <input type="text" x-model="nestedInputs[st3.id]" placeholder="Añadir nivel 4..." class="w-full text-[10px] py-1 px-2 rounded-md border-gray-200 bg-white focus:border-violet-300 transition-all placeholder:text-gray-300">
                                                                                                        <button type="submit" class="ml-1 text-gray-400 hover:text-violet-600"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></button>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                    </template>
                                                                                </ul>
                                                                            </template>
                                                                            
                                                                            <!-- Add Level 3 Input -->
                                                                            <form @submit.prevent="addNestedSubtask(st2)" class="flex items-center mt-2 pl-4">
                                                                                <input type="text" x-model="nestedInputs[st2.id]" placeholder="Añadir nivel 3..." class="w-full text-xs py-1 px-2 rounded-md border-gray-200 bg-white focus:border-violet-300 transition-all placeholder:text-gray-300">
                                                                                <button type="submit" class="ml-1 text-gray-400 hover:text-violet-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </template>
                                                    
                                                    <!-- Add Level 2 Input -->
                                                    <form @submit.prevent="addNestedSubtask(st1)" class="flex items-center mt-3 pl-4">
                                                        <input type="text" x-model="nestedInputs[st1.id]" placeholder="Añadir sub-paso (Nivel 2)..." class="w-full text-sm py-1.5 px-3 rounded-lg border-gray-200 bg-white focus:border-violet-300 transition-all placeholder:text-gray-300">
                                                        <button type="submit" class="ml-1 text-gray-400 hover:text-violet-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            
                            <!-- Root level add inside Modal -->
                            <form @submit.prevent="addSubtask" class="flex items-center transition-all" :class="subtasks.length > 0 ? 'mt-6 pt-4 border-t border-gray-200' : ''">
                                <input type="text" x-model="newSubtaskTitle" :placeholder="['parcial', 'final'].includes(editType) ? 'Añadir tema para estudiar...' : 'Añadir nueva subtarea principal (Nivel 1)...'" class="w-full text-sm py-2 px-3 rounded-lg border-gray-200 bg-white focus:border-violet-300 transition-all">
                                <button type="submit" class="ml-2 bg-violet-100 text-violet-700 p-2 rounded-lg hover:bg-violet-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg></button>
                            </form>
                        </div>
                    </div>


                </div>
            </div>

            <!-- MODO EDICIÓN -->
            <div x-show="isEditing" style="display:none;" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Título</label>
                    <input type="text" x-model="editTitle" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm">
                </div>
                
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tipo de Tarea</label>
                        <div x-data="{ open: false, get options() { return ['parcial', 'final'].includes(editType) ? {'parcial': 'Parcial', 'final': 'Final'} : {'normal': 'Normal', 'tp': 'Trabajo Práctico'}; } }" class="relative w-full">
                            <button type="button" @click="open = !open"
                                class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:bg-white">
                                <span x-text="options[editType]"></span>
                                <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 mt-1 w-full rounded-xl border border-gray-100 bg-white p-2 shadow-xl" style="display:none;">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button" @click="editType = key; open = false"
                                        class="w-full text-left truncate rounded-lg px-3 py-2 text-sm hover:bg-violet-50 transition"
                                        :class="editType === key ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'"
                                        x-text="label">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Prioridad</label>
                        <div x-data="{ open: false, options: {'low': 'Baja', 'medium': 'Media', 'high': 'Alta'} }" class="relative w-full">
                            <button type="button" @click="open = !open"
                                class="flex w-full items-center justify-between gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800 shadow-sm transition hover:border-violet-300 focus:outline-none focus:ring-1 focus:ring-violeta-moderno focus:bg-white">
                                <span x-text="options[editPriority]"></span>
                                <svg class="h-4 w-4 shrink-0 text-violeta-moderno" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute z-50 mt-1 w-full rounded-xl border border-gray-100 bg-white p-2 shadow-xl" style="display:none;">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button" @click="editPriority = key; open = false"
                                        class="w-full text-left truncate rounded-lg px-3 py-2 text-sm hover:bg-violet-50 transition"
                                        :class="editPriority === key ? 'bg-violet-50 text-violeta-moderno font-bold' : 'text-gray-700'"
                                        x-text="label">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1" x-text="(editType === 'parcial' || editType === 'final') ? 'Fecha del Examen' : 'Fecha límite'">Fecha límite</label>
                    <input type="date" x-model="editDueDate" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm">
                </div>

                <!-- EDICIÓN TP -->
                <div x-show="editType === 'tp'" class="space-y-4 bg-violet-50 p-4 rounded-xl border border-violet-100 mt-4">
                    <div>
                        <label class="block text-sm font-bold text-violet-800 mb-1">Compañeros de Equipo</label>
                        <input type="text" x-model="editTeamMembers" class="w-full rounded-xl border-violet-200 bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-violet-800 mb-1">Formato de Entrega</label>
                        <input type="text" x-model="editSubmissionFormat" class="w-full rounded-xl border-violet-200 bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm">
                    </div>
                </div>

                <!-- EDICIÓN FINAL/PARCIAL -->
                <div x-show="editType === 'final'" class="space-y-4 bg-orange-50 p-4 rounded-xl border border-orange-100 mt-4">
                    <div>
                        <label class="block text-sm font-bold text-orange-800 mb-1">Inscripción a Mesa</label>
                        <input type="date" x-model="editEnrollmentDate" class="w-full rounded-xl border-orange-200 bg-white focus:border-orange-400 focus:ring-orange-400 text-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-orange-800 mb-1">Modalidad</label>
                        <select x-model="editExamType" class="w-full rounded-xl border-orange-200 bg-white focus:border-orange-400 focus:ring-orange-400 text-gray-800 text-sm">
                            <option value="">Seleccionar...</option>
                            <option value="Escrito">Escrito</option>
                            <option value="Oral">Oral</option>
                            <option value="Mixto">Mixto</option>
                        </select>
                    </div>
                </div>

                <!-- NOTA OBTENIDA -->
                <div x-show="(editType === 'parcial' || editType === 'final')" class="mt-4">
                    <label class="block text-sm font-bold text-green-700 mb-1">Nota Obtenida</label>
                    <input type="number" step="0.1" max="10" min="0" x-model="editGrade" class="w-full rounded-xl border-green-200 bg-green-50 focus:bg-white focus:border-green-400 focus:ring-green-400 text-gray-800 text-sm" placeholder="Ej: 8.5">
                    <p class="text-xs text-green-600 mt-1" x-show="currentStatus !== 'completed'">Al guardar una nota, se marcará automáticamente como Rendido.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Descripción</label>
                    <textarea x-model="editDescription" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-400 focus:ring-violet-400 text-gray-800 text-sm"></textarea>
                </div>
                
                <div class="flex justify-end pt-4 mt-2 border-t border-gray-100">
                    <button @click="saveChanges()" class="rounded-xl bg-violeta-moderno px-6 py-2.5 text-white font-bold shadow-md transition hover:-translate-y-0.5 hover:shadow-lg text-sm flex items-center gap-2">
                        💾 Guardar cambios
                    </button>
                </div>

                </div>
            </div>
        </div>
    </template>
</div>
