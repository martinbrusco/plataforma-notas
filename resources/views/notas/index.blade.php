@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Listado de Notas</h2>
        <a href="{{ route('notas.create') }}" class="btn btn-primary">Registrar nueva nota</a>

        <form method="GET" action="{{ route('notas.index') }}" class="mb-3">
            <label for="curso_id">Filtrar por curso:</label>
            <select name="curso_id" id="curso_id" class="form-control w-25 d-inline-block" onchange="this.form.submit()">
                <option value="">Todos los cursos</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ $curso_id == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nombre }}
                    </option>
                @endforeach
            </select>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Materia</th>
                    <th>Trimestre 1</th>
                    <th>Trimestre 2</th>
                    <th>Trimestre 3</th>
                    <th>Nota Final</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notas as $nota)
                    <tr>
                        <td>{{ $nota->alumno->nombre }}</td>
                        <td>{{ $nota->asignatura->nombre }}</td>
                        <td>{{ $nota->trimestre1 }}</td>
                        <td>{{ $nota->trimestre2 }}</td>
                        <td>{{ $nota->trimestre3 }}</td>
                        <td>{{ $nota->nota_final }}</td>
                        <td>
                            <a href="{{ route('notas.edit', $nota->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('notas.destroy', $nota->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Volver</a>
    </div>
@endsection