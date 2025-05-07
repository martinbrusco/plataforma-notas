@extends('layouts.app')

   @section('content')
       <div class="container py-4">
           <div class="card">
               <div class="card-body">
                   <h2 class="mb-4">Listado de Materias</h2>
                   <a href="{{ route('materias.create') }}" class="btn btn-primary mb-3">Nueva Materia</a>

                   @if(session('success'))
                       <div class="alert alert-success">{{ session('success') }}</div>
                   @endif

                   @if(session('error'))
                       <div class="alert alert-danger">{{ session('error') }}</div>
                   @endif

                   @if($materias->isEmpty())
                       <div class="alert alert-info">No hay materias registradas.</div>
                   @else
                       <div class="table-responsive">
                           <table class="table">
                               <thead>
                                   <tr>
                                       <th>Nombre</th>
                                       <th>Curso</th>
                                       <th>Profesores</th>
                                       <th>Acciones</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @foreach($materias as $materia)
                                       <tr>
                                           <td>{{ $materia->nombre }}</td>
                                           <td>{{ $materia->curso->nombre ?? 'Sin curso' }}</td>
                                           <td>
                                               @forelse($materia->profesores as $prof)
                                                   {{ $prof->nombre }} {{ $prof->apellido }}<br>
                                               @empty
                                                   Sin profesores asignados
                                               @endforelse
                                           </td>
                                           <td>
                                               <a href="{{ route('materias.edit', $materia) }}" class="btn btn-sm btn-warning">Editar</a>
                                               <form action="{{ route('materias.destroy', $materia) }}" method="POST" class="d-inline">
                                                   @csrf
                                                   @method('DELETE')
                                                   <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                                               </form>
                                           </td>
                                       </tr>
                                   @endforeach
                               </tbody>
                           </table>
                       </div>
                   @endif

                   <a href="{{ route('admin.index') }}" class="btn btn-secondary mt-3">Volver</a>
               </div>
           </div>
       </div>
   @endsection