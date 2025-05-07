@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Editar Perfil</h2>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <!-- Agrega campos como nombre, email, contraseña, etc., según necesites -->
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection