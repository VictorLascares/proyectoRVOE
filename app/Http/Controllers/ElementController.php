<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Requisition;
use App\Models\Element;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Plan;

class ElementController extends Controller
{
    //Funcion para realizar la evaluación de los formatos
    public function evaluateElements($requisition_id){
        $requisition = Requisition::find($requisition_id);
        $career = Career::find($requisition->career_id);
        $institution = Institution::find($career->institution_id);
        $elements = Element::searchrequisitionid($requisition->id)->get();

        return view('requisiciones.instaEva', compact('requisition', 'career', 'institution', 'elements'));
    }

    //Funcion para realizar la actualización de los formatos
    public function updateElements(Request $request){
        $requisition = Requisition::find($request->requisition_id);
        if($requisition->noEvaluacion == 4 && $requisition->estado == 'pendiente'){
            $imagen = $request->formatoInstalaciones;
            if (!is_null($imagen)){
                for ($elementName = 1; $elementName < 53; $elementName++) {
                    $element = Element::searchElemento($elementName)->searchrequisitionid($requisition->id)->first();
                    $elemento = 'elemento'.$elementName;
                    $element->existente = $request->input($elemento);
                    $noRequired = [7,12,14,15,17,18,19,20,21,22,23,24,25,26,27,28,31,32,33,34,35,36,37,38,39,41,48,49,50];
                    if($request->input($elemento) == 'false' && !in_array($elementName,$noRequired)){
                        $requisition->estado = 'rechazado';
                    }
                    $elementoj = $elemento.'o';
                    if(!is_null($request->input($elementoj))){
                        $element->observacion = $request->input($elementoj);
                    }
                    $element->save();
                }
                $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
                //Guardar imagen
                $ruta_destino = public_path('img/formatos/instalaciones');
                $nombre_de_archivo = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move($ruta_destino, $nombre_de_archivo);
                $requisition->formatoInstalaciones = $nombre_de_archivo;
                // Se crean los planes para evaluación
                for ($planName = 1; $planName < 4; $planName++) {
                    $plan = new Plan();
                    $plan->plan = $planName;
                    $plan->requisition_id = $requisition->id;
                    $plan->save();
                } 
                $requisition->save();
            }
        }
        if (!$requisition) {
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
}
