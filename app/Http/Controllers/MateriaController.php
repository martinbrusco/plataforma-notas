<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with(['curso', 'profesores'])->get()->unique(function ($materia) {
            return $materia->nombre . '|' . $materia->curso_id;
        });
        return view('materias.index', compact('materias'));
    }

    public function create()
    {
        $cursos = Curso::all();
        $profesores = User::where('rol', 'profesor')->get();
        return view('materias.create', compact('cursos', 'profesores'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:100|unique:materias,nombre,NULL,id,curso_id,' . $request->curso_id,
                'descripcion' => 'nullable|string',
                'curso_id' => 'required|exists:cursos,id',
                'profesores' => 'nullable|array',
                'profesores.*' => 'exists:users,id',
            ]);
    
            $materia = Materia::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'curso_id' => $request->curso_id,
            ]);
    
            if ($request->has('profesores')) {
                $materia->profesores()->sync($request->profesores);
            }
    
            return redirect()->route('materias.index')->with('success', 'Materia creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('materias.index')->with('error', 'No se puede crear la materia porque ya existe una con ese nombre en el curso seleccionado.');
        } catch (\Exception $e) {
            return redirect()->route('materias.index')->with('error', 'Ocurrió un error al crear la materia. Inténtalo de nuevo.');
        }
    }

    public function edit(Materia $materia)
    {
        $cursos = Curso::all();
        $profesores = User::where('rol', 'profesor')->get();
        $profesoresSeleccionados = $materia->profesores->pluck('id')->toArray();

        return view('materias.edit', compact('materia', 'cursos', 'profesores', 'profesoresSeleccionados'));
    }

    public function update(Request $request, Materia $materia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'curso_id' => 'required|exists:cursos,id',
            'profesores' => 'nullable|array',
            'profesores.*' => 'exists:users,id'
        ]);

        $materia->update($request->only('nombre', 'descripcion', 'curso_id'));
        $materia->profesores()->sync($request->profesores);

        return redirect()->route('materias.index')->with('success', 'Materia actualizada correctamente.');
    }

    public function destroy(Materia $materia)
    {
        $materia->profesores()->detach();
        $materia->delete();

        return redirect()->route('materias.index')->with('success', 'Materia eliminada correctamente.');
    }
}
