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
            if(!($request->noEvaluation < $requisition->evaNum && $requisition->evaNum == 3)){
                if ($request->noEvaluation < 4 && $requisition->status == 'pendiente') {
                    for ($formatName = 1; $formatName < 6; $formatName++) {
                        $format = Format::searchformat($formatName)->searchrequisitionid($requisition->id)->first();
                        $formato = 'anexo' . $formatName;
                        $formatoj = $formato . 'j';
                        if ($request->input($formato) == false || $request->input($formato) == 'false') {
                            if ($request->noEvaluation == 1) {
                                $format->justification = 'No se encuentra el formato';
                                $validationEstatus = false;
                            } else if($request->noEvaluation == 2){
                                if($format->valid != false){
                                    $format->justification = $request->$formatoj;
                                }
                                // $validationEstatus = false;
                            }else {
                                $format->justification = $request->$formatoj;
                            }
                            $format->valid = false;
                        } else {
                            if($request->noEvaluation == 2){
                                if($format->valid == false){
                                    // $validationEstatus = false;
                                    $format->justification = "";
                                    $format->valid = true;
                                }
                            }else{
                                if($request->$formatoj != null){
                                    $format->justification = $request->$formatoj;
                                }else{
                                    if($request->noEvaluation == 1 && $format->valid == false){
                                        $format->justification = "";
                                    }
                                }
                                $format->valid = true;
                            }
                        }
                        $format->save();
                    }
                    if($request->noEvaluation == $requisition->evaNum){
                        //Acá se crean los elementos de evaluación de las intalaciones
                        if ($request->noEvaluation == '3') {
                            if (is_null($elements)) {
                                for ($elementName = 1; $elementName < 53; $elementName++) {
                                    $element = new Element();
                                    $element->element = $elementName;
                                    $element->requisition_id = $requisition->id;
                                    $element->save();
                                }
                            }
                        }
                        if($validationEstatus){
                            $requisition->evaNum = $requisition->evaNum + 1;
                            if($requisition->evaNum == 2){
                                //Asignar fecha de 3 meses
                                $requisition->fecha_managment = date('Y-m-d');
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
