<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\ProjectRequest;

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

        return view('projects.show', compact('project'));
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

    private function authorizeOwner(Project $project): void
    {
        abort_unless($project->user_id === auth()->id(), 403);
    }
}