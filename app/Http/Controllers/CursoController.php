<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Mostrar la lista de cursos.
     */
    public function index()
    {
        $cursos = Curso::all();
        return view('cursos.index', compact('cursos'));
    }

    /**
     * Mostrar formulario para crear un nuevo curso.
     */
    public function create()
    {
        return view('cursos.create');
    }

    /**
     * Guardar un nuevo curso en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Curso::create($request->only('nombre', 'descripcion'));

        return redirect()->route('cursos.index')->with('success', 'Curso creado correctamente.');
    }

    /**
     * Mostrar formulario para editar un curso.
     */
    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Actualizar la información del curso.
     */
    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $curso->update($request->only('nombre', 'descripcion'));

        return redirect()->route('cursos.index')->with('success', 'Curso actualizado correctamente.');
    }

    /**
     * Eliminar un curso de la base de datos.
     */
    public function destroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('cursos.index')->with('success', 'Curso eliminado correctamente.');
    }
}
