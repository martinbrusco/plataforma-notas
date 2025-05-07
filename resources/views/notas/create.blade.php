@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Registrar Nota</h2>

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

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Formulario para seleccionar estudiante (GET) -->
                        <form action="{{ route('notas.create') }}" method="GET" id="estudianteForm" class="mb-4">
                            <div class="mb-3">
                                <label for="estudiante_id" class="form-label">Estudiante</label>
                                <select name="estudiante_id" id="estudiante_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">Selecciona un estudiante</option>
                                    @foreach($estudiantes as $estudiante)
                                        <option value="{{ $estudiante->id }}" {{ $estudiante_id == $estudiante->id ? 'selected' : '' }}>
                                            {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estudiante_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>

                        <!-- Formulario para registrar nota (POST) -->
                        <form action="{{ route('notas.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="materia_id" class="form-label">Materia</label>
                                <select name="materia_id" id="materia_id" class="form-control" required>
                                    <option value="">Selecciona una materia</option>
                                    @foreach($materias as $materia)
                                        <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                            {{ $materia->nombre }} ({{ $materia->curso->nombre ?? 'Sin curso' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('materia_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="trimestre1" class="form-label">Trimestre 1</label>
                                <input type="number" name="trimestre1" id="trimestre1" class="form-control" value="{{ old('trimestre1') }}" step="0.1" min="0" max="10">
                                @error('trimestre1')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="trimestre2" class="form-label">Trimestre 2</label>
                                <input type="number" name="trimestre2" id="trimestre2" class="form-control" value="{{ old('trimestre2') }}" step="0.1" min="0" max="10">
                                @error('trimestre2')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="trimestre3" class="form-label">Trimestre 3</label>
                                <input type="number" name="trimestre3" id="trimestre3" class="form-control" value="{{ old('trimestre3') }}" step="0.1" min="0" max="10">
                                @error('trimestre3')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nota Final</label>
                                <p class="form-control-plaintext">
                                    La nota final se calculará automáticamente como el promedio de los tres trimestres una vez que todos estén completos.
                                </p>
                            </div>

                            @if($estudiante_id)
                                <input type="hidden" name="estudiante_id" value="{{ $estudiante_id }}">
                            @endif

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Guardar</button>
                                <a href="{{ route('notas.index') }}" class="btn btn-secondary">Volver</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection