<x-app-layout>
    <x-slot name="header">
        <h2>Nuevo proyecto</h2>
    </x-slot>

    <form action="{{ route('projects.store') }}" method="POST">
        @include('projects._form')
        <button type="submit">Crear proyecto</button>
        <a href="{{ route('projects.index') }}">Cancelar</a>
    </form>
</x-app-layout>