<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use App\Models\Career;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\RedisQueue;

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
    return view('instituciones.index', compact('institutions'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('instituciones.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $institution = new Institution();
    $imagen = $request->logotipo;
    if (!is_null($imagen)) {
      $ruta_destino = public_path('img/institutions/');
      $nombre_de_archivo = time() . '.' . $imagen->getClientOriginalExtension();
      $imagen->move($ruta_destino, $nombre_de_archivo);
      $institution->logotipo = $nombre_de_archivo;
    }
    $institution->nombre = $request->nombre;
    $institution->director = $request->director;
    $institution->save();

    return redirect('institutions');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($institution)
  {
    $institution = Institution::find($institution);
    $careers = DB::table('institutions')
      ->join('careers', 'institutions.id', '=', 'careers.institution_id')
      ->select('careers.*')
      ->where('institutions.id', $institution->id)
      ->get();
    return view('instituciones.show', compact('institution', 'careers'));
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
    $logotipo = $request->file('logo');
    $nombre_logo = $data->logotipo;
    if (!is_null($logotipo)) {
      unlink(public_path('img/institutions/' . $nombre_logo));
      $ruta_destino = public_path('img/institutions/');
      $nombre_de_archivo = time() . '.' . $logotipo->getClientOriginalExtension();
      $logotipo->move($ruta_destino, $nombre_de_archivo);
      $data->logotipo = $nombre_de_archivo;
    }
    if (!is_null($request->nombre)) {
      $data->nombre = $request->nombre;
    }
    if (!is_null($request->director)) {
      $data->director = $request->director;
    }
    $data->save();
    return redirect('institutions');
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
    $path = $data->logotipo;
    unlink(public_path('img/institutions/' . $path));
    $data->delete();
    return redirect('institutions');
  }
}
