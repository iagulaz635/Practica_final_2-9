<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Nuevo proyecto</h2>

            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 transition hover:text-gray-800">
                &larr; Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">

            @php
                // Clases comunes de los campos y variantes según haya error o no
                $inputBase = 'mt-1 w-full rounded-md shadow-sm';
                $inputOk = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
                $inputBad = 'border-red-500 focus:border-red-500 focus:ring-red-500';
            @endphp

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">

                {{-- Cabecera de la tarjeta --}}
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Datos del proyecto</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Rellena la información básica. Los campos marcados con
                        <span class="font-semibold text-red-500">*</span> son obligatorios.
                    </p>
                </div>

                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf

                    <div class="space-y-5 p-6">

                        {{-- Aviso general si la validación ha fallado --}}
                        @if ($errors->any())
                            <div class="rounded-md border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-700">
                                Hay errores en el formulario. Revisa los campos marcados en rojo.
                            </div>
                        @endif

                        {{-- Título --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                   required maxlength="100"
                                   placeholder="Ej: Rediseño de la web corporativa"
                                   class="{{ $inputBase }} {{ $errors->has('title') ? $inputBad : $inputOk }}">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-xs text-gray-500">Máximo 100 caracteres.</p>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Descripción <span class="font-normal text-gray-400">(opcional)</span>
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      placeholder="Describe brevemente el objetivo del proyecto"
                                      class="{{ $inputBase }} {{ $errors->has('description') ? $inputBad : $inputOk }}">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fecha límite y estado, en dos columnas --}}
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">
                                    Fecha límite <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                                       required
                                       class="{{ $inputBase }} {{ $errors->has('due_date') ? $inputBad : $inputOk }}">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                        class="{{ $inputBase }} {{ $errors->has('status') ? $inputBad : $inputOk }}">
                                    @foreach (['pendiente', 'en curso', 'finalizado'] as $status)
                                        <option value="{{ $status }}" @selected(old('status', 'pendiente') === $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Pie con los botones --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                        <a href="{{ route('projects.index') }}"
                           class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Guardar proyecto
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>