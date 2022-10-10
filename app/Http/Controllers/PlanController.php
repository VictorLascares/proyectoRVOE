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
                "Planes y programas de estudios",
                "Reglamentos",
                "Plan de mejora continua"
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
                if($requisition->noEvaluacion == 5){
                    $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
                }
                $requisition->save();
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }
}
