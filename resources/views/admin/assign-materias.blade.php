@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Asignar Materias a Profesores</h2>

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

                        <div class="mb-4">
                            <h4>Selecciona un profesor:</h4>
                            <form method="GET" action="">
                                <select name="user" onchange="this.form.submit()" class="form-control w-100">
                                    <option value="">-- Selecciona un profesor --</option>
                                    @foreach($profesores as $profesor)
                                        <option value="{{ $profesor->id }}" {{ optional($user)->id == $profesor->id ? 'selected' : '' }}>
                                            {{ $profesor->nombre }} {{ $profesor->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        @if($user->id ?? false)
                            <h4 class="mb-3">Asignando materias a: {{ $user->nombre }} {{ $user->apellido }}</h4>
                            <form method="POST" action="{{ route('admin.assign-materias', $user) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="materia_ids" class="form-label">Materias</label>
                                    <select name="materia_ids[]" id="materia_ids" class="form-control" multiple style="height: 200px;">
                                        @foreach($materias as $materia)
                                            <option value="{{ $materia->id }}" {{ $user->materias->contains($materia->id) ? 'selected' : '' }}>
                                                {{ $materia->nombre }} ({{ $materia->curso->nombre ?? 'Sin curso' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">Guardar</button>
                                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">Volver</a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection