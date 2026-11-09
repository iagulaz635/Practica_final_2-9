<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // Campos que permitimos guardar masivamente (create / update con $request->all()).
    // OJO: user_id NO está aquí a propósito (se asigna desde el controlador).
    protected $fillable = ['title', 'description', 'due_date', 'status'];

    // Relación 1:N (lado "N"): un proyecto pertenece a un usuario propietario.
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación N:N: un proyecto tiene muchos usuarios participantes.
    // La tabla pivote (project_user) guarda además el rol y las horas.
    public function participants()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role', 'contribution_hours');
    }
}