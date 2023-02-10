<?php

namespace App\Http\Controllers;

use App\Models\Plan;

use App\Models\Career;
use App\Models\Comment;
use App\Models\Element;
use App\Models\Institution;
use App\Models\Opinion;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ElementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Funcion para realizar la evaluación de los formatos
    public function evaluateElements($requisition_id)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($requisition_id);
            $career = Career::find($requisition->career_id);
            $institution = Institution::find($career->institution_id);
            $elements = Element::searchrequisitionid($requisition->id)->get();
            $elementComment = Comment::searchname('elementComment')->get()[0];
            $elementName = array(
                "Cuenta con escritura pública",
                "Cuenta con contrato de arrendamiento",
                "En el contrato de arrendamiento especifica que el uso del inmueble sera impartir educación",
                "Cuenta con contrato de comodato",
                "En el contrato de comodato especifica que el uso del inmueble sera impartir educación",
                "Cuenta con acreditación de seguridad estructural del inmueble",
                "Cuenta con acreditación de permiso de uso de suelo ó equivalente",
                "Cuenta con dictamen de la secretaria de protección civil estatal o municipal",
                "Cuenta con cédula de funcionamiento vigente",
                "La cédula de funcionamiento se encuentra debidamente exhibida",
                "El terreno se encuentra debidamente delimitado de exterior y colindancias",
                "Cuenta con áreas de recreación y/o esparcimiento",
                "Cuenta con áreas verdes",
                "Cuenta con servicio de cafetería",
                "Cuenta con estacionamiento propio",
                "El inmueble presenta condiciones de accesibilidad para personas discapacitadas",
                "Cuenta con sanitarios exclusivos y adaptados para personas discapacitadas",
                "Cuenta con botiquín de primeros auxilios",
                "Se cuenta con dispensadores de agua para el consumo humano",
                "Cuenta con extintores vigentes",
                "Cuenta con señalamientos",
                "Cuenta con rutas de evacuación",
                "Cuenta con alerta sísmica",
                "Cuenta con puntos de reunión",
                "Cuenta con cubículos",
                "Cuenta con sala de maestros"
            );

            return view('requisiciones.instaEva', compact('requisition', 'career', 'elementComment', 'institution', 'elements', 'elementName'));
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateElements(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            $plans = Plan::searchrequisitionid($request->requisition_id)->first();
            $opinions = Opinion::searchrequisitionid($request->requisition_id)->first();
            if ($requisition->evaNum >= 5 && $requisition->status == 'pendiente') {
                $imagen = $request->formatoInstalaciones;
                // if (!is_null($imagen)) {
                    for ($elementName = 1; $elementName < 27; $elementName++) {
                        $element = Element::searchElemento($elementName)->searchrequisitionid($requisition->id)->first();
                        $elemento = 'elemento' . $elementName;
                        if(is_null($request->input($elemento))){
                            $element->existing = false;
                        }else{
                            $element->existing = $request->input($elemento);
                        }
                        $element->save();
                    }
                    $elementComment = Comment::searchName("elementComment")->searchRequisitionid($requisition->id)->first();
                    if (!is_null($request->input("elementoC"))) {
                        $elementComment->observation = $request->input("elementoC");
                        $elementComment->save();
                    }
                    if($requisition->evaNum == 5){
                        $requisition->evaNum = $requisition->evaNum + 1;
                    }
                    if ($requisition->facilitiesFormat != null) {
                        Cloudinary()->destroy($requisition->format_public_id);
                    }                    
                    //Guardar imagen
                    if ($request->facilitiesFormat) {
                        $path = $request->file('facilitiesFormat')->getRealPath();
                        $response = Cloudinary()->upload($path);
                        $secureURL = $response->getSecurePath();
                        $public_id = $response->getPublicId();
                        
                        $requisition->facilitiesFormat = $secureURL;
                        $requisition->format_public_id = $public_id;
                    }
                    // Se crean los planes para evaluación
                    $opinionTop= array(1,1,1,1,5,1,1,12,10,1,12,2,10,10,5,30,20,5,20,40);
                    if (is_null($plans)) {
                        for ($planName = 1; $planName < 21; $planName++) {
                            $plan = new Plan();
                            $plan->plan = $planName;
                            $plan->top = $opinionTop[$planName - 1];
                            $plan->requisition_id = $requisition->id;
                            $plan->save();
                        }
                        $planComment = new Comment();
                        $planComment->name = "planComment";
                        $planComment->requisition_id = $requisition->id;
                        $planComment->save();
                    }
                    $requisition->save();
                // }
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }
}
