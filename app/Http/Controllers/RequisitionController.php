<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;

class RequisitionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $requisitions = Requisition::all();

    if (isset($requisitions)) {
      return response()->json([
        'requisitions' => $requisitions
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
    $requisition = new Requisition();
    $requisition->meta = $request->meta;
    $requisition->modalidad = $request->modalidad;
    $requisition->duracion = $request->duracion;
    $requisition->career_id = $request->career_id;
    $data = $requisition->save();
    if (!$data) {
      return response()->json([
        'status' => 400,
        'error' => "something went wrong"
      ]);
    } else {
      return response()->json([
        'status' => 200,
        'message' => 'Data successfully saved'
      ]);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($requisition)
  {
    $data = Requisition::find($requisition);
    if (isset($data)) {
      return response()->json([
        'requisition' => $data
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
  public function update(Request $request, $requisition)
  {
    $data = Requisition::find($requisition);
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
  public function destroy($requisition)
  {
    $data = Requisition::find($requisition);
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
