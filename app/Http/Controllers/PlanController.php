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
                ["aspecto" => "Grado académico", "totalPts" => 1],
                ["aspecto" => "Modalidad educativa", "totalPts" => 1],
                ["aspecto" => "Área de formación", "totalPts" => 1],
                ["aspecto" => "Duración mínima en semanas", "totalPts" => 1],
                ["aspecto" => "Carga horaria semanal", "totalPts" => 5],
                ["aspecto" => "Tipo de diseño", "totalPts" => 1],
                ["aspecto" => "Número de ciclos escolares que integran el plan de estudios", "totalPts" => 1],
                ["aspecto" => "Indica los fines del aprendizaje", "totalPts" => 12],
                ["aspecto" => "Perfil de ingreso", "totalPts" => 10],
                ["aspecto" => "Antecedentes académico de ingreso", "totalPts" => 1],
                ["aspecto" => "Perfil de egreso", "totalPts" => 12],
                ["aspecto" => "Requisitos de ingreso", "totalPts" => 2],
                ["aspecto" => "Requisitos de permanencia", "totalPts" => 10],
                ["aspecto" => "Estrategias y lineamientos para asegurar el ingreso, permanencia, egreso y titulación", "totalPts" => 10],
                ["aspecto" => "Proyección de la matrícula escolar", "totalPts" => 5],
                ["aspecto" => "Modelo educativo", "totalPts" => 30],
                ["aspecto" => "Modalidad de evaluación del plan de estudios", "totalPts" => 20],
                ["aspecto" => "Periodicidad de evaluación del plan de estudios", "totalPts" => 5],
                ["aspecto" => "Justificación de la modalidad elegida", "totalPts" => 20],
                ["aspecto" => "Mapa curricular", "totalPts" => 40]
            );

            return view('requisiciones.plansEva', compact('requisition', 'career', 'institution', 'plans', 'planNames'));
        }
    }

    //Funcion para realizar la actualización de los planes
    public function updatePlans(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            if ($requisition->noEvaluacion >= 5 && $requisition->estado == 'pendiente') {
                for ($planName = 1; $planName < 4; $planName++) { //
                    $plan = Plan::searchPlan($planName)->searchrequisitionid($requisition->id)->first();
                    $planNumber = 'plan' . $planName;
                    $planNumberc = $planNumber . 'c';
                    $plan->ponderacion = $request->input($planNumber);
                    $plan->comentario = $request->input($planNumberc);
                    $plan->save();
                }
                if ($requisition->noEvaluacion == 5) {
                    $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
                }
                $requisition->save();
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }
}
