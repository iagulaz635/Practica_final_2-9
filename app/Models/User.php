<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación 1:N (lado "1"): proyectos que el usuario ha creado (es el propietario).
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // Relación N:N: proyectos en los que el usuario participa.
    // La tabla pivote (project_user) guarda además el rol y las horas.
    public function participatingProjects()
    {
        return $this->belongsToMany(Project::class)
                    ->withPivot('role', 'contribution_hours');
    }
}