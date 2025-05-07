<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'dni',
        'nombre',
        'apellido',
        'email',
        'password',
        'rol'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación muchos a muchos con Materia
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'profesor_materia', 'user_id', 'materia_id')->withTimestamps();
    }
}