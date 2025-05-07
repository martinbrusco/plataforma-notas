<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'curso_id'
    ];

    // Relación con el curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación con profesores
    public function profesores()
    {
        return $this->belongsToMany(User::class, 'profesor_materia')
                    ->withTimestamps();
    }

    // Relación con notas
    public function notas()
    {
        return $this->hasMany(Nota::class, 'materia_id');
    }
}