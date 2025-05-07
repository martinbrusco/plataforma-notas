@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-center mb-4">Registrar Estudiante</h1>

                    <form action="{{ route('estudiantes.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" name="dni" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="curso_id" class="form-label">Curso</label>
                            <select name="curso_id" class="form-control" required>
                                <option value="">Seleccione un curso</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Registrar</button>
                            <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection