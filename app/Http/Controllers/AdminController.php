<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        // No necesitamos middleware aquí porque ya está aplicado en las rutas
    }

    public function index()
    {
        $profesores = User::where('rol', 'profesor')->get();
        $administradores = User::where('rol', 'admin')->get();
        return view('admin.index', compact('profesores', 'administradores'));
    }

    public function showAssignMateriasForm(Request $request, $userId = null)
    {
        $profesores = User::where('rol', 'profesor')->get();
        $user = $userId ? User::findOrFail($userId) : new User;

        $materias = Materia::with('curso')->get();
        return view('admin.assign-materias', compact('profesores', 'user', 'materias'));
    }

    public function assignMaterias(Request $request, User $user)
    {
        $request->validate([
            'materia_ids' => 'array',
            'materia_ids.*' => 'exists:materias,id',
        ]);

        $user->materias()->sync($request->materia_ids ?? []);
        return redirect()->route('admin.assign-materias.form', ['user' => $user->id])
            ->with('success', 'Materias asignadas correctamente.');
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        Log::info('Datos recibidos en AdminController@register', $request->all());

        $validatedData = $request->validate([
            'dni' => 'required|string|max:10|unique:users,dni',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:profesor,admin',
        ], [
            'dni.unique' => 'El DNI ya está registrado.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        try {
            $user = User::create([
                'dni' => $validatedData['dni'],
                'nombre' => $validatedData['nombre'],
                'apellido' => $validatedData['apellido'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'rol' => $validatedData['rol'],
            ]);

            Log::info('Usuario creado exitosamente', ['user_id' => $user->id]);

            return redirect()->route('admin.register.form')->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Error al registrar el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'dni' => 'required|string|max:10|unique:users,dni,' . $user->id,
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'rol' => 'required|in:profesor,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'rol' => $request->rol,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('admin.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();
        return redirect()->route('admin.index')->with('success', 'Usuario eliminado correctamente.');
    }
}