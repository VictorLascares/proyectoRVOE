<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Element;
use Illuminate\Support\Facades\Auth;

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
    public function evaluateFormats($requisition_id)
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
            return view('requisiciones.formEva' , compact('requisition', 'career', 'institution', 'formats', 'formatNames'));
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateFormats(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            $elements = Element::searchrequisitionid($request->requisition_id)->first();
            if ($requisition->noEvaluacion < 4 && $requisition->estado == 'pendiente') {
                for ($formatName = 1; $formatName < 6; $formatName++) {
                    $format = Format::searchformato($formatName)->searchrequisitionid($requisition->id)->first();
                    $formato = 'anexo' . $formatName;
                    $formatoj = $formato . 'j';
                    $format->valido = false;
                    $format->justificacion = '';
                    if ($request->input($formato) == false || $request->input($formato) == 'false') {
                        if ($requisition->noEvaluacion == 1) {
                            $format->justificacion = 'No se encuentra el formato';
                        } else {
                            $format->justificacion = $request->$formatoj;
                        }
                    } else {
                        if($request->$formatoj != null){
                            $format->justificacion = $request->$formatoj;
                        }else{
                            $format->justificacion = "";
                        }
                        $format->valido = true;
                    }
                    $format->save();
                }
                if ($requisition->noEvaluacion == '3') {
                    if (is_null($elements)) {
                        for ($elementName = 1; $elementName < 53; $elementName++) {
                            $element = new Element();
                            $element->elemento = $elementName;
                            $element->requisition_id = $requisition->id;
                            $element->save();
                        }
                    }
                }
                $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
                $requisition->save();
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }
}
