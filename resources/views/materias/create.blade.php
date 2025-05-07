@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Materia</h2>

    <form action="{{ route('materias.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}">
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Curso</label>
            <select name="curso_id" class="form-control">
                <option value="">Seleccione</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Profesores</label>
            <select name="profesores[]" multiple class="form-control">
                @foreach($profesores as $prof)
                    <option value="{{ $prof->id }}">{{ $prof->nombre }} {{ $prof->apellido }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('materias.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
