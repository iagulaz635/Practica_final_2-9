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

                // true si el usuario logueado es el propietario del proyecto
                $isOwner = Auth::id() === $project->user_id;
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
                @if ($isOwner)
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

                {{-- Errores al modificar un participante (bolsa de errores propia) --}}
                @if ($errors->participantUpdate->any())
                    <div class="mt-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->participantUpdate->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Usuario</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Rol</th>
                                <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Horas</th>
                                @if ($isOwner)
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Acciones</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($project->participants as $participant)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $participant->name }}
                                        <span class="block text-xs text-gray-500">{{ $participant->email }}</span>
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
                                            <div class="flex items-center justify-end gap-4">
                                                {{-- Formulario de modificación (PUT) --}}
                                                <form id="update-{{ $participant->id }}"
                                                      method="POST"
                                                      action="{{ route('projects.participants.update', ['project' => $project->id, 'participant' => $participant->id]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
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
                                                            class="text-sm font-medium text-red-600 hover:text-red-800">
                                                        Quitar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @else
                                        {{-- Resto de usuarios: solo lectura --}}
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $participant->pivot->role }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-700">
                                            {{ number_format($participant->pivot->contribution_hours, 2, ',', '.') }} h
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isOwner ? 4 : 3 }}" class="px-4 py-6 text-center text-sm text-gray-500">
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