@php
    // Si llega un proyecto que ya existe, es el formulario de editar; si no, el de crear.
    $editando = isset($project) && $project->exists;

    $estados = [
        'pendiente' => 'Pendiente',
        'en curso'  => 'En curso',
        'terminado' => 'Terminado',
    ];

    $fecha = old('due_date', $editando && $project->due_date ? $project->due_date->format('Y-m-d') : '');
@endphp

<form method="POST"
      action="{{ $editando ? route('projects.update', $project) : route('projects.store') }}"
      class="space-y-6">
    @csrf
    @if ($editando)
        @method('PUT')
    @endif

    {{-- Título --}}
    <div>
        <x-input-label for="title" value="Título" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                      :value="old('title', $project->title ?? '')" autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    {{-- Descripción --}}
    <div>
        <x-input-label for="description" value="Descripción" />
        <textarea id="description" name="description" rows="4"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    {{-- Fecha límite y estado, en dos columnas en pantallas medianas --}}
    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="due_date" value="Fecha límite" />
            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
                          :value="$fecha" />
            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="status" value="Estado" />
            <select id="status" name="status"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @foreach ($estados as $valor => $etiqueta)
                    <option value="{{ $valor }}" @selected(old('status', $project->status ?? 'pendiente') === $valor)>
                        {{ $etiqueta }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    </div>

    {{-- Botones --}}
    <div class="flex items-center gap-4">
        <x-primary-button>
            {{ $editando ? 'Guardar cambios' : 'Crear proyecto' }}
        </x-primary-button>

        <a href="{{ $editando ? route('projects.show', $project) : route('projects.index') }}"
           class="text-sm text-gray-600 hover:text-gray-900 underline">
            Cancelar
        </a>
    </div>
</form>