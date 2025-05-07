<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 20)->unique(); // Longitud específica para DNI
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable(); // Para verificación de email
            $table->string('password');
            $table->enum('rol', ['admin', 'profesor'])->default('profesor'); // Sistema de roles
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Eliminación suave
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
