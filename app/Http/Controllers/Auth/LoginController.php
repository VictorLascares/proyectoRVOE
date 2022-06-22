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

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->remember)) {
            // dd($request->remember);
            return redirect()->intended('requisitions');
        } else {
            return back()->with('mensaje', 'Credenciales Incorrectas');
        }
    }
}
