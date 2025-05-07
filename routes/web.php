<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas de autenticación
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Ruta de inicio
Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas con auth
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Ruta CRUD estudiante
    Route::resource('estudiantes', EstudianteController::class);

    // Ruta CRUD notas
    Route::resource('notas', NotaController::class);
    Route::get('/notas/resumen', [NotaController::class, 'resumen'])->name('notas.resumen');

    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas protegidas para administradores (auth y es_admin)
Route::middleware(['auth', 'es_admin'])->group(function () {
    // Ruta CRUD curso
    Route::resource('cursos', CursoController::class);

    // Ruta CRUD materia
    Route::resource('materias', MateriaController::class);

    // Rutas de administración
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register.form');
    Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register');

    // Rutas para editar y eliminar usuarios
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // Panel administrativo para asignar materias
    Route::get('/admin/users/{user}/assign-materias', [AdminController::class, 'showAssignMateriasForm'])->name('admin.assign-materias.form');
    Route::post('/admin/users/{user}/assign-materias', [AdminController::class, 'assignMaterias'])->name('admin.assign-materias');
});