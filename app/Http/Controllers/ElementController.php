<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Requisition;
use App\Models\Element;
use App\Models\Institution;
use App\Models\Career;

class ElementController extends Controller
{
    //Funcion para realizar la evaluación de los formatos
    public function evaluateElements($requisition_id){
        $requisition = Requisition::find($requisition_id);
        $career = Career::find($requisition->career_id);
        $institution = Institution::find($career->institution_id);
        $elements = Element::searchrequisitionid($requisition->id)->get();

        if (isset($requisition)) {
            return response()->json([
            'requisition' => $requisition,
            'career' => $career,
            'institution' => $institution,
            'elements' => $elements
        ]);
        } else {
            return response()->json([
                'error' => 'Data not found'
            ]);
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateElements(Request $request){
        $requisition = Requisition::find($request->requisition_id);
        if($requisition->noEvaluacion == 4 && $requisition->estado == 'pendiente'){
            $imagen = $request->formatoInstalaciones;
            if (!is_null($imagen)){
                $elementsName = ['Piso', 'Laboratorio', 'Computadoras', 'Sanitizantes', 'Baños limpios'];
                foreach ($elementsName as $elementName) {
                    $element = Element::searchElemento($elementName)->searchrequisitionid($requisition->id)->first();
                    switch($elementName){
                        case 'Piso':
                            $element->existente = $request->elemento1;
                            if($request->elemento1 == 'false'){
                                $requisition->estado = 'rechazado';
                            }
                            if(!is_null($request->elemento1j)){
                                $element->observacion = $request->elemento1j;
                            }
                            break;
                        case 'Laboratorio':
                            $element->existente = $request->elemento2;
                            if($request->elemento2 == 'false'){
                                $requisition->estado = 'rechazado';
                            }  
                            if(!is_null($request->elemento2j)){
                                $element->observacion = $request->elemento2j;
                            }                      
                            break;
                        case 'Computadoras':
                            $element->existente = $request->elemento3;
                            if($request->elemento3 == 'false'){
                                $requisition->estado = 'rechazado';
                            }   
                            if(!is_null($request->elemento3j)){
                                $element->observacion = $request->elemento2j;
                            }                     
                            break;
                        case 'Sanitizantes':
                            $element->existente = $request->elemento4;
                            if($request->elemento4 == 'false'){
                                $requisition->estado = 'rechazado';
                            }                        
                            if(!is_null($request->elemento4j)){
                                $element->observacion = $request->elemento4j;
                            }
                            break; 
                        case 'Baños limpios':
                            $element->existente = $request->elemento5;
                            if($request->elemento5 == 'false'){
                                $requisition->estado = 'rechazado';
                            }                      
                            if(!is_null($request->elemento5j)){
                                $element->observacion = $request->elemento5j;
                            }  
                            break;       
                    }                
                    $element->save();
                }
                $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
                //Guardar imagen
                $ruta_destino = public_path('img/formatos/instalaciones');
                $nombre_de_archivo = time() . '.' . $imagen->getClientOriginalExtension();
                $imagen->move($ruta_destino, $nombre_de_archivo);
                $requisition->formatoInstalaciones = $nombre_de_archivo;
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
