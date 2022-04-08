<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
        $users = User::all();

        $token = csrf_token();
        // if(isset($users)){
        //     return response()->json([
        //         'users'=>$users,
        //         'tocken'=>$token
        //     ]);
        // }
        // else{
        //     return response()->json([
        //         'error'=>'Data not found'
        //     ]);
        // }
        return view('usuarios.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
        return view('auth.register');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = New User();
        $user->tipoUsuario = $request->tipoUsuario;
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->telefono = $request->telefono;
        $user->contrasenia =Hash::make($request->contrasenia);
        $user->correo = $request->correo;
        $user->save();
        return redirect('users');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $data = User::find($user);
        if(isset($data)){
            return response()->json([
                'user'=>$data
            ]);
        }
        else{
            return response()->json([
                'error'=>'Data not found'
            ]);
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
        $user = User::find($id)->first();
        return view('usuarios.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$user)
    {
        $data = User::find($user);
        $data->update($request->all());
        return redirect('users');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $data = User::find($user);
        $data->delete();
        
        return redirect('users');
    }

    public function login(Request $request){
        $user= User::where('correo', $request->correo)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->contrasenia, $user->contrasenia)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
            $token = $user->createToken('my-app-token')->plainTextToken;
            $users = User::all();
            // $response = [
            //     'user' => $user,
            //     'token' => $token
            // ];
            // return redirect()->route('users')->with( ['token' => $token] );
            return  view('users.index', compact('token', 'users'));
    }
    public function logout($user){
        // Revoke a specific user token
        // Revoke a specific token...
        //$data = User::find($user);
        //$data->tokens()->delete();
        auth()->user()->tokens()->delete();
        return[
            'message' => 'Logeed out'
        ];
        
    }



    /**
     * Update the password to User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updatePSW(Request $request, $user){
        $data = User::find($user);
        $data->contrasenia = $request->contrasenia;
        $data->save();
        if(isset($data)){
            return response()->json([
                'status'=>200,
                'message'=>'Data successfully updated'
            ]);
        }
        else{
            return response()->json([
                'status'=>400,
                'error'=>"something went wrong"
            ]);
        }
    }

}
