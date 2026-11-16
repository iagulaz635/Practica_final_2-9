<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

                <h3 class="text-lg font-semibold text-gray-900">
                    ¡Hola, {{ Auth::user()->name }}!
                </h3>
                <p class="mt-1 text-sm text-gray-600">
                    Entra al tablón para ver todos los proyectos.
                </p>

                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('projects.index') }}"
                       class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        Ver proyectos
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>