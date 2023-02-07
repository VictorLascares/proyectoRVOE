<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Comment;
use App\Models\Element;
use App\Models\Institution;
use App\Models\Opinion;
use App\Models\Plan;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $opinionNames = array(
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

            return view('requisiciones.factYPertEva', compact('requisition', 'career', 'institution', 'opinions', 'opinionNames'));
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateOpinions(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            $elements = Element::searchrequisitionid($request->requisition_id)->first();
            $plans = Plan::searchrequisitionid($request->requisition_id)->first();
            if ($requisition->evaNum >= 4 && $requisition->status == 'pendiente') {
                for ($opinionName = 1; $opinionName < 30; $opinionName++) {
                    $opinion = Opinion::searchOpinion($opinionName)->searchrequisitionid($requisition->id)->first();
                    $opinionRequest = 'opinion' . $opinionName;
                    if(!is_null($request->input($opinionRequest))){
                        $opinion->status = $request->input($opinionRequest);
                    }
                    $opinion->save();
                }
                $opinionComment = Comment::searchName("opinionComment")->searchRequisitionid($requisition->id)->first();
                if(!is_null($request->input("opinionC"))){
                    $opinonComment->observation = $request->input("opinionC");
                    $opinionComment->save();
                }
                if($requisition->evaNum == 4){
                    $requisition->evaNum = $requisition->evaNum + 1;
                }
                if (is_null($elements)) {
                    for ($elementName = 1; $elementName < 27; $elementName++) {
                        $element = new Element();
                        $element->element = $elementName;
                        $element->requisition_id = $requisition->id;
                        $element->save();
                    }
                    $elementComment = new Comment();
                    $elementComment->name = "elementComment";
                    $elementComment->requisition_id = $requisition->id;
                    $elementComment->save();
                }
                $requisition->save();
            // }
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }    
}
