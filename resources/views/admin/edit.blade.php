@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Editar Usuario</h2>

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

                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" name="dni" id="dni" class="form-control" value="{{ old('dni', $user->dni) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', $user->nombre) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" name="apellido" id="apellido" class="form-control" value="{{ old('apellido', $user->apellido) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select name="rol" id="rol" class="form-control" required>
                                    <option value="profesor" {{ $user->rol == 'profesor' ? 'selected' : '' }}>Profesor</option>
                                    <option value="admin" {{ $user->rol == 'admin' ? 'selected' : '' }}>Administrador</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">Actualizar</button>
                                <a href="{{ route('admin.index') }}" class="btn btn-secondary">Volver</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection