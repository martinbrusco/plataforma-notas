@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Crear Curso</h2>

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

                        <form method="POST" action="{{ route('cursos.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del Curso</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción (opcional)</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="4">{{ old('descripcion') }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <a href="{{ route('cursos.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection