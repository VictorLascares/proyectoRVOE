<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Element;


class FormatController extends Controller
{
    //Funcion para realizar la evaluación de los formatos
    public function evaluateFormats($requisition_id){
        $requisition = Requisition::find($requisition_id);
        $career = Career::find($requisition->career_id);
        $institution = Institution::find($career->institution_id);
        $formats = Format::searchrequisitionid($requisition->id)->get();

        if (isset($requisition)) {
            return response()->json([
            'requisition' => $requisition,
            'career' => $career,
            'institution' => $institution,
            'formats' => $formats
        ]);
        } else {
            return response()->json([
                'error' => 'Data not found'
            ]);
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateFormats(Request $request){
        $requisition = Requisition::find($request->requisition_id);
        if($requisition->noEvaluacion < 4 && $requisition->estado == 'pendiente'){
            $formatsName = ['Plan de estudios', 'Mapa curricular', 'Programas de estudios', 'Estructura e instalaciones', 'Plataforma tecnológica'];
            foreach ($formatsName as $formatName) {
                $format = Format::searchformato($formatName)->searchrequisitionid($requisition->id)->first();
                switch($formatName){
                    case 'Plan de estudios':
                        $format->valido = $request->anexo1;
                        if($request->anexo1 == 'false'){
                            $format->justificacion = $request->anexo1j;
                            $requisition->estado = 'rechazado';
                        }
                        break;
                    case 'Mapa curricular':
                        $format->valido = $request->anexo2;
                        if($request->anexo2 == 'false'){
                            $format->justificacion = $request->anexo2j;
                            $requisition->estado = 'rechazado';
                        }                        
                        break;
                    case 'Programas de estudios':
                        $format->valido = $request->anexo3;
                        if($request->anexo3 == 'false'){
                            $format->justificacion = $request->anexo3j;
                            $requisition->estado = 'rechazado';
                        }                        
                        break;
                    case 'Estructura e instalaciones':
                        $format->valido = $request->anexo4;
                        if($request->anexo4 == 'false'){
                            $format->justificacion = $request->anexo4j;
                            $requisition->estado = 'rechazado';
                        }                        
                        break; 
                    case 'Plataforma tecnológica':
                        $format->valido = $request->anexo5;
                        if($request->anexo5 == 'false'){
                            $format->justificacion = $request->anexo5j;
                            $requisition->estado = 'rechazado';
                        }                        
                        break;       
                }                
                $format->save();
            }
            if($requisition->noEvaluacion == '3'){
                $elementsName = ['Piso', 'Laboratorio', 'Computadoras', 'Sanitizantes', 'Baños limpios'];
                foreach ($elementsName as $elementName) {
                    $element = new Element();
                    $element->elemento = $elementName;
                    $element->requisition_id = $requisition->id;
                    $element->save();
                }   
            }
            $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
            $requisition->save();

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
