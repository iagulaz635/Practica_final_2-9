<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Proyectos</h2>
                <p class="mt-0.5 text-sm text-gray-500">
                    {{ $projects->count() }} {{ $projects->count() === 1 ? 'proyecto' : 'proyectos' }} en total
                </p>
            </div>

            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center gap-1 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + Nuevo proyecto
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-md border-l-4 border-green-500 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @php
                // Estilos de cada estado: etiqueta, punto de color y franja superior de la tarjeta
                $statusStyles = [
                    'pendiente' => [
                        'badge' => 'bg-yellow-100 text-yellow-800',
                        'dot' => 'bg-yellow-500',
                        'accent' => 'border-t-yellow-400',
                    ],
                    'en curso' => [
                        'badge' => 'bg-blue-100 text-blue-800',
                        'dot' => 'bg-blue-500',
                        'accent' => 'border-t-blue-400',
                    ],
                    'finalizado' => [
                        'badge' => 'bg-green-100 text-green-800',
                        'dot' => 'bg-green-500',
                        'accent' => 'border-t-green-400',
                    ],
                ];
                $defaultStyle = [
                    'badge' => 'bg-gray-100 text-gray-800',
                    'dot' => 'bg-gray-500',
                    'accent' => 'border-t-gray-300',
                ];
            @endphp

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($projects as $project)
                    @php
                        $style = $statusStyles[$project->status] ?? $defaultStyle;
                        $isMine = Auth::id() === $project->user_id;
                        $due = \Carbon\Carbon::parse($project->due_date);
                        $overdue = $due->lt(today()) && $project->status !== 'finalizado';
                        $count = $project->participants_count ?? 0;
                    @endphp

                    <article class="flex flex-col rounded-lg border border-t-4 border-gray-200 {{ $style['accent'] }} bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                        <div class="flex-1 p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold leading-snug text-gray-900">
                                    <a href="{{ route('projects.show', $project->id) }}" class="hover:text-indigo-600">
                                        {{ $project->title }}
                                    </a>
                                </h3>

                                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $style['badge'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $style['dot'] }}"></span>
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>

                            <p class="mt-3 text-sm leading-relaxed text-gray-600">
                                {{ Str::limit($project->description ?? 'Sin descripción', 110) }}
                            </p>

                            <div class="mt-5 flex items-center justify-between gap-3 text-xs">
                                {{-- Propietario con avatar de inicial --}}
                                <div class="flex items-center gap-2 text-gray-600">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                                        {{ mb_strtoupper(mb_substr($project->owner->name, 0, 1)) }}
                                    </span>
                                    <span>{{ $isMine ? 'Tú' : $project->owner->name }}</span>
                                </div>

                                {{-- Fecha límite (en rojo si ya ha vencido) --}}
                                <span class="inline-flex items-center gap-1 {{ $overdue ? 'font-semibold text-red-600' : 'text-gray-500' }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    {{ $due->format('d/m/Y') }}
                                    @if ($overdue)
                                        &middot; Vencido
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between rounded-b-lg border-t border-gray-100 bg-gray-50 px-5 py-3 text-sm">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('projects.show', $project->id) }}"
                                   class="font-medium text-indigo-600 hover:text-indigo-800">
                                    Ver detalle &rarr;
                                </a>

                                @if ($isMine)
                                    <a href="{{ route('projects.edit', $project->id) }}"
                                       class="text-gray-500 hover:text-gray-800">
                                        Editar
                                    </a>
                                @endif
                            </div>

                            <span class="text-xs text-gray-500">
                                {{ $count }} {{ $count === 1 ? 'participante' : 'participantes' }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-lg border-2 border-dashed border-gray-300 bg-white py-16 text-center">
                        <p class="text-lg font-medium text-gray-900">Todavía no hay proyectos</p>
                        <p class="mt-1 text-sm text-gray-500">Crea el primero para empezar a organizar el trabajo.</p>
                        <a href="{{ route('projects.create') }}"
                           class="mt-6 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            + Crear proyecto
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>