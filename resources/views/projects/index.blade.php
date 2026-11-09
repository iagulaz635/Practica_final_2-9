<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Proyectos</h2>

            <a href="{{ route('projects.create') }}"
               class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                + Nuevo proyecto
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $statusColors = [
                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                    'en curso' => 'bg-blue-100 text-blue-800',
                    'finalizado' => 'bg-green-100 text-green-800',
                ];
            @endphp

            <div class="grid gap-6 md:grid-cols-2">
                @forelse ($projects as $project)
                    <div class="flex flex-col rounded-lg border border-gray-200 bg-white p-5 shadow-sm">

                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <a href="{{ route('projects.show', $project->id) }}" class="hover:text-indigo-600">
                                    {{ $project->title }}
                                </a>
                            </h3>

                            <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>

                        <p class="mt-2 flex-1 text-sm text-gray-600">
                            {{ Str::limit($project->description ?? 'Sin descripción', 100) }}
                        </p>

                        <p class="mt-4 text-xs text-gray-500">
                            Propietario: {{ $project->owner->name }}
                            &middot;
                            Fecha límite: {{ \Carbon\Carbon::parse($project->due_date)->format('d/m/Y') }}
                        </p>

                        <div class="mt-4 flex gap-4 border-t border-gray-100 pt-3 text-sm">
                            <a href="{{ route('projects.show', $project->id) }}"
                               class="font-medium text-indigo-600 hover:text-indigo-800">
                                Ver detalle
                            </a>

                            @if (Auth::id() === $project->user_id)
                                <a href="{{ route('projects.edit', $project->id) }}"
                                   class="text-gray-500 hover:text-gray-800">
                                    Editar
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="col-span-full py-12 text-center text-gray-500">
                        Todavía no hay proyectos. ¡Crea el primero!
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>