{{-- Formulario para añadir un participante al proyecto.
     Se incluye desde show.blade.php y usa las variables $project y $availableUsers --}}
<div class="mt-6 border-t border-gray-100 pt-6">
    <h4 class="text-base font-semibold text-gray-900">Añadir participante</h4>

    @if ($availableUsers->isEmpty())
        <p class="mt-2 text-sm text-gray-500">
            No quedan usuarios disponibles para añadir a este proyecto.
        </p>
    @else
        <form method="POST"
              action="{{ route('projects.participants.store', $project->id) }}"
              class="mt-4 grid gap-4 sm:grid-cols-3">
            @csrf

            {{-- Usuario --}}
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario</label>
                <select id="user_id" name="user_id" required
                        class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Selecciona un usuario --</option>
                    @foreach ($availableUsers as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Rol --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                <input type="text" id="role" name="role" list="roles"
                       value="{{ old('role') }}" required maxlength="50"
                       placeholder="Ej: Desarrollador"
                       class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <datalist id="roles">
                    <option value="Desarrollador">
                    <option value="Diseñador">
                    <option value="Tester">
                </datalist>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Horas --}}
            <div>
                <label for="contribution_hours" class="block text-sm font-medium text-gray-700">Horas trabajadas</label>
                <input type="number" id="contribution_hours" name="contribution_hours"
                       value="{{ old('contribution_hours', 0) }}" min="0" step="0.01"
                       class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('contribution_hours')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end sm:col-span-3">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                    Añadir participante
                </button>
            </div>
        </form>
    @endif
</div>