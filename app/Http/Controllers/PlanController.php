<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Requisition;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Plan;

class PlanController extends Controller
{
  //Funcion para realizar la evaluación de los planes
  public function evaluatePlans($requisition_id)
  {
    $requisition = Requisition::find($requisition_id);
    $career = Career::find($requisition->career_id);
    $institution = Institution::find($career->institution_id);
    $plans = Plan::searchrequisitionid($requisition->id)->get();

    return view('requisiciones.plansEva', compact('requisition', 'career', 'institution', 'plans'));
  }

  //Funcion para realizar la actualización de los planes
  public function updatePlans(Request $request)
  {
    $requisition = Requisition::find($request->requisition_id);
    if ($requisition->noEvaluacion == 5 && $requisition->estado == 'pendiente') {
      $requisition->cata = true;
      for ($planName = 1; $planName < 4; $planName++) { //
        $plan = Plan::searchPlan($planName)->searchrequisitionid($requisition->id)->first();
        $planNumber = 'plan' . $planName;
        $planNumberc = $planNumber . 'c';
        $plan->ponderacion = $request->input($planNumber);
        $plan->comentario = $request->input($planNumberc);

        //Se evalua si la requisición es o no favorable
        if ($request->input($planNumber) <= 60) {
          $requisition->cata = false;
        }

        $plan->save();
      }
      $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
      $requisition->save();
    }
    return redirect(route('requisitions.show',$requisition->id));
  }
}
