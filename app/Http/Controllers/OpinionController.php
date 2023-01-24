<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use App\Models\Plan;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Funcion para realizar la evaluación de los formatos
    public function evaluateOpinions($requisition_id)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($requisition_id);
            $career = Career::find($requisition->career_id);
            $institution = Institution::find($career->institution_id);
            $opinions = Opinion::searchrequisitionid($requisition->id)->get();
            $opinionName = array(
                "Presenta datos económicos de la zona donde se impartirá el plan",
                "Analiza los datos económicos de la zona en donde se establecerá el plan",
                "Presenta datos económicos del estado",
                "Presenta datos económicos del sureste",
                "Define el sector de población a atender",
                "Define y analiza el sector productivo que se verá beneficiado",
                "siete",
                "ocho",
                "nueve",
                "diez",
                "once",
                "doce",
                "trece",
                "catorce",
                "quince",
                "diesciseis",
                "diescisiete",
                "diesciocho",
                "diescinueve",
                "veinte",
                "veintiuno",
                "veintidos",
                "veintitres",
                "veinticuatro",
                "veinticinco",
                "veintiseis",
                "veintisiete",
                "veintiocho",
                "veintinueve"
            );

            return view('requisiciones.instaEva', compact('requisition', 'career', 'institution', 'opinion', 'opinionName'));
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateOpinions(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            $plans = Plan::searchrequisitionid($request->requisition_id)->first();
            if ($requisition->evaNum >= 5 && $requisition->estado == 'pendiente') {
                    for ($opinionName = 1; $opinionName < 29; $opinionName++) {
                        $opinion = Opinion::searchOpinion($opinionName)->searchrequisitionid($requisition->id)->first();
                        $opinionRequest = 'opinion' . $opinionName;
                        if(!is_null($request->input($opinionRequest))){
                            $opinion->status = $request->input($opinionRequest);
                        }
                        $opinion->save();
                    }
                    if($requisition->evaNum == 5){
                        $requisition->evaNum = $requisition->evaNum + 1;
                    }
                    // Se crean los planes para evaluación
                    $planTop= array(1,1,1,1,5,1,1,12,10,1,12,2,20,5,30,20,5,20,40,40,40,40,40,40,40,40,40,40,40);
                    if (is_null($plans)) {
                        for ($planName = 1; $planName < 29; $planName++) {
                            $plan = new Plan();
                            $plan->plan = $planName;
                            $plan->top = $planTop[$planName - 1];
                            $plan->requisition_id = $requisition->id;
                            $plan->save();
                        }
                    }
                    $requisition->save();
                // }
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }
}
