<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Campos propios del proyecto
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->string('status', 20)->default('pendiente');

            // Relación 1:N (un usuario crea muchos proyectos): propietario del proyecto
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('projects');
    }
};