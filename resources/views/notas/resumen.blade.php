@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Resumen de Notas Finales</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Materia</th>
                <th>Nota Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notas as $nota)
                <tr>
                    <td>{{ $nota->alumno->nombre }}</td>
                    <td>{{ $nota->asignatura->nombre }}</td>
                    <td>{{ $nota->nota_final }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
