@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4">Registrar Nuevo Usuario</h2>


                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('admin.register') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" name="dni" id="dni" class="form-control" value="{{ old('dni') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select name="rol" id="rol" class="form-control" required>
                                    <option value="profesor" {{ old('rol') == 'profesor' ? 'selected' : '' }}>Profesor</option>
                                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="{{ route('home') }}" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection