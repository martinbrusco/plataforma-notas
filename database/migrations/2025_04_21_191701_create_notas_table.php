<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->decimal('trimestre1', 5, 2)->nullable();
            $table->decimal('trimestre2', 5, 2)->nullable();
            $table->decimal('trimestre3', 5, 2)->nullable();
            $table->decimal('nota_final', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['estudiante_id', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
