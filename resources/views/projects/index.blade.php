<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Proyectos
            </h2>
            <a href="{{ route('projects.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nuevo proyecto
            </a>
        </div>
    </x-slot>

    @php
        // Etiquetas como en _form.blade.php + colores directos (fondo, texto, punto)
        $estados = [
            'pendiente' => ['etiqueta' => 'Pendiente', 'fondo' => '#fef3c7', 'texto' => '#92400e', 'punto' => '#f59e0b'],
            'en curso'  => ['etiqueta' => 'En curso',  'fondo' => '#e0e7ff', 'texto' => '#3730a3', 'punto' => '#6366f1'],
            'terminado' => ['etiqueta' => 'Terminado', 'fondo' => '#d1fae5', 'texto' => '#065f46', 'punto' => '#10b981'],
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tarjeta contenedora de la tabla --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-6 py-3.5 font-semibold">Proyecto</th>
                                <th class="px-6 py-3.5 font-semibold">Propietario</th>
                                <th class="px-6 py-3.5 font-semibold">Fecha límite</th>
                                <th class="px-6 py-3.5 font-semibold">Estado</th>
                                <th class="px-6 py-3.5 font-semibold">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($projects as $project)
                                @php
                                    $estado = $estados[$project->status] ?? [
                                        'etiqueta' => ucfirst($project->status),
                                        'fondo'    => '#f3f4f6',
                                        'texto'    => '#374151',
                                        'punto'    => '#6b7280',
                                    ];
                                @endphp

                                <tr class="hover:bg-gray-50/80 transition">
                                    {{-- Título + extracto de descripción --}}
                                    <td class="px-6 py-4 max-w-xs">
                                        <a href="{{ route('projects.show', $project) }}"
                                           class="font-semibold text-gray-900 hover:text-indigo-600 transition block">
                                            {{ $project->title }}
                                        </a>
                                        @if ($project->description)
                                            <p class="text-xs text-gray-500 truncate mt-0.5">
                                                {{ $project->description }}
                                            </p>
                                        @endif
                                    </td>

                                    {{-- Propietario --}}
                                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                        {{ $project->owner->name }}
                                    </td>

                                    {{-- Fecha límite --}}
                                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                        {{ $project->due_date ? $project->due_date->format('d/m/Y') : 'Sin fecha' }}
                                    </td>

                                    {{-- Estado con color garantizado --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full"
                                              style="background-color: {{ $estado['fondo'] }}; color: {{ $estado['texto'] }};">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $estado['punto'] }};"></span>
                                            {{ $estado['etiqueta'] }}
                                        </span>
                                    </td>

                                    {{-- Acciones juntas y alineadas --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (auth()->id() === $project->user_id)
                                            <div class="flex items-center gap-3 text-xs font-medium">
                                                <a href="{{ route('projects.edit', $project) }}"
                                                   class="px-2.5 py-1 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                                    Editar
                                                </a>

                                                <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                                      onsubmit="return confirm('¿Seguro que quieres borrar este proyecto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-2.5 py-1 rounded bg-red-50 text-red-600 hover:bg-red-100 transition">
                                                        Borrar
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Solo lectura</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        No hay proyectos todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>