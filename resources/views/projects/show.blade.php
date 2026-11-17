<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                {{ $project->title }}
            </h2>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('projects.index') }}"
                   class="text-sm text-gray-600 hover:text-gray-900 underline">
                    ← Volver al listado
                </a>

                @if (auth()->id() === $project->user_id)
                    <a href="{{ route('projects.edit', $project) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Editar
                    </a>

                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                          onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    @php
        $estados = [
            'pendiente' => ['etiqueta' => 'Pendiente', 'fondo' => '#fef3c7', 'texto' => '#92400e', 'punto' => '#f59e0b'],
            'en curso'  => ['etiqueta' => 'En curso',  'fondo' => '#e0e7ff', 'texto' => '#3730a3', 'punto' => '#6366f1'],
            'terminado' => ['etiqueta' => 'Terminado', 'fondo' => '#d1fae5', 'texto' => '#065f46', 'punto' => '#10b981'],
        ];
        $estado = $estados[$project->status] ?? [
            'etiqueta' => ucfirst($project->status),
            'fondo'    => '#f3f4f6',
            'texto'    => '#374151',
            'punto'    => '#6b7280',
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tarjeta 1: Información general del proyecto --}}
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Descripción</span>
                        <p class="mt-1 text-gray-700 text-sm leading-relaxed">
                            {{ $project->description ?? 'Sin descripción.' }}
                        </p>
                    </div>

                    <span class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full"
                          style="background-color: {{ $estado['fondo'] }}; color: {{ $estado['texto'] }};">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $estado['punto'] }};"></span>
                        {{ $estado['etiqueta'] }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-100 text-sm">
                    <div>
                        <span class="text-gray-500">Propietario:</span>
                        <span class="font-semibold text-gray-800 ml-1">{{ $project->owner->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Fecha límite:</span>
                        <span class="font-semibold text-gray-800 ml-1">
                            {{ $project->due_date ? $project->due_date->format('d/m/Y') : 'Sin fecha' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Tarjeta 2: Tabla de participantes --}}
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-800">
                        Participantes ({{ $project->members->count() }})
                    </h3>
                </div>

                @if ($errors->any())
                    <div class="m-6 mb-0 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nombre</th>
                                <th class="px-6 py-3 font-semibold">Rol</th>
                                <th class="px-6 py-3 font-semibold">Horas</th>
                                @if (auth()->id() === $project->user_id)
                                    <th class="px-6 py-3 font-semibold">Gestión</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($project->members as $member)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $member->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                        {{ $member->pivot->role }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                        {{ $member->pivot->contribution_hours }} h
                                    </td>

                                    @if (auth()->id() === $project->user_id)
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex flex-wrap items-center gap-2">
                                                {{-- Formulario actualizar rol/horas --}}
                                                <form action="{{ route('projects.members.update', [$project, $member]) }}"
                                                      method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="text" name="role"
                                                           value="{{ $member->pivot->role }}"
                                                           aria-label="Rol"
                                                           class="w-36 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs py-1.5">

                                                    <input type="number" name="contribution_hours"
                                                           value="{{ $member->pivot->contribution_hours }}" min="0"
                                                           aria-label="Horas"
                                                           class="w-20 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-xs py-1.5">

                                                    <button type="submit"
                                                            class="px-2.5 py-1.5 rounded bg-indigo-50 text-indigo-700 font-medium text-xs hover:bg-indigo-100 transition">
                                                        Actualizar
                                                    </button>
                                                </form>

                                                {{-- Formulario quitar participante --}}
                                                <form action="{{ route('projects.members.destroy', [$project, $member]) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Quitar a este participante del proyecto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-2.5 py-1.5 rounded bg-red-50 text-red-600 font-medium text-xs hover:bg-red-100 transition">
                                                        Quitar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->id() === $project->user_id ? 4 : 3 }}"
                                        class="px-6 py-8 text-center text-gray-500">
                                        Aún no hay participantes asignados a este proyecto.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tarjeta 3: Añadir participante (solo propietario) --}}
            @if (auth()->id() === $project->user_id)
                <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">
                        Añadir participante
                    </h3>

                    @if ($availableUsers->isEmpty())
                        <p class="text-sm text-gray-500">
                            Todos los usuarios registrados ya participan en este proyecto.
                        </p>
                    @else
                        <form action="{{ route('projects.members.store', $project) }}" method="POST"
                              class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                            @csrf

                            <div>
                                <x-input-label for="user_id" value="Usuario" />
                                <select id="user_id" name="user_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    @foreach ($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="role" value="Rol" />
                                <x-text-input id="role" name="role" type="text"
                                              placeholder="Ej. Desarrollador, Tester..."
                                              class="mt-1 block w-full text-sm" />
                            </div>

                            <div>
                                <x-input-label for="contribution_hours" value="Horas" />
                                <x-text-input id="contribution_hours" name="contribution_hours" type="number"
                                              value="0" min="0"
                                              class="mt-1 block w-full text-sm" />
                            </div>

                            <div>
                                <x-primary-button class="w-full justify-center py-2.5">
                                    Añadir
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>