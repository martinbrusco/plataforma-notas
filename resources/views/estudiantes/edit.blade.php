@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Editar Estudiante</h1>

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('estudiantes.update', $estudiante->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" name="dni" id="dni" class="form-control" value="{{ old('dni', $estudiante->dni) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $estudiante->nombre) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido', $estudiante->apellido) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $estudiante->telefono) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $estudiante->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="curso_id" class="form-label">Curso</label>
                                <select name="curso_id" id="curso_id" class="form-control" required>
                                    @foreach ($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ old('curso_id', $estudiante->curso_id) == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection