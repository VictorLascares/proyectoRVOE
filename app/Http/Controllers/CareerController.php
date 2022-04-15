<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;

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
    $data = Career::find($career);
    if (isset($data)) {
      return response()->json([
        'career' => $data
      ]);
    } else {
      return response()->json([
        'error' => 'Data not found'
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
  public function update(Request $request, $career)
  {
    $data = Career::find($career);
    $data->update($request->all());
    if (!$data) {
      return response()->json([
        'status' => 400,
        'error' => "something went wrong"
      ]);
    } else {
      return response()->json([
        'status' => 200,
        'message' => 'Data successfully updated'
      ]);
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
    $data = Career::find($career);
    $data->delete();
    if (!$data) {
      return response()->json([
        'status' => 400,
        'error' => "something went wrong"
      ]);
    } else {
      return response()->json([
        'status' => 200,
        'message' => 'Data successfully destroyed'
      ]);
    }
  }
}
