@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-4">Panel de Administración</h2>
                <div class="mb-3">
                    <a href="{{ route('estudiantes.index') }}" class="btn btn-primary">Agregar Estudiantes</a>
                    <a href="{{ route('cursos.index') }}" class="btn btn-primary">Gestionar Cursos</a>
                    <a href="{{ route('materias.index') }}" class="btn btn-primary">Gestionar Materias</a>
                </div>

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

                <h4 class="mb-3">Lista de Profesores</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($profesores as $profesor)
                                <tr>
                                    <td>{{ $profesor->nombre }} {{ $profesor->apellido }}</td>
                                    <td>{{ $profesor->email }}</td>
                                    <td>
                                        <a href="{{ route('admin.assign-materias.form', $profesor) }}" class="btn btn-primary btn-sm">Asignar Materias</a>
                                        <a href="{{ route('admin.users.edit', $profesor) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('admin.users.destroy', $profesor) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h4 class="mb-3">Lista de Administradores</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($administradores as $admin)
                                <tr>
                                    <td>{{ $admin->nombre }} {{ $admin->apellido }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $admin) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('admin.users.destroy', $admin) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Volver</a>
            </div>
        </div>
    </div>
@endsection