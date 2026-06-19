<x-app-layout>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6">

            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                Mis tareas
            </h1>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                <x-task-card
                    title="Entrega MVP"
                    subject="Base de Datos"
                    type="tp"
                    priority="high"
                    dueDate="26/06/2026"
                    description="Presentar la versión funcional del sistema."
                />

                <x-task-card
                    title="Parcial de Algoritmos"
                    subject="Algoritmos"
                    type="parcial"
                    priority="medium"
                    dueDate="01/07/2026"
                    description="Repasar grafos, matrices y recursividad."
                />

                <x-task-card
                    title="Final de Inglés"
                    subject="Inglés"
                    type="final"
                    priority="high"
                    dueDate="15/07/2026"
                    description="Preparar exposición y vocabulario técnico."
                />

            </div>

        </div>
    </div>

</x-app-layout>