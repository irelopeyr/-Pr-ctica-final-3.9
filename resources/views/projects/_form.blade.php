@csrf

<div>
    <label for="title">Título</label>
    <input type="text" name="title" id="title" value="{{ old('title', $project->title ?? '') }}">
    @error('title') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="description">Descripción</label>
    <textarea name="description" id="description">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="due_date">Fecha límite</label>
    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', isset($project) ? $project->due_date->format('Y-m-d') : '') }}">
    @error('due_date') <p>{{ $message }}</p> @enderror
</div>

<div>
    <label for="status">Estado</label>
    <select name="status" id="status">
        @foreach (['pendiente', 'en curso', 'terminado'] as $estado)
            <option value="{{ $estado }}" @selected(old('status', $project->status ?? '') === $estado)>
                {{ ucfirst($estado) }}
            </option>
        @endforeach
    </select>
    @error('status') <p>{{ $message }}</p> @enderror
</div>