<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} · Gestión de proyectos</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white text-gray-900 antialiased">

        {{-- Barra superior --}}
        <header class="border-b border-gray-100 bg-white">
            <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-sm font-bold text-white">
                        {{ mb_strtoupper(mb_substr(config('app.name', 'Laravel'), 0, 1)) }}
                    </span>
                    <span class="text-lg font-semibold text-gray-900">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            Ir al dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="rounded-md px-4 py-2 text-sm font-semibold text-gray-700 transition hover:text-indigo-600">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}"
                           class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            Registrarse
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            {{-- Hero: mensaje principal y vista previa --}}
            <section class="bg-indigo-50">
                <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">

                    <div>
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                            Gestión de proyectos colaborativa
                        </span>

                        <h1 class="mt-5 text-4xl font-bold leading-tight tracking-tight text-gray-900 sm:text-5xl">
                            Organiza tus proyectos y a tu equipo en un solo lugar
                        </h1>

                        <p class="mt-5 text-lg leading-relaxed text-gray-600">
                            Crea proyectos, asigna participantes con su rol y registra las horas
                            de cada uno. Todo claro, ordenado y al alcance de todo el equipo.
                        </p>

                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            @auth
                                <a href="{{ route('projects.index') }}"
                                   class="rounded-md bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Ver proyectos &rarr;
                                </a>
                                <a href="{{ route('dashboard') }}"
                                   class="rounded-md border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                    Ir al dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="rounded-md bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Crear cuenta gratis &rarr;
                                </a>
                                <a href="{{ route('login') }}"
                                   class="rounded-md border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                    Ya tengo cuenta
                                </a>
                            @endauth
                        </div>
                    </div>

                    {{-- Vista previa de un proyecto (contenido de ejemplo, no sale de la base de datos) --}}
                    <div class="mx-auto w-full max-w-md">
                        <div class="rounded-lg border border-t-4 border-gray-200 border-t-blue-400 bg-white p-5 shadow-lg">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold leading-snug text-gray-900">
                                    Rediseño de la web corporativa
                                </h3>
                                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    En curso
                                </span>
                            </div>

                            <p class="mt-3 text-sm leading-relaxed text-gray-600">
                                Renovar la imagen y la estructura del sitio web de la empresa.
                            </p>

                            <div class="mt-5 divide-y divide-gray-100 rounded-lg border border-gray-200 text-sm">
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">L</span>
                                        <div>
                                            <p class="font-medium text-gray-900">Luis Martínez</p>
                                            <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">Desarrollador</span>
                                        </div>
                                    </div>
                                    <span class="text-gray-700">24,50 h</span>
                                </div>

                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">M</span>
                                        <div>
                                            <p class="font-medium text-gray-900">Marta López</p>
                                            <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">Diseñadora</span>
                                        </div>
                                    </div>
                                    <span class="text-gray-700">18,00 h</span>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                                <span>Fecha límite: 15/12/2026</span>
                                <span class="font-semibold text-gray-900">Total: 42,50 h</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Características --}}
            @php
                $features = [
                    [
                        'title' => 'Proyectos bien organizados',
                        'text' => 'Crea proyectos con título, descripción, fecha límite y estado, y encuéntralos en un tablón claro.',
                        'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                    [
                        'title' => 'Equipo con roles',
                        'text' => 'Añade a otros usuarios a tus proyectos y asígnales un rol: desarrollador, diseñador, tester...',
                        'icon' => 'M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z',
                    ],
                    [
                        'title' => 'Control de horas',
                        'text' => 'Anota las horas que ha dedicado cada participante y consulta el total acumulado del proyecto.',
                        'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
                    ],
                ];
            @endphp

            <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">Todo lo que necesitas para coordinar tu trabajo</h2>
                    <p class="mt-3 text-gray-600">Sencillo de usar y pensado para que el equipo sepa siempre en qué punto está cada proyecto.</p>
                </div>

                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach ($features as $feature)
                        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                                </svg>
                            </span>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $feature['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $feature['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Llamada a la acción final: solo para visitantes --}}
            @guest
                <section class="px-4 pb-16 sm:px-6 lg:px-8 lg:pb-24">
                    <div class="mx-auto max-w-6xl rounded-lg bg-indigo-600 px-6 py-12 text-center shadow-sm sm:px-12">
                        <h2 class="text-3xl font-bold text-white">¿Listo para empezar?</h2>
                        <p class="mx-auto mt-3 max-w-xl text-indigo-100">
                            Crea tu cuenta en menos de un minuto y organiza tu primer proyecto hoy mismo.
                        </p>
                        <a href="{{ route('register') }}"
                           class="mt-8 inline-flex items-center rounded-md bg-white px-6 py-3 text-sm font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                            Crear cuenta gratis
                        </a>
                    </div>
                </section>
            @endguest
        </main>

        {{-- Pie de página --}}
        <footer class="border-t border-gray-100 bg-gray-50">
            <div class="mx-auto max-w-6xl px-4 py-6 text-center text-sm text-gray-500 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Proyecto desarrollado con Laravel.
            </div>
        </footer>

    </body>
</html>