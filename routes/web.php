<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectParticipantController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de proyectos (7 rutas REST)
    Route::resource('projects', ProjectController::class);

    // Gestión de participantes (N:N): añadir, modificar rol/horas y quitar
    Route::resource('projects.participants', ProjectParticipantController::class)
         ->only(['store', 'update', 'destroy']);
});

require __DIR__.'/auth.php';