<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Element;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class FormatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

    }
    //Funcion para realizar la evaluación de los formatos
    public function evaluateFormats($requisition_id,$no_evaluation)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($requisition_id);
            $career = Career::find($requisition->career_id);
            $institution = Institution::find($career->institution_id);
            $formats = Format::searchrequisitionid($requisition->id)->get();
            $formatNames = array(
                "Plan de Estudios",
                "Mapa Curricular",
                "Programa de Estudio",
                "Estructura e Instalaciones",
                "Plataforma Tecnológica"
            );
            $noEvaluation = $no_evaluation;
            return view('requisiciones.formEva' , compact('requisition', 'career', 'institution', 'formats', 'formatNames', 'noEvaluation'));
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateFormats(Request $request)
    {
        $errors = [];
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            $elements = Element::searchrequisitionid($request->requisition_id)->first();
            $formatsActualy = Format::searchrequisitionid($requisition->id)->get();
            $validationEstatus = true;
            if(!($request->noEvaluation < $requisition->noEvaluacion && $requisition->noEvaluacion == 3)){
                if ($request->noEvaluation < 4 && $requisition->estado == 'pendiente') {
                    for ($formatName = 1; $formatName < 6; $formatName++) {
                        $format = Format::searchformato($formatName)->searchrequisitionid($requisition->id)->first();
                        $formato = 'anexo' . $formatName;
                        $formatoj = $formato . 'j';
                        if ($request->input($formato) == false || $request->input($formato) == 'false') {
                            if ($request->noEvaluation == 1) {
                                $format->justificacion = 'No se encuentra el formato';
                            } else if($request->noEvaluation == 2){
                                if($format->valido != false){
                                    $format->justificacion = $request->$formatoj;
                                }
                                $validationEstatus = false;
                            }else {
                                $format->justificacion = $request->$formatoj;
                            }
                            $format->valido = false;
                            // array_push($errors, $format);
                        } else {
                            if($request->noEvaluation == 2){
                                if($format->valido == false){
                                    $validationEstatus = false;
                                }else{
                                    $format->justificacion = $request->$formatoj;
                                    $format->valido = true;
                                }
                            }else{
                                if($request->$formatoj != null){
                                    $format->justificacion = $request->$formatoj;
                                }else{
                                    if($request->noEvaluation == 1 && $format->valido == false && $requisition->noEvaluation == 1){
                                        $format->justificacion = "";
                                    }
                                }
                                $format->valido = true;
                            }
                        }
                        $format->save();
                    }
                    if($request->noEvaluation == $requisition->noEvaluacion){
                        if ($request->noEvaluation == '3') {
                            if (is_null($elements)) {
                                for ($elementName = 1; $elementName < 53; $elementName++) {
                                    $element = new Element();
                                    $element->elemento = $elementName;
                                    $element->requisition_id = $requisition->id;
                                    $element->save();
                                }
                            }
                        }
                        if($validationEstatus){
                            $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
                            if($requisition->noEvaluacion == 2){
                                //Asignar fecha de 3 meses
                                $requisition->fecha_direccion = date('Y-m-d');
                                //Informar a dirección
                            }
                        }
                    } 
                    $requisition->save();
                }
            }
            return redirect(route('requisitions.show', $requisition->id))->with( ['errors' => $errors]);
        }
    }
}
