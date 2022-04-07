<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function verificarCorreo(Request $request)
    {
       
        if($request->ajax()){
            $mail = $request->get('correo');
            if($mail){
                $correoEncontrado = DB::table('users')->where('correo', 'like', $correo. '%')->get();
                    if($correoEncontrado){
                        return $correoEncontrado;    
                    }
            }
            
        }   
        return redirect('/login');
    }   
}
