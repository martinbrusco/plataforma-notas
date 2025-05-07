<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudiante_id',
        'materia_id',
        'user_id',
        'trimestre1',
        'trimestre2',
        'trimestre3',
        'nota_final'
    ];

    protected $casts = [
        'trimestre1' => 'float',
        'trimestre2' => 'float',
        'trimestre3' => 'float',
        'nota_final' => 'float'
    ];

    // Relación con estudiante
    public function alumno()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación con materia
    public function asignatura()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    // Relación con profesor
    public function profesor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}