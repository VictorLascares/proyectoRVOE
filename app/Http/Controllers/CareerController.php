<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Career;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CareerController extends Controller
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
    if (Auth::user() != null) {
      $career = new Career();
      $career->nombre = $request->nombre;
      $career->modalidad = $request->modalidad;
      $career->duracion = $request->duracion;
      $career->area_id = $request->area_id;
      $career->institution_id = $request->institution_id;
      $career->save();
      return redirect(route('institutions.show', $request->institution_id));
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($career)
  {
    if (Auth::user() != null) {
      $areas= Area::all();
      $career = Career::find($career);
      $requisitions = DB::table('careers')
        ->join('requisitions', 'careers.id', '=', 'requisitions.career_id')
        ->select('requisitions.*')
        ->where('careers.id', $career->id)
        ->get();
      return view('carreras.show', compact('career','requisitions', 'areas'));
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
  public function update(Request $request, $career)
  {
    if (Auth::user() != null) {
      $data = Career::find($career);
      $data->update($request->all());
      return redirect('institutions');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($career)
  {
    if (Auth::user() != null) {
      $career = Career::find($career);
      $career->delete();
      return redirect('institutions');
    }
  }

  public function getCareers(Request $request) {
    if (Auth::user() != null) {
      if( $request->ajax()){
        $careers = Career::where('institution_id', $request->institutionId)->get();
        $careerArray = array();
        foreach ($careers as $career) {
          $careerArray[$career->id] = $career->nombre;
        }
        return response()->json($careerArray);
      }
    }
  }
}
