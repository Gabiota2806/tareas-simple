<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen flex items-center justify-center p-6">
        {{-- 
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>
            --}}

        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2 min-h-[650px]">

                <!-- Panel izquierdo -->
                <div
                    class="hidden md:flex flex-col justify-center items-center text-center p-12
               bg-gradient-to-br from-violet-600 to-violet-800 text-white">

                    <div class="mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-white mx-auto" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                        </svg>
                    </div>

                    <h1 class="text-5xl font-bold mb-6">
                        UniTask
                    </h1>

                    <p class="text-lg text-violet-100 max-w-sm leading-relaxed">
                        Planificá tus materias,
                        organizá tus tareas y
                        alcanzá tus objetivos académicos.
                    </p>

                </div>

                <!-- Panel derecho -->
                <div class="flex items-center justify-center p-8">
                    <div class="w-full max-w-md">
                        {{ $slot }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
