<?php

namespace App\Http\Controllers;

use App\Models\Plan;

use App\Models\Career;
use App\Models\Element;
use App\Models\Institution;
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
                "Cuenta con areas de recreación y/o esparcimiento",
                "Cuenta con areas verdes",
                "Cuenta con servicio de cafeteria",
                "Cuenta con estacionamiento propio",
                "El inmueble presenta condiciones de accesibilidad para personas discapacitadas",
                "Cuenta con sanitarios exclusivos y adaptados para personas discapacitadas",
                "Cuenta con botiquin de primeros auxilios",
                "Se cuenta con dispensadores de agua para el consumo humano",
                "Cuenta con extintores vigentes",
                "Cuenta con señalamientos",
                "Cuenta con rutas de evacuación",
                "Cuenta con alerta sismica",
                "Cuenta con puntos de reunión",
                "Cuenta con cubiculos",
                "Cuenta con sala de maestros"
            );

            return view('requisiciones.instaEva', compact('requisition', 'career', 'institution', 'elements', 'elementName'));
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateElements(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            $plans = Plan::searchrequisitionid($request->requisition_id)->first();
            $opinions = Opinion::searchrequisitionid($request->requisition_id)->first();
            if ($requisition->evaNum >= 5 && $requisition->estado == 'pendiente') {
                $imagen = $request->formatoInstalaciones;
                // if (!is_null($imagen)) {
                    for ($elementName = 1; $elementName < 27; $elementName++) {
                        $element = Element::searchElemento($elementName)->searchrequisitionid($requisition->id)->first();
                        $elemento = 'elemento' . $elementName;
                        if(is_null($request->input($elemento))){
                            $element->existente = false;
                        }else{
                            $element->existente = $request->input($elemento);
                        }
                        if (!is_null($request->input($elementoj))) {
                            $element->observacion = $request->input($elementoj);
                        }else{
                            return response()->json([
                                'error' => 'Algunas observaciones no se encuentran especificadas.'
                            ]);
                        }
                        $element->save();
                    }
                    $elementComment = Comment::searchName("elementComment")->searchRequisitionid($requisition->id)->first();
                    if (!is_null($request->input("elementoC"))) {
                        $elementComment = $request->input($request->input("elementoC"));
                        $elementComment->save();
                    }
                    if($requisition->evaNum == 5){
                        $requisition->evaNum = $requisition->evaNum + 1;
                    }
                    if ($requisition->formatoInstalaciones != null) {
                        Cloudinary()->destroy($requisition->formato_public_id);
                    }
                    
                    //Guardar imagen

                    if ($request->formatoInstalaciones) {
                        $path = $request->file('formatoInstalaciones')->getRealPath();
                        $response = Cloudinary()->upload($path);
                        $secureURL = $response->getSecurePath();
                        $public_id = $response->getPublicId();
                        
                        $requisition->formatoInstalaciones = $secureURL;
                        $requisition->formato_public_id = $public_id;
                    }
                    // Se crean los planes para evaluación
                    if (is_null($plans)) {
                        for ($planName = 1; $planName < 33; $planName++) {
                            $plan = new Plan();
                            $plan->plan = $planName;
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
