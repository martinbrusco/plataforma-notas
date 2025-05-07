<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Estudiante;
use App\Models\Materia;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('es_profesor');
    }

    // Listado de notas del profesor autenticado, con filtro por curso
    public function index(Request $request)
    {
        $curso_id = $request->input('curso_id');
        $user = Auth::user();
    
        // Obtener las materias asignadas al profesor
        $materias = $user->materias;
    
        // Si no tiene materias asignadas, devolver una vista vacía
        if ($materias->isEmpty()) {
            $cursos = collect();
            $notas = collect();
            return view('notas.index', compact('notas', 'cursos', 'curso_id'));
        }
    
        // Obtener los cursos asociados a las materias
        $cursos = Curso::whereIn('id', $materias->pluck('curso_id')->unique())->get();
    
        // Filtrar las notas estrictamente por las materias asignadas
        $materiaIds = $materias->pluck('id')->toArray();
        $notas = Nota::with(['alumno', 'asignatura.curso'])
            ->where('user_id', $user->id)
            ->whereIn('materia_id', $materiaIds)
            ->when($curso_id, function ($query, $curso_id) {
                $query->whereHas('alumno', function ($q) use ($curso_id) {
                    $q->where('curso_id', $curso_id);
                });
            })
            ->get();
    
        return view('notas.index', compact('notas', 'cursos', 'curso_id'));
    }

    // Formulario para crear una nueva nota
    public function create()
    {
        $user = Auth::user();
        $materias = $user->materias; // Materias asignadas al profesor
        $cursos_ids = $materias->pluck('curso_id')->unique(); // IDs de los cursos asociados a las materias
        $estudiantes = Estudiante::whereIn('curso_id', $cursos_ids)->get(); // Solo estudiantes de esos cursos
    
        $estudiante_id = request()->input('estudiante_id', null);
        $materias = collect();
    
        if ($estudiante_id) {
            $estudiante = Estudiante::find($estudiante_id);
            if ($estudiante && $estudiante->curso_id) {
                $materias = Materia::where('curso_id', $estudiante->curso_id)
                    ->whereIn('id', $user->materias->pluck('id'))
                    ->get();
            }
        }
    
        return view('notas.create', compact('estudiantes', 'materias', 'estudiante_id'));
    }

    // Almacenar una nueva nota
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'materia_id' => 'required|exists:materias,id',
            'trimestre1' => 'nullable|numeric|min:0|max:10',
            'trimestre2' => 'nullable|numeric|min:0|max:10',
            'trimestre3' => 'nullable|numeric|min:0|max:10',
        ]);
    
        // Verificar si ya existe una nota para esta combinación
        $existingNota = Nota::where('estudiante_id', $request->estudiante_id)
            ->where('materia_id', $request->materia_id)
            ->where('user_id', Auth::id())
            ->first();
    
        if ($existingNota) {
            return redirect()->route('notas.index')
                ->with('error', 'No se puede agregar la nota porque ya existe una registrada para este estudiante y materia. Edítela si desea actualizarla.');
        }
    
        // Calcular la nota_final si los tres trimestres están presentes
        $nota_final = null;
        if ($request->filled('trimestre1') && $request->filled('trimestre2') && $request->filled('trimestre3')) {
            $nota_final = round(($request->trimestre1 + $request->trimestre2 + $request->trimestre3) / 3, 2);
        }
    
        Nota::create([
            'estudiante_id' => $request->estudiante_id,
            'materia_id' => $request->materia_id,
            'user_id' => Auth::id(),
            'trimestre1' => $request->trimestre1,
            'trimestre2' => $request->trimestre2,
            'trimestre3' => $request->trimestre3,
            'nota_final' => $nota_final,
        ]);
    
        return redirect()->route('notas.index')->with('success', 'Nota registrada correctamente.');
    }

    // Formulario para editar una nota
    public function edit(Nota $nota)
    {
        $user = Auth::user();
    
        if ($nota->user_id !== $user->id) {
            abort(403);
        }
    
        $materias_asignadas = $user->materias; // Materias asignadas al profesor
        $cursos_ids = $materias_asignadas->pluck('curso_id')->unique(); // IDs de los cursos asociados
        $estudiantes = Estudiante::whereIn('curso_id', $cursos_ids)->get(); // Solo estudiantes de esos cursos
    
        // Cargar la relación alumno
        $nota->load('alumno');
        if (!$nota->alumno) {
            abort(404, 'El estudiante asociado a esta nota no existe.');
        }
    
        // Filtrar materias según el curso del estudiante y las materias del profesor
        $materias = Materia::where('curso_id', $nota->alumno->curso_id)
            ->whereIn('id', $materias_asignadas->pluck('id'))
            ->get();
    
        return view('notas.edit', compact('nota', 'materias', 'estudiantes'));
    }

    // Actualizar una nota existente
    public function update(Request $request, Nota $nota)
    {
        $user = Auth::user();

        if ($nota->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'materia_id' => 'required|exists:materias,id',
            'trimestre1' => 'nullable|numeric|min:0|max:10',
            'trimestre2' => 'nullable|numeric|min:0|max:10',
            'trimestre3' => 'nullable|numeric|min:0|max:10',
        ]);

        // Verificar si se intenta cambiar a una combinación existente (excluyendo la nota actual)
        $existingNota = Nota::where('estudiante_id', $request->estudiante_id)
            ->where('materia_id', $request->materia_id)
            ->where('user_id', $user->id)
            ->where('id', '!=', $nota->id)
            ->first();

        if ($existingNota) {
            return redirect()->back()
                ->with('error', 'Ya existe una nota registrada para esta combinación de estudiante y materia.')
                ->withInput();
        }

        // Calcular la nota_final si los tres trimestres están presentes
        $nota_final = null;
        if ($request->filled('trimestre1') && $request->filled('trimestre2') && $request->filled('trimestre3')) {
            $nota_final = round(($request->trimestre1 + $request->trimestre2 + $request->trimestre3) / 3, 2);
        }

        $nota->update([
            'estudiante_id' => $request->estudiante_id,
            'materia_id' => $request->materia_id,
            'trimestre1' => $request->trimestre1,
            'trimestre2' => $request->trimestre2,
            'trimestre3' => $request->trimestre3,
            'nota_final' => $nota_final,
        ]);

        return redirect()->route('notas.index')->with('success', 'Nota actualizada correctamente.');
    }
    // Eliminar una nota
    public function destroy(Nota $nota)
    {
        $user = Auth::user();

        if ($nota->user_id !== $user->id) {
            abort(403);
        }

        $nota->delete();

        return redirect()->route('notas.index')->with('success', 'Nota eliminada correctamente.');
    }
}
