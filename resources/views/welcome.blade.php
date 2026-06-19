<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniTask</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-gray-800 font-sans">

    <header class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="text-2xl font-bold text-violeta-moderno">
                UniTask
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="#features" class="hover:text-violeta-moderno transition">Características</a>
                <a href="#preview" class="hover:text-violeta-moderno transition">Vista previa</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="rounded-xl bg-violeta-moderno px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                        Ir al dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="hidden sm:inline-block rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Iniciar sesión
                    </a>

                    <a href="{{ route('register') }}"
                        class="rounded-xl bg-violeta-moderno px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                        Crear cuenta
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="pt-28">
        <section class="relative overflow-hidden max-w-7xl mx-auto px-6 py-16 grid gap-12 lg:grid-cols-2 items-center">

            <div class="absolute top-0 left-0 w-72 h-72 bg-violet-200/40 rounded-full blur-3xl"></div>

            <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-200/30 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <span
                    class="inline-flex rounded-full bg-violet-100 px-4 py-2 text-sm font-semibold text-violeta-moderno">
                    Organización académica simple
                </span>

                <h1 class="mt-6 text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900 leading-tight">
                    Organizá tus materias, tareas y evaluaciones en un solo lugar.
                </h1>

                <p class="mt-6 text-lg text-gray-500 leading-relaxed">
                    UniTask ayuda a estudiantes a planificar entregas, parciales, finales,
                    subtareas y fechas importantes desde una interfaz clara y moderna.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="rounded-xl bg-violeta-moderno px-6 py-3 text-center font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                            Entrar a UniTask
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="rounded-xl bg-violeta-moderno px-6 py-3 text-center font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg">
                            Empezar ahora
                        </a>

                        <a href="{{ route('login') }}"
                            class="rounded-xl border border-gray-200 bg-white px-6 py-3 text-center font-semibold text-gray-700 transition hover:bg-gray-50">
                            Ya tengo cuenta
                        </a>
                    @endauth
                </div>
            </div>

            <div id="preview" class="relative z-10">
                <div class="absolute -inset-4 bg-violet-200/50 blur-3xl rounded-full"></div>

                <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Panel académico</h2>
                            <p class="text-sm text-gray-400">Resumen semanal</p>
                        </div>

                        <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violeta-moderno">
                            UniTask
                        </span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3 mb-6">
                        <div class="rounded-2xl bg-violet-50 p-4">
                            <p class="text-sm text-gray-500">Materias</p>
                            <p class="mt-2 text-2xl font-bold text-violeta-moderno">5</p>
                        </div>

                        <div class="rounded-2xl bg-orange-50 p-4">
                            <p class="text-sm text-gray-500">Pendientes</p>
                            <p class="mt-2 text-2xl font-bold text-orange-500">12</p>
                        </div>

                        <div class="rounded-2xl bg-green-50 p-4">
                            <p class="text-sm text-gray-500">Completadas</p>
                            <p class="mt-2 text-2xl font-bold text-green-600">8</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-2xl border border-gray-100 p-4 flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">Entrega de prototipo</p>
                                <p class="text-sm text-gray-400">Trabajo práctico · Base de Datos</p>
                            </div>
                            <span class="text-sm font-semibold text-red-500">Alta</span>
                        </div>

                        <div class="rounded-2xl border border-gray-100 p-4 flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">Parcial de Algoritmos</p>
                                <p class="text-sm text-gray-400">Evaluación · Miércoles</p>
                            </div>
                            <span class="text-sm font-semibold text-orange-500">Media</span>
                        </div>

                        <div class="rounded-2xl border border-gray-100 p-4 flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">Lectura pendiente</p>
                                <p class="text-sm text-gray-400">Tarea normal · Programación</p>
                            </div>
                            <span class="text-sm font-semibold text-green-600">Baja</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="max-w-7xl mx-auto px-6 py-16">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900">
                    Todo lo que necesitás para organizar tu cursada
                </h2>

                <p class="mt-4 text-gray-500">
                    UniTask reúne herramientas pensadas para estudiantes universitarios.
                </p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-white p-6 shadow-md border border-gray-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-2xl mb-4">📌</div>
                    <h3 class="font-bold text-gray-800">Kanban Drag & Drop</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Organizá tareas por estado y movelas de forma visual.
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-md border border-gray-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-2xl mb-4">📅</div>
                    <h3 class="font-bold text-gray-800">Calendario</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Visualizá entregas, parciales y fechas importantes.
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-md border border-gray-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-2xl mb-4">✅</div>
                    <h3 class="font-bold text-gray-800">Subtareas</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Dividí actividades grandes en pasos más simples.
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-md border border-gray-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-2xl mb-4">📚</div>
                    <h3 class="font-bold text-gray-800">Materias</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Asociá cada tarea a una materia activa de tu cursada.
                    </p>
                </div>
            </div>
        </section>

        <section class="max-w-5xl mx-auto px-6 py-16 text-center">
            <div class="rounded-3xl bg-violeta-moderno p-10 text-white shadow-xl">
                <h2 class="text-3xl font-bold">
                    Empezá a organizar tu vida académica
                </h2>

                <p class="mt-4 text-violet-100">
                    Creá tu cuenta y gestioná tus materias, tareas y evaluaciones desde UniTask.
                </p>

                <div class="mt-8">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block rounded-xl bg-white px-6 py-3 font-semibold text-violeta-moderno transition hover:bg-violet-50">
                            Ir al dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="inline-block rounded-xl bg-white px-6 py-3 font-semibold text-violeta-moderno transition hover:bg-violet-50">
                            Crear cuenta gratis
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-gray-100 bg-white py-6">
        <div class="max-w-7xl mx-auto px-6 text-center text-sm text-gray-500">
            <p class="font-medium">
                © 2026 UniTask
            </p>

            <p class="mt-2">
                Organizando materias, tareas y sueños académicos 🚀
            </p>

            <p class="mt-1 text-xs text-gray-400">
                Hecho con ❤️ para estudiantes universitarios.
            </p>
        </div>
    </footer>

</body>

</html>
