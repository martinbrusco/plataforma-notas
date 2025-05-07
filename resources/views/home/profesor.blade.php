@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="text-center text-3xl font-bold mb-6">Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellido }} (Profesor)</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-xl font-semibold">Gestión de Notas</h5>
                        <p class="card-text">Consulta y registra notas de tus alumnos.</p>
                        <a href="{{ route('notas.index') }}" class="btn btn-primary">Ver Notas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection