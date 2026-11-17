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

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Horas</th>
                @if (auth()->id() === $project->user_id)
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($project->members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->pivot->role }}</td>
                    <td>{{ $member->pivot->contribution_hours }}</td>
                    @if (auth()->id() === $project->user_id)
                        <td>
                            <form action="{{ route('projects.members.update', [$project, $member]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="role" value="{{ $member->pivot->role }}">
                                <input type="number" name="contribution_hours" value="{{ $member->pivot->contribution_hours }}" min="0">
                                <button type="submit">Actualizar</button>
                            </form>

                            <form action="{{ route('projects.members.destroy', [$project, $member]) }}" method="POST"
                                  onsubmit="return confirm('¿Quitar a este participante?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Quitar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->id() === $project->user_id ? 4 : 3 }}">Aún no hay participantes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (auth()->id() === $project->user_id)
        <h3>Añadir participante</h3>
        <form action="{{ route('projects.members.store', $project) }}" method="POST">
            @csrf
            <select name="user_id">
                @foreach ($availableUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <input type="text" name="role" placeholder="Rol (Desarrollador, Tester...)">
            <input type="number" name="contribution_hours" value="0" min="0">
            <button type="submit">Añadir</button>
        </form>

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