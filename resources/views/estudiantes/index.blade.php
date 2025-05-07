@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Estudiantes</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('estudiantes.create') }}" class="btn btn-primary mb-3">Nuevo Estudiante</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Curso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($estudiantes as $estudiante)
            <tr>
                <td>{{ $estudiante->dni }}</td>
                <td>{{ $estudiante->nombre }}</td>
                <td>{{ $estudiante->apellido }}</td>
                <td>{{ $estudiante->email }}</td>
                <td>{{ $estudiante->curso->nombre ?? 'Sin curso' }}</td>
                <td>
                    <a href="{{ route('estudiantes.edit', $estudiante->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('estudiantes.destroy', $estudiante->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar este estudiante?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection