<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $nombre = $request->input('email');
        $usuario = User::where('email', $nombre)->first();
        if (is_null($usuario)) {
            return back()->with('mensaje', 'Usuario no registrado');
        } else {
            $password = $request->input('password');
            $password_bd = $usuario->password;
            // if (Has::check($password, $password_bd)) {
                Auth::login($usuario);
                if ($usuario->tipoUsuario == 'administrador') {
                    return redirect('users');
                } else {
                    return redirect('requisitions');
                }
            // } else {
            //     return back()->with('mensaje', 'Credenciales Incorrectas');
            // }
        }
    }
}
