<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    // Relación con estudiantes
    public function alumnos()
    {
        return $this->hasMany(Estudiante::class, 'curso_id');
    }

    // Relación con materias
    public function asignaturas()
    {
        return $this->hasMany(Materia::class, 'curso_id');
    }
}