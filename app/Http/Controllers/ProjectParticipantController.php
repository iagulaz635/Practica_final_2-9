<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectParticipantController extends Controller
{
    /**
     * Añade un usuario como participante del proyecto (relación N:N).
     */
    public function store(Request $request, string $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorizeOwner($project);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|max:50',
            'contribution_hours' => 'nullable|numeric|min:0',
        ]);

        // El propietario no se añade como participante: ya es el dueño del proyecto
        if ((int) $validated['user_id'] === $project->user_id) {
            return back()->withInput()
                         ->withErrors(['user_id' => 'El propietario ya forma parte del proyecto.']);
        }

        // Evita duplicados (además existe un índice único en la tabla pivote)
        $yaParticipa = $project->participants()
                               ->where('users.id', $validated['user_id'])
                               ->exists();
        if ($yaParticipa) {
            return back()->withInput()
                         ->withErrors(['user_id' => 'Este usuario ya participa en el proyecto.']);
        }

        // attach(id, [datos extra de la tabla pivote])
        $project->participants()->attach($validated['user_id'], [
            'role' => $validated['role'],
            'contribution_hours' => $validated['contribution_hours'] ?? 0,
        ]);

        return redirect()->route('projects.show', $project->id)
                         ->with('success', 'Participante añadido correctamente.');
    }

    /**
     * Modifica el rol y las horas de un participante.
     */
    public function update(Request $request, string $projectId, string $participantId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorizeOwner($project);

        // Si el usuario no participa en este proyecto, error 404
        $project->participants()->findOrFail($participantId);

        // Bolsa de errores propia ('participantUpdate') para que estos errores no
        // aparezcan también en el formulario de añadir participante
        $validated = $request->validateWithBag('participantUpdate', [
            'role' => 'required|max:50',
            'contribution_hours' => 'required|numeric|min:0',
        ]);

        // Modifica solo los datos de la pivote de ESTE participante
        $project->participants()->updateExistingPivot($participantId, $validated);

        return redirect()->route('projects.show', $project->id)
                         ->with('success', 'Participante actualizado correctamente.');
    }

    /**
     * Quita a un usuario del proyecto (no borra al usuario, solo la relación).
     */
    public function destroy(string $projectId, string $participantId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorizeOwner($project);

        $project->participants()->findOrFail($participantId);

        $project->participants()->detach($participantId);

        return redirect()->route('projects.show', $project->id)
                         ->with('success', 'Participante eliminado del proyecto.');
    }

    /**
     * Autorización: solo el propietario puede gestionar los participantes.
     */
    private function authorizeOwner(Project $project): void
    {
        abort_if(
            (int) $project->user_id !== Auth::id(),
            403,
            'Solo el propietario del proyecto puede gestionar los participantes.'
        );
    }
}