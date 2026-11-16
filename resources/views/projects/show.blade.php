<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Detalle del proyecto</h2>

            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 transition hover:text-gray-800">
                &larr; Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-md border-l-4 border-green-500 bg-green-50 px-4 py-3 text-green-800">
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
                $style = $statusStyles[$project->status] ?? [
                    'badge' => 'bg-gray-100 text-gray-800',
                    'dot' => 'bg-gray-500',
                    'accent' => 'border-t-gray-300',
                ];

                // true si el usuario logueado es el propietario del proyecto
                $isOwner = (int) Auth::id() === (int) $project->user_id;

                // Fecha límite y si ya ha vencido (solo si el proyecto no está finalizado)
                $due = \Carbon\Carbon::parse($project->due_date);
                $overdue = $due->lt(today()) && $project->status !== 'finalizado';

                // Total de horas de todos los participantes
                $totalHours = $project->participants->sum('pivot.contribution_hours');
            @endphp

            {{-- Datos del proyecto --}}
            <div class="rounded-lg border border-t-4 border-gray-200 {{ $style['accent'] }} bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <h3 class="text-2xl font-bold leading-tight text-gray-900">{{ $project->title }}</h3>

                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium {{ $style['badge'] }}">
                        <span class="h-2 w-2 rounded-full {{ $style['dot'] }}"></span>
                        {{ ucfirst($project->status) }}
                    </span>
                </div>

                <p class="mt-4 whitespace-pre-line leading-relaxed text-gray-700">
                    {{ $project->description ?? 'Este proyecto no tiene descripción.' }}
                </p>

                {{-- Resumen en cuatro casillas --}}
                <dl class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Propietario</dt>
                        <dd class="mt-2 flex items-center gap-2 text-sm font-medium text-gray-900">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                                {{ mb_strtoupper(mb_substr($project->owner->name, 0, 1)) }}
                            </span>
                            {{ $isOwner ? 'Tú' : $project->owner->name }}
                        </dd>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Fecha límite</dt>
                        <dd class="mt-2 text-sm font-medium {{ $overdue ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $due->format('d/m/Y') }}
                            @if ($overdue)
                                <span class="block text-xs font-semibold">Vencido</span>
                            @endif
                        </dd>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Participantes</dt>
                        <dd class="mt-2 text-sm font-medium text-gray-900">
                            {{ $project->participants->count() }}
                        </dd>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4">
                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Horas totales</dt>
                        <dd class="mt-2 text-sm font-medium text-gray-900">
                            {{ number_format($totalHours, 2, ',', '.') }} h
                        </dd>
                    </div>
                </dl>

                {{-- Acciones: solo las ve el propietario --}}
                @if ($isOwner)
                    <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                        <a href="{{ route('projects.edit', $project->id) }}"
                           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Editar proyecto
                        </a>

                        <form method="POST"
                              action="{{ route('projects.destroy', $project->id) }}"
                              class="ml-auto"
                              onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="inline-flex items-center rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Eliminar proyecto
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Participantes --}}
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-semibold text-gray-900">Participantes</h3>
                    <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                        {{ $project->participants->count() }}
                    </span>
                </div>

                {{-- Errores al modificar un participante (bolsa de errores propia) --}}
                @if ($errors->participantUpdate->any())
                    <div class="mt-4 rounded-md border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->participantUpdate->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Usuario</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rol</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Horas</th>
                                @if ($isOwner)
                                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Acciones</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($project->participants as $participant)
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                                {{ mb_strtoupper(mb_substr($participant->name, 0, 1)) }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $participant->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $participant->email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    @if ($isOwner)
                                        {{-- Propietario: campos editables. El atributo form="..." los
                                             asocia al formulario de actualización de esta fila --}}
                                        <td class="px-4 py-3">
                                            <input type="text" name="role"
                                                   form="update-{{ $participant->id }}"
                                                   value="{{ $participant->pivot->role }}"
                                                   required maxlength="50"
                                                   class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <input type="number" name="contribution_hours"
                                                   form="update-{{ $participant->id }}"
                                                   value="{{ $participant->pivot->contribution_hours }}"
                                                   required min="0" step="0.01"
                                                   class="w-24 rounded-md border-gray-300 text-right text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Formulario de modificación (PUT) --}}
                                                <form id="update-{{ $participant->id }}"
                                                      method="POST"
                                                      action="{{ route('projects.participants.update', ['project' => $project->id, 'participant' => $participant->id]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                            class="rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700">
                                                        Guardar
                                                    </button>
                                                </form>

                                                {{-- Formulario de eliminación (DELETE) --}}
                                                <form method="POST"
                                                      action="{{ route('projects.participants.destroy', ['project' => $project->id, 'participant' => $participant->id]) }}"
                                                      onsubmit="return confirm('¿Quitar a este participante del proyecto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-md border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                        Quitar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @else
                                        {{-- Resto de usuarios: solo lectura --}}
                                        <td class="px-4 py-3">
                                            <span class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                                {{ $participant->pivot->role }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-700">
                                            {{ number_format($participant->pivot->contribution_hours, 2, ',', '.') }} h
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isOwner ? 4 : 3 }}" class="px-4 py-10 text-center">
                                        <p class="text-sm font-medium text-gray-900">Este proyecto aún no tiene participantes</p>
                                        @if ($isOwner)
                                            <p class="mt-1 text-xs text-gray-500">Añade al primero con el formulario de abajo.</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if ($project->participants->isNotEmpty())
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-sm font-medium text-gray-700">Total de horas</td>
                                    <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">
                                        {{ number_format($totalHours, 2, ',', '.') }} h
                                    </td>
                                    @if ($isOwner)
                                        <td></td>
                                    @endif
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                {{-- Formulario para añadir participantes: solo lo ve el propietario --}}
                @if ($isOwner)
                    @include('projects.add_participant')
                @endif
            </div>

        </div>
    </div>
</x-app-layout>