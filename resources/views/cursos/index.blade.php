@extends('layouts.app')

   @section('content')
   <div class="container py-4">
       <div class="card">
           <div class="card-body">
               <h2 class="mb-4">Listado de Cursos</h2>

               <div class="mb-3">
                   <a href="{{ route('cursos.create') }}" class="btn btn-primary">Nuevo Curso</a>
               </div>

               @if($cursos->isEmpty())
                   <div class="alert alert-info">No hay cursos registrados todavía.</div>
               @else
                   <div class="table-responsive">
                       <table class="table">
                           <thead class="table-light">
                               <tr>
                                   <th>Nombre</th>
                                   <th>Descripción</th>
                                   <th class="text-center">Acciones</th>
                               </tr>
                           </thead>
                           <tbody>
                               @foreach($cursos as $curso)
                               <tr>
                                   <td>{{ $curso->nombre }}</td>
                                   <td>{{ $curso->descripcion }}</td>
                                   <td class="text-center">
                                       <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                       <form action="{{ route('cursos.destroy', $curso->id) }}" method="POST" style="display:inline-block;">
                                           @csrf
                                           @method('DELETE')
                                           <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este curso?')">Eliminar</button>
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