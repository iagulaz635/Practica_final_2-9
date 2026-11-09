<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // with('owner') carga el propietario de todos los proyectos de una sola vez
        $projects = Project::with('owner')->latest()->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar (validate() devuelve solo los campos validados)
        $validated = $request->validate($this->validationRules());

        // 2. Crear el proyecto a través de la relación: user_id se rellena solo
        $project = Auth::user()->projects()->create($validated);

        // 3. Redirigir al detalle del proyecto con un mensaje flash
        return redirect()->route('projects.show', $project->id)
                         ->with('success', 'Proyecto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Cargamos también el propietario y los participantes (con role y horas)
        $project = Project::with(['owner', 'participants'])->findOrFail($id);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        $this->authorizeOwner($project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $this->authorizeOwner($project);

        $validated = $request->validate($this->validationRules());
        $project->update($validated);

        return redirect()->route('projects.show', $project->id)
                         ->with('success', 'Proyecto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $this->authorizeOwner($project);

        $project->delete();

        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto eliminado correctamente.');
    }

    /**
     * Reglas de validación compartidas por store() y update().
     */
    private function validationRules(): array
    {
        return [
            'title' => 'required|max:100',
            'description' => 'nullable',
            'due_date' => 'required|date',
            'status' => 'required|in:pendiente,en curso,finalizado',
        ];
    }

    /**
     * Autorización: solo el propietario puede editar o borrar el proyecto.
     * Si no lo es, se corta la ejecución con un error 403 (Prohibido).
     */
    private function authorizeOwner(Project $project): void
    {
        abort_if(
            $project->user_id !== Auth::id(),
            403,
            'Solo el propietario del proyecto puede realizar esta acción.'
        );
    }
}