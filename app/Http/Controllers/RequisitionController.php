<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Element;
use App\Models\Institution;
use App\Models\Career;


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
        $institutions = Institution::all();
        $careers = Career::all();

        return response()->json([
            'institutions'=>$institutions,
            'careers'=>$careers
        ]);
    }

    /**
     * Vista para crear la requisicion conociendo la carrera
     *
     * @return \Illuminate\Http\Response
     */
    public function crearPorCarrera($career_id)
    {
        return response()->json([
            'career'=>$career_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requisition = New Requisition();
        $requisition->meta = $request->meta;
        $requisition->career_id = $request->career_id;
        $data = $requisition->save();

        $elementsName = ['Anexo 1', 'Anexo 2', 'Anexo 3', 'Anexo 4', 'Anexo 5'];
        foreach($elementsName as $elementName){
            $element = New Element();
            $element->nombre = $elementName;
            $element->noEvaluacion = 1;
            $element->requisition_id = $requisition->id;
            $element->save();
        }

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
