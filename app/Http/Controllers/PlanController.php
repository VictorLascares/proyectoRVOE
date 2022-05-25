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
      if ($requisition->noEvaluacion == 5 && $requisition->estado == 'pendiente') {
        $requisition->cata = true;
        for ($planName = 1; $planName < 4; $planName++) { //
          $plan = Plan::searchPlan($planName)->searchrequisitionid($requisition->id)->first();
          $planNumber = 'plan' . $planName;
          $planNumberc = $planNumber . 'c';
          $plan->ponderacion = $request->input($planNumber);
          $plan->comentario = $request->input($planNumberc);

          //Se evalua si la requisición es o no favorable
          if ($request->input($planNumber) < 60) {
            $requisition->cata = false;
            $requisition->estado = 'rechazado';
          }

          $plan->save();
        }
        if ($requisition->cata == true) {
          $date_vencimiento = date("Y-m-d", strtotime($requisition->created_at . "+ 3 year"));
          $requisition->fecha_vencimiento = $date_vencimiento;
          $requisition->estado = 'activo';
        }
        $year = date('Y');
        if(is_null($requisition->numero_solicitud)){
          $no_requisitions = Requisition::searchDate($year)->noSolicitud()->count();
          if ($no_requisitions == 0) {
            $requisition->numero_solicitud = 1;
          } else {
            $requisition->numero_solicitud = $no_requisitions + 1;
          }
        }      
        $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
        $requisition->save();
        //Notificando Planeación
        $career = Career::find($requisition->career_id);
        $institution = Institution::find($career->institution_id);
        $users = User::searchPlaneacion()->get();
        $meta = $request->meta;
        if($request->meta == "planEstudios"){
          $meta = "plan de estudios";
        }
        if($request->meta == "domicilio"){
          $meta = "cambio de domicilio";
        }
        foreach($users as $user){
          Mail::to($user->correo)->send(new NotifyMail($meta,$career,$institution));
        }
      }
      return redirect(route('requisitions.show',$requisition->id));
    }
  }
}
