<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Institution;
use App\Models\Comment;
use App\Models\Career;
use App\Models\Opinion;
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
            $formatsActualy = Format::searchrequisitionid($requisition->id)->get();
            $opinions = Opinion::searchrequisitionid($request->requisition_id)->first();
            $validationEstatus = true;
            if(($request->noEvaluation < 3 && $requisition->evaNum < 3 ) ||  ($requisition->evaNum >= 3 && $request->noEvaluation == 3)){
                if ($requisition->status == 'pendiente') {
                    for ($formatName = 1; $formatName < 6; $formatName++) {
                        $format = Format::searchformat($formatName)->searchrequisitionid($requisition->id)->first();
                        $formato = 'anexo' . $formatName;
                        $formatoj = $formato . 'j';
                        if ($request->input($formato) == false || $request->input($formato) == 'false') {
                            if ($request->noEvaluation == 1) {
                                if($requisition->evaNum < 3 && $format->justification == ''){
                                    $format->justification = 'No se encuentra el formato';
                                    $format->valid = false;
                                }
                            } else if($request->noEvaluation == 2){
                                if($requisition->evaNum == 2){
                                    if($format->valid != false){
                                        $format->justification = $request->$formatoj;
                                    }
                                }
                                $validationEstatus = false;
                                $format->valid = false;
                            }else {
                                $format->justification = $request->$formatoj;
                                $format->valid = false;
                            }
                        } else {
                            if($request->noEvaluation == 1){
                                if($requisition->evaNum == 1){
                                    $format->justification = "";
                                    $format->valid = true;
                                }
                                if($requisition->evaNum == 2 && $format->valid == false && $format->justification == 'No se encuentra el formato'){
                                    $format->justification = "";
                                    $format->valid = true;
                                }
                            }
                            if($request->noEvaluation == 2){
                                if($format->valid == false ){
                                    if( $format->justification != 'No se encuentra el formato'){
                                        if($request->$formatoj != null){
                                            $format->justification = $request->$formatoj;
                                        }else{
                                            $format->justification = "";
                                        }   
                                        $format->valid = true;     
                                    }else{
                                        $validationEstatus = false;
                                    }                        
                                }
                                if($format->valid == true){
                                    if($request->$formatoj != null){
                                        $format->justification = $request->$formatoj;
                                    }
                                }
                            }
                            if($request->noEvaluation == 3){
                                if($format->valid == false){
                                    if($request->$formatoj != null){
                                        $format->justification = $request->$formatoj;
                                    }else{
                                        $format->justification = "";
                                    }   
                                    $format->valid = true; 
                                }
                                if($format->valid == true){
                                    if($request->$formatoj != null){
                                        $format->justification = $request->$formatoj;
                                    }
                                }
                            }
                        }
                        $format->save();
                    }
                    if($request->noEvaluation == $requisition->evaNum){
                        //Acá se crean los elementos de evaluación de las intalaciones
                        if ($request->noEvaluation == '3') {                            
                            if(is_null($opinions)){
                                //Se crean las opiniones
                                $opinionTop= array(1,1,1,1,1,.83,.83,.83,.83,.83,.83,1.67,1.67,1.67,3,3,3,3,3,3.75,3.75,3.75,3.75,12.5,12.5,10,6.67,6.67,6.67);
                                for ($opinionName = 1; $opinionName < 30; $opinionName++) {
                                    $opinion = new Opinion();
                                    $opinion->opinion = $opinionName;
                                    $opinion->top = $opinionTop[$opinionName - 1];
                                    $opinion->requisition_id = $requisition->id;
                                    $opinion->save();
                                }
                                $opinionComment = new Comment();
                                $opinionComment->name = "opinionComment";
                                $opinionComment->requisition_id = $requisition->id;
                                $opinionComment->save();
                            }
                        }
                        if($validationEstatus){
                            $requisition->evaNum = $requisition->evaNum + 1;
                            if($requisition->evaNum == 3){
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
