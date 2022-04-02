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

        if(isset($users)){
            return response()->json([
                'users'=>$users
            ]);
        }
        else{
            return response()->json([
                'error'=>'Data not found'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $user->nombres=$request->nombres;
        $user->apellidos=$request->apellidos;
        $user->telefono=$request->telefono;
        $user->contrasenia=$request->contrasenia;
        $user->correo=$request->correo;

        $data = $user->save();
        if(!$data){
            return response()->json([
                'status'=>400,
                'error'=>"something went wrong"
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
                'message'=>'Data successfully saved'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if(isset($user)){
            return response()->json([
                'users'=>$user
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
    public function update(Request $request,$id)
    {
        $user = User::find($id);
        $user->nombres = $request->nombres;
        $user->apellidos=$request->apellidos;
        $user->telefono=$request->telefono;
        $user->correo=$request->correo;
        $data = $user->save();
        if(!$data){
            return response()->json([
                'status'=>400,
                'error'=>"something went wrong"
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
                'message'=>'Data successfully saved'
            ]);
        }
    }

    public function updatePSW(Request $request, $id){
        $user = User::find($id);
        $user->contrasenia = $request->contrasenia;
        $user->save();
        if(isset($user)){
            return response()->json([
                'status'=>200,
                'message'=>'Data successfully saved'
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
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        if(!$user){
            return response()->json([
                'status'=>400,
                'error'=>"something went wrong"
            ]);
        }
        else{
            return response()->json([
                'status'=>200,
                'message'=>'Data successfully saved'
            ]);
        }
    }
}
