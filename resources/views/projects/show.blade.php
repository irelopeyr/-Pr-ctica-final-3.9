<x-app-layout>
    <x-slot name="header">
        <h2>{{ $project->title }}</h2>
    </x-slot>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <p>{{ $project->description ?? 'Sin descripción.' }}</p>
    <p>Propietario: {{ $project->owner->name }}</p>
    <p>Fecha límite: {{ $project->due_date->format('d/m/Y') }}</p>
    <p>Estado: {{ ucfirst($project->status) }}</p>

    <h3>Participantes</h3>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Horas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($project->members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->pivot->role }}</td>
                    <td>{{ $member->pivot->contribution_hours }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Aún no hay participantes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (auth()->id() === $project->user_id)
        <a href="{{ route('projects.edit', $project) }}">Editar</a>

        <form action="{{ route('projects.destroy', $project) }}" method="POST"
              onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?')">
            @csrf
            @method('DELETE')
            <button type="submit">Eliminar</button>
        </form>
    @endif

    <a href="{{ route('projects.index') }}">Volver al listado</a>
</x-app-layout>