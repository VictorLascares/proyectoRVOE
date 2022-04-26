<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Institution;

class SessionController extends Controller
{
  public function iniciar()
  {
    $institutions=Institution::all();
    $nombre = Auth::user()->nombres;
    return view('pages.dashboard', compact('nombre','institutions'));
  }

  public function salir()
  {
    Auth::logout();
    return redirect('/');
  }

  public function validar(Request $request)
  {
    $nombre = $request->input('correo');
    $usuario = User::where('correo', $nombre)->first();
    if (is_null($usuario)) {
      $error = 'Usuario no registrado';
      return view('pages.dashboard', compact('nombre'));
    } else {
      $password = $request->input('contrasenia');
      $password_bd = $usuario->contrasenia;
      if (Hash::check($password, $password_bd)) {
        Auth::login($usuario);
        if ($usuario->tipoUsuario == 'administrador') {
          return redirect('users');
        } else {
          
          return redirect('iniciar');
        }
      } else {
        $error = 'Usuario o Password incorrecto.';
        return redirect('/')->with(compact('error'));
      }
    }
  }
}
