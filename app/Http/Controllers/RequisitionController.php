<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Element;
use App\Models\Opinion;
use App\Models\Plan;
use App\Models\Institution;
use App\Models\User;
use App\Models\Career;
use App\Models\Municipality;
use Illuminate\Support\Facades\Auth;

use Mail;

use App\Mail\NotifyMail;


class RequisitionController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth')->except('searchRequisition');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    if (Auth::user() != null) {
      $requisitions = Requisition::all();
      $careers = Career::all();
      $institutions = Institution::all();
      foreach ($requisitions as $requisition) {
        $this->revisar_activo($requisition->id);
      }
      $requisitions = Requisition::all();
      return view('requisiciones.index', compact('requisitions', 'careers', 'institutions'));
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    if (Auth::user() != null) {
      $institutions = Institution::all();
      $careers = Career::all();
      return response()->json([
        'institutions' => $institutions,
        'careers' => $careers
      ]);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if (Auth::user() != null) {
      $requisition = new Requisition();
      $career = Career::find($request->career_id);
      $institution = Institution::find($career->institution_id);
      $requisition_max = Requisition::searchcareeridMax($request->career_id)->first(); //Mayor
      if (!is_null($requisition_max)) {
        $dateNow = date('Y-m-d');
        $dateBack = $requisition_max->fecha_vencimiento;
        $dateNow_time = strtotime($dateNow);
        $dateBack_time = strtotime($dateBack);
        if ($requisition_max->estado == 'latencia' || ($dateBack_time < $dateNow_time && $requisition_max->fecha_vencimiento != null)) {
          $requisition->meta = $request->meta;
          $requisition->career_id = $request->career_id;
          $data = $requisition->save();
          //Se crean los formatos del 1 al 6
          for ($formatName = 1; $formatName < 6; $formatName++) {
            $format = new Format();
            $format->formato = $formatName;
            $format->requisition_id = $requisition->id;
            $format->save();
          }

          $users = User::searchDireccion()->get();
          $meta = $request->meta;
          if ($request->meta == "planEstudios") {
            $meta = "plan de estudios";
          }
          if ($request->meta == "domicilio") {
            $meta = "cambio de domicilio";
          }
          foreach ($users as $user) {
            Mail::to($user->email)->send(new NotifyMail($meta, $career, $institution));
          }
          return redirect('requisitions');
        } else {
          return response()->json([
            'status' => 400,
            'error' => "No se puede generar una requisición en estos momentos, consulte el historial de requisiciones."
          ]);
        }
      } else {
        $requisition->meta = $request->meta;
        $requisition->career_id = $request->career_id;
        $data = $requisition->save();
        for ($formatName = 1; $formatName < 6; $formatName++) {
          $format = new Format();
          $format->formato = $formatName;
          $format->requisition_id = $requisition->id;
          $format->save();
        }

        $users = User::searchDireccion()->get();
        $meta = $request->meta;
        if ($request->meta == "planEstudios") {
          $meta = "plan de estudios";
        }
        if ($request->meta == "domicilio") {
          $meta = "cambio de domicilio";
        }
        foreach ($users as $user) {
          Mail::to($user->correo)->send(new NotifyMail($meta, $career, $institution));
        }
        return redirect('requisitions');
      }
    }
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($requisition)
  {
    if (Auth::user() != null) {
      $this->revisar_activo($requisition);
      if (Auth::user() != null) {
        $data = Requisition::find($requisition);
        $formats = Format::where('requisition_id', $data->id)->get();
        $career = Career::find($data->career_id);
        $area = Area::find($career->area_id);
        $institution = Institution::find($career->institution_id);
        $elements = Element::searchrequisitionid($data->id)->get();
        $opinions = Opinon::searchrequisitionid($data->id)->get();
        $plans = Plan::searchrequisitionid($data->id)->get();
        $formatNames = array(
          "Plan de Estudios",
          "Mapa Curricular",
          "Programa de Estudio",
          "Estructura e Instalaciones",
          "Plataforma Tecnológica"
        );
        $errors = [];
        if ($data->evaNum <= 4) {
          foreach ($formats as $format) {
            if (!$format->valid) {
              array_push($errors, $format);
            }
          }
        } elseif ($data->evaNum <= 5) {
          foreach ($elements as $element) {
            if (!$element->existing) {
              array_push($errors, $element);
            }
          }
        } elseif ($data->evaNum <= 6) {
          foreach ($opinions as $opinion) {
            if (!$opinion->existente) {
              array_push($errors, $element);
            }
          }
        } else {
          foreach ($plans as $plan) {
            if ($plan->ponderacion < 60) {
              array_push($errors, $plan);
            }
          }
        }
        return view('requisiciones.show', compact('data', 'formatNames', 'career', 'institution', 'elements', 'plans', 'formats', 'area', 'errors'));
      }
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $requisition)
  {
    if (Auth::user() != null) {
      $this->revisar_activo($requisition);
      $data = Requisition::find($requisition);
      if ($request->estado == 'latencia' && $data->estado == 'activo') {
        $date = date('Y-m-d');
        $date = date('Y-m-d', strtotime($date));
        $dateMax = date("Y-m-d", strtotime($data->created_at . "+ 2 year"));
        if ($dateMax >= $date) {
          $data->fecha_latencia = $date;
          $data->estado = $request->estado;
        }
      } else if ($request->estado == 'activo' && $data->estado == 'latencia') {
        $dateLatencia = date_create(date('Y-m-d', strtotime($data->fecha_latencia)));
        $dateVencimiento = date_create(date('Y-m-d', strtotime($data->fecha_vencimiento)));
        $diasRestantes = date_diff($dateVencimiento, $dateLatencia);
        $differenceFormat = '%a';
        $date = date('Y-m-d');
        $date = date("Y-m-d", strtotime($date . "+ " . $diasRestantes->format($differenceFormat) . " day"));
        $data->fecha_vencimiento = $date;
        $data->estado = $request->estado;
      } else if ($request->estado == 'activo' && $data->estado == 'revocado') {
        $data->estado = $request->estado;
      }
      $data->update($request->all());
      return redirect(route('requisitions.show', $data->id));
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($requisition)
  {
    if (Auth::user() != null) {
      $data = Requisition::find($requisition);
      $data->delete();
      if (!$data) {
        return response()->json([
          'status' => 400,
          'error' => "something went wrong"
        ]);
      } else {
        return response()->json([
          'status' => 200,
          'message' => 'Data successfully destroyed'
        ]);
      }
    }
  }

  //Funcion para vista de busqueda de RVOE ---PENDIENTE
  public function searchRequisition()
  {
    $institutions = Institution::all();
    $municipalities = Municipality::all();
    $areas = Area::all();

    return view('pages.consult', compact('institutions', 'municipalities', 'areas'));
  }

  //Funcion para buscar RVOE ---PENDIENTE
  public function showRVOE(Request $request)
  {
    if (Auth::user() != null) {
      if (!is_null($request->rvoe)) {
        $requisition = Requisition::rvoe($request->rvoe)->first();
        if (!$requisition) {
          return response()->json([
            'status' => "No se encontró la requisición"
          ]);
        } else {
          return response()->json([
            'requisition' => $requisition

          ]);
        }
      }
    }
  }

  public function downloadOta($requisition_id)
  {
    if (Auth::user() != null) {
      $requisition = Requisition::find($requisition_id);
      if ($requisition->evaNum == 6 and $requisition->ota == true) {
        try {
          //code...
          $career = Career::find($requisition->career_id);
          $institution = Institution::find($career->institution_id);
          $municipalitie = Municipality::find($institution->municipalitie_id);
          $mes = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
          $anioI = date("Y", strtotime($requisition->created_at));
          $mesI = $mes[ltrim(date("m", strtotime($requisition->created_at)), "0") - 1];
          $diaI = date("d", strtotime($requisition->created_at));
          //$mesA = $mes[ltrim(date("m", strtotime($requisition->created_at . "+ 3 month")), "0") - 1];
          //$diaA = date("d", strtotime($requisition->created_at . "+ 3 month"));
          //$mesA = $mes[ltrim(date("m", strtotime($requisition->fecha_vencimiento)), "0") - 1];
          //$diaA = date("d", strtotime($requisition->fecha_vencimiento));
          $institucion = $institution->nombre;
          $municipio = $municipalitie->nombre;
          $direccion = $institution->direccion;
          if (is_null($requisition->rvoe)) {
            $no_solicitud = 'No disponible';
          } else {
            $no_solicitud = $requisition->rvoe;
          }
          if ($requisition->cata == true) {
            $resultado = 'cumple con los requisitos mínimos de esta disposición';
            $resultadoF = 'Favorable';
          } else {
            $resultado = 'no cumple con los requisitos mínimos de esta disposición';
            $resultadoF = 'No Favorable';
          }

          switch ($requisition->meta) {
            case 'solicitud':
              $meta = 'el tramite de solicitud';
              break;
            case 'domicilio':
              $meta = 'el cambio de domicilio';
              break;
            case 'planEstudios':
              $meta = 'el cambio de plan de estudios';
              break;
          }

          $template = new \PhpOffice\PhpWord\TemplateProcessor('DOCUMENTO_OTA.docx');

          $template->setValue('resultado', $resultado);
          $template->setValue('meta', $meta);
          $template->setValue('anioI', $anioI);
          $template->setValue('mesI', $mesI);
          $template->setValue('diaI', $diaI);
          //$template->setValue('mesA', $mesA);
          $template->setValue('resultadoF', $resultadoF);
          $template->setValue('institucion', $institucion);
          $template->setValue('municipio', $municipio);
          $template->setValue('direccion', $direccion);
          //$template->setValue('diaA', $diaA);
          $template->setValue('noSolicitud', $no_solicitud);


          $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
          $template->saveAs($tempFile);

          $header = [
            "Content-Type: application/octet-stream",
          ];

          return response()->download($tempFile, 'DOCUMENTO_OTA.docx', $header)->deleteFileAfterSend($shouldDelete = true);
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
          //throw $th;
          return back($e->getCode());
        }
      }
    }
    return response()->json([
      'status' => 400,
      'error' => "You can't download OTA"
    ]);
  }

  public function evaluarSolicitud(Request $request, $requisition_id)
  {
    if (Auth::user() != null) {
      $requisition = Requisition::find($requisition_id);
      if ($requisition->evaNum == 6) {
        if ($request->evaluacion == "1") {
          $requisition->cata = true;
        } else if ($request->evaluacion == "0") {
          $requisition->cata = false;
        } else {
          return response()->json([
            'status' => 400,
            'error' => "You can't update "
          ]);
        }
        //Notificar a los administradores
        $requisition->ota = true;
        $requisition->save();
        return redirect(route('requisitions.show', $requisition->id));
      }
    }
    return response()->json([
      'status' => 400,
      'error' => "You can't update request"
    ]);
  }

  public function downloadStatus($requisition_id)
  {
    if (Auth::user() != null) {
      $requisition = Requisition::find($requisition_id);
      $formats = Format::where('requisition_id', $requisition_id)->get();
      $elements = Element::searchrequisitionid($requisition_id)->get();
      $plans = Plan::searchrequisitionid($requisition_id)->get();
      try {
        //code...
        $career = Career::find($requisition->career_id);
        $institution = Institution::find($career->institution_id);
        $municipalitie = Municipality::find($institution->municipalitie_id);
        $mes = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
        $anioI = date("Y", strtotime(date('Y-m-d')));
        $mesI = $mes[ltrim(date("m", strtotime(date('Y-m-d'))), "0") - 1];
        $diaI = date("d", strtotime(date('Y-m-d')));
        $institucion = $institution->nombre;
        $municipio = $municipalitie->nombre;
        $direccion = $institution->direccion;
        $estado = $requisition->estado;
        $correo = $institution->email;
        $carrera = $career->nombre;
        $formatos = '';
        $elementos = '';
        $planes = '';
        $formatNames = array(
          "Plan de Estudios",
          "Mapa Curricular",
          "Programa de Estudio",
          "Estructura e Instalaciones",
          "Plataforma Tecnológica"
        );
        $elementName = array(
          "Documento de posesión legal del inmueble",
          "El inmueble en el que se impartirá el RVOE",
          "Dimensiones del predio (m2)",
          "Dimensiones de construcción (m2)",
          "Dimensiones útiles para la impartición del Plan y Programas de estudio",
          "¿En el inmueble se realizan actividades que están directa o indirectamente relacionadas con otros servicios educativos?",
          "Detallar las actividades que se realizan en el inmueble",
          "Tipo de estudios que se imparten en el inmueble actualmente",
          "Descripción del área fisíca destinada para el resguardo de la documentación de control escolar",
          "Número total de aulas en el inmueble",
          "Estado de las Aulas destinadas que serán destinadas a la impartición del Plan y Programa de estudio objeto de RVOE",
          "Número de aulas que serán destinadas a la impartición del Plan y Programas de estudio objeto de RVOE",
          "Capacidad promedio de cada aula (cupo de alumnos)",
          "Tipo de ilumminación de las aulas",
          "Tipo de ventilación de las aulas",
          "Número total de cubiculos en el inmueble",
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
          "¿Cuenta con equipo tecnológico?",
          "Condiciones en las que se encuentra el equipo",
          "Tipo del equipo tecnológico al servicio del alumno",
          "Cantidad de equipo tecnológico por alumno",
          "Ubicación del equipo tecnológico del alumno dentro del inmueble",
          "Tipo de equipo tecnológico al servicio del administrativo",
          "Cantidad de equipos por administrativo",
          "Ubicación del equipo tecnológico del administrativo dentro de l inmueble",
          "Tipo de equipo tecnológico al servicio del docente",
          "Cantidad de equipos por docente",
          "Ubicación del equipo tecnológico del docente dentro del inmueble",
          "Cuenta con servicio de telefonia",
          "Total de equipos telefónicos",
          "¿Cuenta con el servicio de internet?",
          "Velocidad en MB",
          "Razonamiento técnico que justifica la idoneidad de las instalaciones para el servicio educativo que se brindará",
          "Población estudiantil máxima que podrá ser atendida en el inmueble",
          "¿Existe un plan interno de protección civil?",
          "Nivel de accesibilidad",
          "Forma en la que se abastece de agua",
          "Tipo de drenaje sanitario existente en el inmueble",
          "Número total de sanitarios en el inmueble",
          "¿El diseño de la infraestructura educativa incorporó un modelo de sostenibilidad?",
          "¿El diseño de la infraestructura educativa incorporó el uso de energía sostenible?"
        );
        $planNames = array(
          "Planes y programas de estudios",
          "Reglamentos",
          "Plan de mejora continua"
        );
        if (is_null($requisition->rvoe)) {
          $no_solicitud = 'No disponible';
        } else {
          $no_solicitud = $requisition->rvoe;
        }

        if ($requisition->cata == true) {
          $resultado = 'Cumple con los requisitos mínimos de esta disposición';
          $resultadoF = '"Favorable"';
        } else if (is_null($requisition->cata)) {
          $resultado = 'Falta evaluar solicitud';
          $resultadoF = '"No disponible"';
        } else {
          $resultado = 'No cumple con los requisitos mínimos de esta disposición';
          $resultadoF = '"No Favorable"';
        }

        switch ($requisition->meta) {
          case 'solicitud':
            $meta = 'Solicitud';
            break;
          case 'domicilio':
            $meta = 'Cambio de domicilio';
            break;
          case 'planEstudios':
            $meta = 'Cambio de plan de estudios';
            break;
        }

        //Retorno de evaluaciones
        //Formatos
        foreach ($formats as $format) {
          if ($format->valido == false) {
            $formatos = $formatos . '*' . $formatNames[$format->formato - 1] . ', anexo ' . $format->formato . ', mantiene un estado INVALIDO con el comentario: ' . $format->justificacion . ".";
          }
        }
        //Elementos
        foreach ($elements as $element) {
          if ($element->existente == false) {
            $noRequired = [7, 12, 14, 15, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 31, 32, 33, 34, 35, 36, 37, 38, 39, 41, 48, 49, 50];
            if (!in_array($element->elemento, $noRequired)) {
              $elementos = $elementos . '*' . $elementName[$element->elemento - 1] . ', mantiene un estado INVALIDO con el comentario: ' . $element->observacion . ".";
            }
          }
        }
        //Planes
        foreach ($plans as $plan) {
          if ($plan->ponderacion < 60) {
            $planes = $planes . '*' . $planNames[$plan->plan - 1] . ', tiene una calificación del ' . $plan->ponderacion . '% con el comentario: ' . $plan->comentario . ".";
          }
        }

        $template = new \PhpOffice\PhpWord\TemplateProcessor('DOCUMENTO_ESTADO.docx');

        //Documento
        $template->setValue('anioI', $anioI);
        $template->setValue('mesI', $mesI);
        $template->setValue('diaI', $diaI);
        //Solicitud
        $template->setValue('tipoS', $meta);
        $template->setValue('noSolicitud', $no_solicitud);
        $template->setValue('estadoS', $estado);
        //Institucion
        $template->setValue('nombreI', $institucion);
        $template->setValue('municipioI', $municipio);
        $template->setValue('direccionI', $direccion);
        $template->setValue('carreraI', $carrera);
        $template->setValue('emailI', $correo);
        //formatos
        $template->setValue('formatos', $formatos);
        $template->setValue('elementos', $elementos);
        $template->setValue('planes', $planes);

        //Conclusión                
        $template->setValue('evaluacionF', $resultadoF);
        $template->setValue('evaluacion', $resultado);

        $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
        $template->saveAs($tempFile);

        $header = [
          "Content-Type: application/octet-stream",
        ];

        return response()->download($tempFile, 'DOCUMENTO_ESTADO.docx', $header)->deleteFileAfterSend($shouldDelete = true);
      } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
        //throw $th;
        return back($e->getCode());
      }
    }
    return response()->json([
      'status' => 400,
      'error' => "You can't download STATUS"
    ]);
  }

  public function revertirEvaluacion($requisition_id)
  {
    if (Auth::user() != null) {
      $this->revisar_activo($requisition_id);
      $requisition = Requisition::find($requisition_id);
      if ($requisition->evaNum != 1 && in_array($requisition->estado, ['activo', 'rechazado', 'pendiente'])) {
        // if($requisition->evaNum == 5){
        //   $formatoInstalaciones = $requisition->formatoInstalaciones;
        //   if($formatoInstalaciones != null){
        //     unlink(public_path('img/formatos/instalaciones/' . $formatoInstalaciones));
        //   }  
        // }
        $requisition->numero_solicitud = null;
        $requisition->evaNum = $requisition->evaNum - 1;
        $requisition->estado = 'pendiente';
        $requisition->rvoe = null;
        $requisition->save();
      }
      return redirect(route('requisitions.show', $requisition->id));
    }
  }

  private function revisar_activo($requisition)
  {
    if (Auth::user() != null) {
      $data = Requisition::find($requisition);
      if ($data->estado == 'activo') {
        $dateNow = date('Y-m-d');
        $dateVencimiento = $data->fecha_vencimiento;
        $dateNow_time = strtotime($dateNow);
        $dateVencimiento_time = strtotime($dateVencimiento);
        if ($dateVencimiento_time < $dateNow_time) {
          $data->estado = 'inactivo';
          $data->update();
        }
      }
    }
  }

  public function cambiarEstado($requisition, $estado)
  {
    if (Auth::user() != null) {
      $data = Requisition::find($requisition);
      if ($estado == "activo") {
        $data->fecha_direccion = date("Y-m-d");
      }
    }
  }
}
