<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EsProfesor
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->rol !== 'profesor') {
            abort(403, 'Acceso denegado. Solo los profesores pueden acceder.');
        }
        return $next($request);
    }
}