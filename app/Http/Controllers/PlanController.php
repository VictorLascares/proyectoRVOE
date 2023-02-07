<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Requisition;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mail;

use App\Mail\NotifyMail;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Funcion para realizar la evaluación de los planes
    public function evaluatePlans($requisition_id)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($requisition_id);
            $career = Career::find($requisition->career_id);
            $institution = Institution::find($career->institution_id);
            $plans = Plan::searchrequisitionid($requisition->id)->get();
            $planNames = array(
                "Grado académico",
                "Modalidad educativa",
                "Área de formación",
                "Duración minima en semanas",
                "Carga hararia semanal",
                "Tipo de diseño",
                "Número de ciclos escolares que integran el plan de estudios",
                "Fines del aprendizaje",
                "Perfil de ingreso",
                "Antecedente académico",
                "Perfil de egreso",
                "Requisitos de ingreso",
                "Requisitos de permanencia (se evaluará conforme al reglamento escolar)",
                "Estrategias y lineamientos para asegurar el ingreso, permanencia, egreso y titulación",
                "Proyección de la matrícula escolar",
                "Modelo educativo (modelo teorico - pedagógico del plan curricular)",
                "Modalidad de evaluación del plan de estudios (justificación teorica)",
                "Periodicidad de evaluación del plan de estudios",
                "Justificación de la modalidad elegida (incluir viabilidad del programa con base en la modalidad propuesta)",
                "Mapa curricular"
            );

            return view('requisiciones.plansEva', compact('requisition', 'career', 'institution', 'plans', 'planNames'));
        }
    }

    //Funcion para realizar la actualización de los planes
    public function updatePlans(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            if ($requisition->evaNum >= 6 && $requisition->status == 'pendiente') {
                for ($planName = 1; $planName < 33; $planName++) { //
                    $plan = Plan::searchPlan($planName)->searchrequisitionid($requisition->id)->first();
                    $planNumber = 'plan' . $planName;
                    $planNumberc = $planNumber . 'c';
                    if(!is_null($request->input($planNumber)) ){
                        $plan->status = $request->input($planNumber);
                    }
                    if(!is_null($request->input($planNumberc))){
                        $plan->commentary = $request->input($planNumberc);
                    }
                    $plan->save();
                }
                if($requisition->evaNum == 6){
                    $requisition->evaNum = $requisition->evaNum + 1;
                }
                $requisition->save();
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }
}
