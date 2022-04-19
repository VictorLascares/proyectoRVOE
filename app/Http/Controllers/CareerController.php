<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use Illuminate\Support\Facades\DB;

class CareerController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $careers = Career::all();

    if (isset($careers)) {
      return response()->json([
        'careers' => $careers
      ]);
    } else {
      return response()->json([
        'error' => 'Data not found'
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
    $career = new Career();
    $career->nombre = $request->nombre;
    $career->titulo = $request->titulo;
    $career->modalidad = $request->modalidad;
    $career->duracion = $request->duracion;
    $career->area = $request->area;
    $career->institution_id = $request->institution_id;
    $career->save();
    return redirect(route('institutions.show', $request->institution_id));
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($career)
  {
    $career = Career::find($career);
    $requisitions = DB::table('careers')
      ->join('requisitions', 'careers.id', '=', 'requisitions.career_id')
      ->select('requisitions.*')
      ->where('careers.id', $career->id)
      ->get();
    return view('carreras.show', compact('career','requisitions'));
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
  public function update(Request $request, $career)
  {
    $data = Career::find($career);
    $data->update($request->all());
    return redirect('institutions');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($career)
  {
    $career = Career::find($career);
    $career->delete();
    return redirect('institutions');
  }
}
