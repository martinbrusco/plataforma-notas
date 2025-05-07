<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = \App\Models\Estudiante::with('curso')->get();
        return view('estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cursos = \App\Models\Curso::all();
        return view('estudiantes.create', compact('cursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|max:20|unique:estudiantes',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:estudiantes',
            'curso_id' => 'required|exists:cursos,id',
        ]);
    
        \App\Models\Estudiante::create($request->all());
    
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $estudiante = \App\Models\Estudiante::findOrFail($id);
        $cursos = \App\Models\Curso::all();
        return view('estudiantes.edit', compact('estudiante', 'cursos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $estudiante = \App\Models\Estudiante::findOrFail($id);

        $request->validate([
            'dni' => 'required|string|max:20|unique:estudiantes,dni,' . $id,
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:estudiantes,email,' . $id,
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $estudiante->update($request->all());

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $estudiante = \App\Models\Estudiante::findOrFail($id);
            $estudiante->delete();

            return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado con éxito.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar estudiante: ' . $e->getMessage(), [
                'estudiante_id' => $id,
                'exception' => $e->getTraceAsString(),
            ]);

            return redirect()->route('estudiantes.index')->with('error', 'Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }
}