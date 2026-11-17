<x-app-layout>
    <x-slot name="header">
        <h2>Editar proyecto</h2>
    </x-slot>

    <form action="{{ route('projects.update', $project) }}" method="POST">
        @method('PUT')
        @include('projects._form')
        <button type="submit">Guardar cambios</button>
        <a href="{{ route('projects.show', $project) }}">Cancelar</a>
    </form>
</x-app-layout>