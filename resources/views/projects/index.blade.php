<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Proyectos
            </h2>
            <a href="{{ route('projects.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-500">
                Nuevo proyecto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Propietario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha límite</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($projects as $project)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('projects.show', $project) }}"
                                       class="text-indigo-600 hover:underline">
                                        {{ $project->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">{{ $project->owner->name }}</td>
                                <td class="px-6 py-4">{{ $project->due_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ ucfirst($project->status) }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if (auth()->id() === $project->user_id)
                                        <a href="{{ route('projects.edit', $project) }}"
                                           class="text-sm text-gray-600 hover:underline">Editar</a>

                                        <form action="{{ route('projects.destroy', $project) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('¿Seguro que quieres borrar este proyecto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="ml-3 text-sm text-red-600 hover:underline">
                                                Borrar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No hay proyectos todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>