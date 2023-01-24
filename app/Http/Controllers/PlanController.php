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
                "Número de ciclos",
                "Fines del aprendizaje",
                "Perfil del aprendizaje",
                "Perfil de ingreso",
                "Antecedente académico",
                "Requiere curso propedeutico y requerir cursos propedeutico (opcional)",
                "Perfil de egreso",
                "Requisitos de ingreso",
                "Requisitos de permanencia (se evaluará conforme al reglamento escolar)",
                "Estrategias y leineamientos para asegurar el ingreso, permanencia, egreso y titulación",
                "Proyección de la matrícula escolar",
                "Modelo educativo (modelo teorico - pedagógico del plan curricular)",
                "Modalidad de evaluación del plan de estudios (justificación teorica)",
                "Periodicidad de evaluación del plan de estudios",
                "Justificación de la modalidad elegida (incluir viabilidad del programa con base en la modalidad propuesta)",
                "Esquema",
                "Ciclo escolar",
                "Tipo de asignatturas o unidades de aprendizaje",
                "Denominación de asignaturas o unidades de aprendizaje",
                "Seriación",
                "Carga horaria",
                "Horas bajo conducción docente",
                "Horas independientes"
            );

            return view('requisiciones.plansEva', compact('requisition', 'career', 'institution', 'plans', 'planNames'));
        }
    }

    //Funcion para realizar la actualización de los planes
    public function updatePlans(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            if ($requisition->evaNum >= 6 && $requisition->estado == 'pendiente') {
                $summary = 0;
                for ($planName = 19; $planName < 29; $planName++) { //
                    $planNumber = 'plan' . $planName;
                    if(!is_null($request->input($planNumber)) ){
                        $summary += abs($request->input($planNumber));
                    }
                }
                if($summary > 40){
                    return response()->json([
                        'status' => 400,
                        'error' => "La suma de los aspectos del programa de investigación suman más de 40."
                    ]);
                }
                for ($planName = 1; $planName < 29; $planName++) { //
                    $plan = Plan::searchPlan($planName)->searchrequisitionid($requisition->id)->first();
                    $planNumber = 'plan' . $planName;
                    $planNumberc = $planNumber . 'c';
                    if(!is_null($request->input($planNumber)) ){
                        $plan->score = $request->input($planNumber);
                        if(abs($request->input($planNumber)) > $plan->top){
                            return response()->json([
                                'status' => 400,
                                'error' => "El valor del puntaje supera el grado maximo del plan."
                            ]);
                        }
                    }
                    if(!is_null($request->input($planNumber))){
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
