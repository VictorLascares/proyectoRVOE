<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        $i = 1;

        // if(isset($users)){
        //     return response()->json([
        //         'users'=>$users
        //     ]);
        // }
        // else{
        //     return response()->json([
        //         'error'=>'Data not found'
        //     ]);
        // }
        return view('users.index', compact('users', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
        return view('users.create');
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
        $user->contrasenia = $request->contrasenia;
        $user->correo = $request->correo;
        $data = $user->save();
        // if(!$data){
        //     return response()->json([
        //         'status'=>400,
        //         'error'=>"something went wrong"
        //     ]);
        // }
        // else{
        //     return response()->json([
        //         'status'=>200,
        //         'message'=>'Data successfully saved'
        //     ]);
        // }
        return redirect('/api/user');
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
        //
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
        if(!$data){
            return response()->json([
                'status'=>400,
                'error'=>"something went wrong"
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
                'message'=>'Data successfully updated'
            ]);
        }
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
        if(!$data){
            return response()->json([
                'status'=>400,
                'error'=>"something went wrong"
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
                'message'=>'Data successfully destroyed'
            ]);
        }
    }
}
