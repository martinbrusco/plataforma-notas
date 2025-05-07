<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'telefono',
        'email',
        'curso_id'
    ];

    // Relación con el curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación con las notas
    public function calificaciones()
    {
        return $this->hasMany(Nota::class, 'estudiante_id');
    }

    
}