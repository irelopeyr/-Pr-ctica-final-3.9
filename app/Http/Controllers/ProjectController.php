<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('owner')->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    // El método store ahora utiliza ProjectRequest para validar los datos del proyecto
    public function store(ProjectRequest $request): RedirectResponse
    {
        $request->user()->ownedProjects()->create($request->validated());

        return redirect()->route('projects.index')
            ->with('success', 'Proyecto creado correctamente.');
    }

    public function show(Project $project): View
    {
        $project->load('owner', 'members');

        // Usuarios que se pueden añadir: los que no son participantes ni el propietario
        $availableUsers = User::whereNotIn('id', $project->members->pluck('id')->push($project->user_id))
            ->orderBy('name')
            ->get();

        return view('projects.show', compact('project', 'availableUsers'));
    }

    public function edit(Project $project): View
    {
        $this->authorizeOwner($project);

        return view('projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorizeOwner($project);

        $project->update($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorizeOwner($project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Proyecto eliminado correctamente.');
    }

    // Añade un usuario como participante del proyecto, con su rol y sus horas
    public function addMember(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeOwner($project);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:50',
            'contribution_hours' => 'required|integer|min:0',
        ]);

        if ((int) $validated['user_id'] === $project->user_id) {
            return back()->withErrors(['user_id' => 'El propietario no puede añadirse como participante.']);
        }

        if ($project->members()->where('users.id', $validated['user_id'])->exists()) {
            return back()->withErrors(['user_id' => 'Ese usuario ya participa en el proyecto.']);
        }

        $project->members()->attach($validated['user_id'], [
            'role' => $validated['role'],
            'contribution_hours' => $validated['contribution_hours'],
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Participante añadido correctamente.');
    }

    // Cambia el rol y las horas de un participante que ya está en el proyecto
    public function updateMember(Request $request, Project $project, User $user): RedirectResponse
    {
        $this->authorizeOwner($project);

        $validated = $request->validate([
            'role' => 'required|string|max:50',
            'contribution_hours' => 'required|integer|min:0',
        ]);

        $project->members()->updateExistingPivot($user->id, $validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Participante actualizado correctamente.');
    }

    // Quita a un participante del proyecto
    public function removeMember(Project $project, User $user): RedirectResponse
    {
        $this->authorizeOwner($project);

        $project->members()->detach($user->id);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Participante eliminado del proyecto.');
    }

    private function authorizeOwner(Project $project): void
    {
        abort_unless($project->user_id === auth()->id(), 403);
    }
}