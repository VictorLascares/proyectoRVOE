<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;
class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutions = Institution::all();
        if(isset($institutions)){
            return response()->json([
                'institutions'=>$institutions
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
        $institution = New Institution();
        $institution->nombre = $request->nombre;
        $institution->director = $request->director;
        $logo = $request->file('logo');
        if(!is_null($logo)){
            $ruta_destino = public_path('img/logos/');
            $nombre_de_archivo = time().'.'.$logo->getClientOriginalExtension();
            $logo->move($ruta_destino,$nombre_de_archivo);
            $institution->logo= $nombre_de_archivo;
        }
        $data = $institution->save();
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
    public function show($institution)
    {
        $data = Institution::find($institution);
        $careers = DB::table('institutions')
        ->join('careers','institutions.id','=','careers.institution_id')
        ->select('careers.*')
        ->where('institutions.id',$institution)
        ->get();


        if(isset($data)){
            return response()->json([
                'institution'=>$data,
                'career'=>$careers
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
    public function update(Request $request, $institution)
    {
        $data = Institution::find($institution);
        $logo = $request->file('logo');
        $nombre_logo = $data->logo;
        if(!is_null($logo)){
            unlink(public_path('img/logos/'.$nombre_logo));
            $ruta_destino = public_path('img/logos/');
            $nombre_de_archivo = time().'.'.$logo->getClientOriginalExtension();
            $logo->move($ruta_destino,$nombre_de_archivo);
            $data->logo= $nombre_de_archivo;
        }
        if(!is_null($request->nombre)){
            $data->nombre = $request->nombre;
        }
        if(!is_null($request->director)){
            $data->nombre = $request->director;
        }
        $data->save();
            
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($institution)
    {
        $data = Institution::find($institution);
        $path = $data->logo;
        unlink(public_path('img/logos/'.$path));
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
