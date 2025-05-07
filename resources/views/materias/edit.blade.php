@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Editar Materia</h2>

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

                        <form action="{{ route('materias.update', $materia) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $materia->nombre) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea name="descripcion" id="descripcion" class="form-control">{{ old('descripcion', $materia->descripcion) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="curso_id" class="form-label">Curso</label>
                                <select name="curso_id" id="curso_id" class="form-control" required>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ old('curso_id', $materia->curso_id) == $curso->id ? 'selected' : '' }}>
                                            {{ $curso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="profesores" class="form-label">Profesores</label>
                                <select name="profesores[]" id="profesores" multiple class="form-control" style="height: 150px;">
                                    @foreach($profesores as $prof)
                                        <option value="{{ $prof->id }}" {{ in_array($prof->id, old('profesores', $profesoresSeleccionados)) ? 'selected' : '' }}>
                                            {{ $prof->nombre }} {{ $prof->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                <a href="{{ route('materias.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection