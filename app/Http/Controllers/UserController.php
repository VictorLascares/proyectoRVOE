<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
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
      $user = new User();
      $user->tipoUsuario = $request->tipoUsuario;
      $user->nombres = $request->nombres;
      $user->apellidos = $request->apellidos;
      $user->telefono = $request->telefono;
      $user->contrasenia = Hash::make($request->contrasenia);
      $user->correo = $request->correo;
      $user->save();
      return redirect('users');
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
      $data = User::find($user);
      $data->update($request->all());
      return redirect('users');
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

  public function login(Request $request)
  {
      $user = User::where('correo', $request->correo)->first();
      // print_r($data);
      if (!$user || !Hash::check($request->contrasenia, $user->contrasenia)) {
        return response([
          'message' => ['These credentials do not match our records.']
        ], 404);
      }

      $token = $user->createToken('my-app-token')->plainTextToken;
      $users = User::all();
      return  view('users.index', compact('token', 'users'));
  }
  public function logout()
  {
    Auth::logout();
    return redirect(('/'));
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
