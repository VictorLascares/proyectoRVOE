<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function iniciar(){
        return view('iniciar');
    }

    public function salir(){
        Auth::logout();
        return redirect('/');
    }

    public function validar(Request $request){
        $nombre = $request->input('correo');
        $usuario = User::where('correo',$nombre)->first();
        if(is_null($usuario)){
            return redirect('/login')->with('error','Usuario no registrado');
        }
        else{
            $password = $request->input('contrasenia');
            $password_bd = $usuario->contrasenia;
            if(Hash::check($password, $password_bd)){
                Auth::login($usuario);
                return redirect('/iniciar');
            }else{
                return redirect('/login')->with('error','Usuario o Password incorrecto.');
            }
        }
    }
}
