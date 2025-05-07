@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="text-center text-3xl font-bold mb-6">Bienvenido, {{ Auth::user()->nombre }} {{ Auth::user()->apellido }} (Administrador)</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-xl font-semibold">Gestión de Usuarios</h5>
                        <p class="card-text">Registra nuevos profesores o administradores.</p>
                        <a href="{{ route('admin.register.form') }}" class="btn btn-primary">Registrar Usuario</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title text-xl font-semibold">Asignación de Privilegios</h5>
                        <p class="card-text">Edición de responsabilidades.</p>
                        <a href="{{ route('admin.index') }}" class="btn btn-primary"> Profesores / Administradores / Estudiantes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection