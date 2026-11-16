<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Banner de bienvenida con el acceso principal al tablón --}}
            <div class="overflow-hidden rounded-lg bg-indigo-600 px-6 py-8 shadow-sm sm:px-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white/20 text-2xl font-bold text-white">
                            {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <div>
                            <h3 class="text-2xl font-bold text-white">
                                ¡Hola, {{ Auth::user()->name }}!
                            </h3>
                            <p class="mt-1 text-indigo-100">
                                Entra al tablón para ver todos los proyectos.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('projects.index') }}"
                       class="inline-flex shrink-0 items-center justify-center rounded-md bg-white px-5 py-2.5 text-sm font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                        Ver proyectos &rarr;
                    </a>
                </div>
            </div>

            {{-- Accesos rápidos --}}
            @php
                $shortcuts = [
                    [
                        'route' => 'projects.create',
                        'title' => 'Nuevo proyecto',
                        'text' => 'Crea un proyecto y empieza a asignar participantes.',
                        'icon' => 'M12 4.5v15m7.5-7.5h-15',
                    ],
                    [
                        'route' => 'profile.edit',
                        'title' => 'Mi perfil',
                        'text' => 'Actualiza tus datos personales y tu contraseña.',
                        'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
                    ],
                ];
            @endphp

            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ($shortcuts as $shortcut)
                    <a href="{{ route($shortcut['route']) }}"
                       class="group flex items-start gap-4 rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-300 hover:shadow-md">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $shortcut['icon'] }}" />
                            </svg>
                        </span>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-indigo-600">
                                {{ $shortcut['title'] }}
                            </h4>
                            <p class="mt-1 text-sm text-gray-500">{{ $shortcut['text'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>