<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user() != null) {
            $users = User::all();
            $token = csrf_token();
            return view('usuarios.index', compact('users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user() != null) {
            return view('auth.register');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user() != null) {
            // Validacion
            $this->validate($request, [
                'nombres' => ['required', 'max:35'],
                'apellidos' => ['required', 'max:35'],
                'telefono' => ['required', 'max:10', 'min:10'],
                'tipoUsuario' => ['required'],
                'correo' => ['required', 'unique:users', 'correo', 'max:60'],
                'contrasenia' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()]
            ]);

            User::create([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'correo' => $request->correo,
                'telefono' => $request->telefono,
                'tipoUsuario' => $request->tipoUsuario,
                'correo' => $request->correo,
                'contrasenia' => Hash::make($request->contrasenia)
            ]);
            return redirect()->route('users');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        if (Auth::user() != null) {
            $data = User::find($user);
            if (isset($data)) {
                return response()->json([
                    'user' => $data
                ]);
            } else {
                return response()->json([
                    'error' => 'Data not found'
                ]);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user() != null) {
            $user = User::find($id)->first();
            return view('usuarios.edit', compact('user'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        if (Auth::user() != null) {
            // Validacion
            $this->validate($request, [
                'nombres' => ['required', 'max:35'],
                'apellidos' => ['required', 'max:35'],
                'telefono' => ['required', 'max:10', 'min:10'],
                'tipoUsuario' => ['required'],
                'correo' => ['required', 'unique:users', 'correo', 'max:60']
            ]);
            $data = User::find($user);
            $data->update($request->all());
            return redirect()->route('users');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        if (Auth::user() != null) {
            $data = User::find($user);
            $data->delete();
            return redirect('users');
        }
    }

    /**
     * Update the password to User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updatePSW(Request $request, $user)
    {
        if (Auth::user() != null) {
            $data = User::find($user);
            $data->contrasenia = Hash::make($request->contrasenia);
            $data->save();
        }
        return redirect(route('users.edit', $user));
    }
}
