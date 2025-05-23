<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->rol === 'admin') {
            return view('home.admin');
        } elseif ($user->rol === 'profesor') {
            return view('home.profesor');
        }

        return redirect('/')->with('error', 'Rol no reconocido.');
    }
}