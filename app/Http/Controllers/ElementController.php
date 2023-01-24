<?php

namespace App\Http\Controllers;

use App\Models\Opinion;

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
                "Documento de posesión legal del inmueble*",
                "El inmueble en el que se impartirá el RVOE*",
                "Dimensiones del predio (m2)*",
                "Dimensiones de construcción (m2)*",
                "Dimensiones útiles para la impartición del Plan y Programas de estudio*",
                "¿En el inmueble se realizan actividades que están directa o indirectamente relacionadas con otros servicios educativos?*",
                "Detallar las actividades que se realizan en el inmueble",
                "Tipo de estudios que se imparten en el inmueble actualmente*",
                "Descripción del área fisíca destinada para el resguardo de la documentación de control escolar*",
                "Número total de aulas en el inmueble*",
                "Estado de las Aulas destinadas que serán destinadas a la impartición del Plan y Programa de estudio objeto de RVOE*",
                "Número de aulas que serán destinadas a la impartición del Plan y Programas de estudio objeto de RVOE",
                "Capacidad promedio de cada aula (cupo de alumnos)*",
                "Tipo de ilumminación de las aulas",
                "Tipo de ventilación de las aulas",
                "Número total de cubiculos en el inmueble*",
                "Número de cubiculos que serán destinados a la impartición del Plan y Programas de estudio objeto de RVOE",
                "Estado de los cubilos destinados a la impartición del Plan y Programas de estudio objeto de RVOE",
                "Tipo de iluminación de los cubiculos",
                "Tipo de ventilación de los cubiculos",
                "¿Cuenta con instalaciones especiales?",
                "Denominación del tipo de instalación",
                "Cantidad",
                "Equipo con que cuenta",
                "Tipo de iluminación",
                "¿Cuenta con ventilación?",
                "Tipo de Ventilación",
                "Asignatura o unidad de aprendizaje que se imparte en la instalación especial",
                "¿Cuenta con equipo tecnológico?*",
                "Condiciones en las que se encuentra el equipo*",
                "Tipo del equipo tecnológico al servicio del alumno",
                "Cantidad de equipo tecnológico por alumno",
                "Ubicación del equipo tecnológico del alumno dentro del inmueble",
                "Tipo de equipo tecnológico al servicio del administrativo",
                "Cantidad de equipos por administrativo",
                "Ubicación del equipo tecnológico del administrativo dentro de l inmueble",
                "Tipo de equipo tecnológico al servicio del docente",
                "Cantidad de equipos por docente",
                "Ubicación del equipo tecnológico del docente dentro del inmueble",
                "Cuenta con servicio de telefonia*",
                "Total de equipos telefónicos",
                "¿Cuenta con el servicio de internet?*",
                "Velocidad en MB*",
                "Razonamiento técnico que justifica la idoneidad de las instalaciones para el servicio educativo que se brindará*",
                "Población estudiantil máxima que podrá ser atendida en el inmueble*",
                "¿Existe un plan interno de protección civil?*",
                "Nivel de accesibilidad*",
                "Forma en la que se abastece de agua",
                "Tipo de drenaje sanitario existente en el inmueble",
                "Número total de sanitarios en el inmueble",
                "¿El diseño de la infraestructura educativa incorporó un modelo de sostenibilidad?*",
                "¿El diseño de la infraestructura educativa incorporó el uso de energía sostenible?*"
            );

            return view('requisiciones.instaEva', compact('requisition', 'career', 'institution', 'elements', 'elementName'));
        }
    }

    //Funcion para realizar la actualización de los formatos
    public function updateElements(Request $request)
    {
        if (Auth::user() != null) {
            $requisition = Requisition::find($request->requisition_id);
            $opinions = Opinion::searchrequisitionid($request->requisition_id)->first();
            if ($requisition->evaNum >= 4 && $requisition->estado == 'pendiente') {
                $imagen = $request->formatoInstalaciones;
                // if (!is_null($imagen)) {
                    for ($elementName = 1; $elementName < 53; $elementName++) {
                        $element = Element::searchElemento($elementName)->searchrequisitionid($requisition->id)->first();
                        $elemento = 'elemento' . $elementName;
                        if(is_null($request->input($elemento))){
                            $element->existente = false;
                        }else{
                            $element->existente = $request->input($elemento);
                        }
                        $elementoj = $elemento . 'o';
                        if (!is_null($request->input($elementoj))) {
                            $element->observacion = $request->input($elementoj);
                        }else{
                            return response()->json([
                                'error' => 'Algunas observaciones no se encuentran especificadas.'
                            ]);
                        }
                        $element->save();
                    }
                    if($requisition->evaNum == 4){
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
                    //Se crean las opiniones
                    $opinionTop= array(1,1,1,1,1,.83,.83,.83,.83,.83,.83,1.67,1.67,1.67,3,3,3,3,3,3.75,3.75,3.75,3.75,12.5,12.5,10,6.67,6.67,6.67);
                    if (is_null($opinions)) {
                        for ($opinionName = 1; $opinionName < 29; $opinionName++) {
                            $opinion = new Plan();
                            $opinion->plan = $opinionName;
                            $opinion->top = $opinionTop[$opinionName - 1];
                            $opinion->requisition_id = $requisition->id;
                            $opinion->save();
                        }
                    }
                    $requisition->save();
                // }
            }
            return redirect(route('requisitions.show', $requisition->id));
        }
    }
}
