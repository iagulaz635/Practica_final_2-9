<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">{{ $project->title }}</h2>

            <a href="{{ route('projects.index') }}" class="text-sm text-gray-500 hover:text-gray-800">
                &larr; Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-md bg-green-100 px-4 py-3 text-green-800">
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

            {{-- Datos del proyecto --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Propietario: {{ $project->owner->name }}
                            &middot;
                            Fecha límite: {{ \Carbon\Carbon::parse($project->due_date)->format('d/m/Y') }}
                        </p>
                    </div>

                    <span class="shrink-0 rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>

                <p class="mt-5 whitespace-pre-line text-gray-700">
                    {{ $project->description ?? 'Este proyecto no tiene descripción.' }}
                </p>

                {{-- Acciones: solo las ve el propietario --}}
                @if (Auth::id() === $project->user_id)
                    <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                        <a href="{{ route('projects.edit', $project->id) }}"
                           class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            Editar
                        </a>

                        <form method="POST"
                              action="{{ route('projects.destroy', $project->id) }}"
                              onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                Eliminar
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Participantes --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">
                    Participantes ({{ $project->participants->count() }})
                </h3>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Usuario</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Rol</th>
                                <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Horas</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($project->participants as $participant)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $participant->name }}
                                        <span class="block text-xs text-gray-500">{{ $participant->email }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $participant->pivot->role }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm text-gray-700">
                                        {{ number_format($participant->pivot->contribution_hours, 2, ',', '.') }} h
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Este proyecto aún no tiene participantes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if ($project->participants->isNotEmpty())
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-sm font-medium text-gray-700">Total de horas</td>
                                    <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                        {{ number_format($project->participants->sum('pivot.contribution_hours'), 2, ',', '.') }} h
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                {{-- Fase 4: aquí irá el formulario para añadir participantes (solo el propietario) --}}
            </div>

        </div>
    </div>
</x-app-layout>