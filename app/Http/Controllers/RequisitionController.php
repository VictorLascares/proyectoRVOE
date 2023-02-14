<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Opinion;
use App\Models\Element;
use App\Models\Comment;
use App\Models\Plan;
use App\Models\Institution;
use App\Models\User;
use App\Models\Career;
use App\Models\Municipality;
use Illuminate\Support\Facades\Auth;

use Mail;

use App\Mail\NotifyMail;
use Exception;

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
      $requisition_max = Requisition::searchcareeridMax($request->career_id)->first();
      if (!is_null($requisition_max)) {
        $dateNow = date('Y-m-d');
        $dateBack = $requisition_max->dueDate;
        $dateNow_time = strtotime($dateNow);
        $dateBack_time = strtotime($dateBack);
        if ($requisition_max->status == 'revocado' || ($dateBack_time < $dateNow_time && $requisition_max->fecha_vencimiento != null)) {
          $requisition->procedure = $request->procedure;
          $requisition->career_id = $request->career_id;
          $data = $requisition->save();
          for ($formatName = 1; $formatName < 6; $formatName++) {
            $format = new Format();
            $format->format = $formatName;
            $format->requisition_id = $requisition->id;
            $format->save();
          }

          try{
            $users = User::searchDireccion()->get();
            $procedure = $request->procedure;
            if ($request->procedure == "planEstudios") {
              $procedure = "plan de estudios";
            }
            if ($request->procedure == "domicilio") {
              $procedure = "cambio de domicilio";
            }
            foreach ($users as $user) {
              Mail::to($user->email)->send(new NotifyMail($procedure, $career, $institution));
            }
          }catch(Exception $e){
            error_log("EL usuario no se encuentra registrado: ".e);
          };
          return redirect('requisitions');
        } else {
          return response()->json([
            'status' => 400,
            'error' => "No se puede generar una requisición en estos momentos, consulte el historial de requisiciones."
          ]);
        }
      } else {
        $requisition->procedure = $request->procedure;
        $requisition->career_id = $request->career_id;
        $data = $requisition->save();
        for ($formatName = 1; $formatName < 6; $formatName++) {
          $format = new Format();
          $format->format = $formatName;
          $format->requisition_id = $requisition->id;
          $format->save();
        }

        $users = User::searchDireccion()->get();
        $procedure = $request->procedure;
        if ($request->procedure == "planEstudios") {
          $procedure = "plan de estudios";
        }
        if ($request->procedure == "domicilio") {
          $procedure = "cambio de domicilio";
        }
        foreach ($users as $user) {
          Mail::to($user->email)->send(new NotifyMail($procedure, $career, $institution));
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
        $plans = Plan::searchrequisitionid($data->id)->get();
        $formatNames = array(
          "Plan de Estudios",
          "Mapa Curricular",
          "Programa de Estudio",
          "Estructura e Instalaciones",
          "Plataforma Tecnológica"
        );
        $errors = [];
        if ($data->noEvaluacion <= 4) {
          foreach ($formats as $format) {
            if (!$format->valido) {
              array_push($errors, $format);
            }
          }
        } elseif ($data->noEvaluacion <= 5) {
          foreach ($elements as $element) {
            if (!$element->existente) {
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
      if ($request->estado == 'latencia' && $data->status == 'activo') {
        $date = date('Y-m-d');
        $date = date('Y-m-d', strtotime($date));
        $dateMax = date("Y-m-d", strtotime($data->requisitionDate . "+ 2 year"));
        if ($dateMax >= $date) {
          $data->latencyDate = $date;
          $data->status = $request->estado;
        }
        $data->update($request->all());
        return redirect(route('requisitions.show', $data->id));
      } else if ($request->estado == 'activo' && $data->status == 'latencia') {
        $dateLatencia = date_create(date('Y-m-d', strtotime($data->latencyDate)));
        $dateVencimiento = date_create(date('Y-m-d', strtotime($data->dueDate)));
        $diasRestantes = date_diff($dateVencimiento, $dateLatencia);
        $differenceFormat = '%a';
        $date = date('Y-m-d');
        $date = date("Y-m-d", strtotime($date . "+ " . $diasRestantes->format($differenceFormat) . " day"));
        $data->dueDate = $date;
        $data->status = $request->estado;
        $data->update($request->all());
        return redirect(route('requisitions.show', $data->id));
      } else if ($request->estado == 'revocado' && $data->status == 'activo') {
        $data->revokedDate = date('Y-m-d', strtotime($date));
        $data->status = $request->estado;
        $data->update($request->all());
        return redirect(route('requisitions.show', $data->id));
      }elseif(($request->estado == 'activo' || $request->estado == 'rechazar') && $data->status == 'pendiente' && $data->evaNum >= 7 ){
        if(is_null($request->fechaActivo)){
          $data->requisitionDate = date('Y-m-d', strtotime($date));
        }else{
          // dd($request->fechaActivo);
          $data->requisitionDate = $request->fechaActivo;
        }
        $data->ota = true;
        //Generar numero de rvoe
        if (is_null($data->requestNumber)) {
          $requisitions = Requisition::searchDate(date("Y", strtotime($data->requisitionDate)))->noSolicitud();
          $noRequi = Requisition::searchDate(date("Y", strtotime($data->requisitionDate)))->count() + 1;
          $existe = false;
          for($count = 1; $count < $noRequi; $count++){
              foreach($requisitions as $requi){
                  if($requi->requestNumber == $count){
                      $existe = true;
                  }
              }
              if(!$existe){
                  $data->requestNumber = $count;
              }
              $count++;
          }
        }
        if(is_null($data->rvoe)){
          $principio = 'SE/SSPE/DP';
          $anioI = date("Y", strtotime($data->requisitionDate));
          if ($data->requestNumber < 10) {
              $no_solicitud = '00' . $data->requestNumber;
          } else if ($data->requestNumber < 100) {
              $no_solicitud = '0' . $data->requestNumber;
          } else {
              $no_solicitud = $data->requestNumber;
          }
          $data->rvoe = $principio.'/'.$no_solicitud.'/'.$anioI;
        }
        if($request->estado == 'activo'){
          $data->status = $request->estado;
          $data->dueDate = date("Y-m-d", strtotime($data->requisitionDate . "+ 3 year"));
        }
        if($request->estado == 'rechazar'){
          $data->status = 'rechazado'; 
        }
        $data->update($request->all());
        return redirect(route('requisitions.show', $data->id));
      }elseif($request->estado == 'eliminar' && $data->status == 'pendiente'){
        if ($requisition->facilitiesFormat != null) {
          Cloudinary()->destroy($requisition->format_public_id);
        }  
        if ($requisition->opinionFormat != null) {
          Cloudinary()->destroy($requisition->opinion_public_id);
        }
        if ($requisition->planFormat != null) {
          Cloudinary()->destroy($requisition->plan_public_id);
        }
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
      return response()->json([
        'status' => 400,
        'error' => "something went wrong"
      ]);
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
      if ($requisition->evaNum == 7 and $requisition->ota == true) {
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
          $diaA = date("d", strtotime($requisition->requisitionDate));
          $mesA = $mes[ltrim(date("m", strtotime($requisition->requisitionDate)), "0") - 1];
          //$diaA = date("d", strtotime($requisition->fecha_vencimiento));
          $institucion = $institution->name;
          $municipio = $municipalitie->name;
          $direccion = $institution->address;
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

          switch ($requisition->procedure) {
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
          $template->setValue('mesA', $mesA);
          $template->setValue('resultadoF', $resultadoF);
          $template->setValue('institucion', $institucion);
          $template->setValue('municipio', $municipio);
          $template->setValue('direccion', $direccion);
          $template->setValue('diaA', $diaA);
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
      if ($requisition->evaNum == 7) {
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
      $opinions = Opinion::searchrequisitionid($requisition_id)->get();
      $comments = Comment::searchrequisitionid($requisition_id)->get();
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
        $institucion = $institution->name;
        $municipio = $municipalitie->name;
        $direccion = $institution->address;
        $estado = $requisition->status;
        $correo = $institution->email;
        $carrera = $career->name;
        $formatNames = array(
          'Plan de Estudios',
          'Mapa Curricular',
          'Programa de Estudio',
          'Estructura e Instalaciones',
          'Plataforma Tecnológica'
        );
        $elementNames = array(
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
        $planNames = array(
          "Grado académico",
          "Modalidad educativa",
          "Área de formación",
          "Duración mínima en semanas",
          "Carga horaria semanal",
          "Tipo de diseño",
          "Número de ciclos escolares que integran el plan de estudios",
          "Fines del aprendizaje",
          "Perfil de ingreso",
          "Antecedente académico",
          "Perfil de egreso",
          "Requisitos de ingreso",
          "Requisitos de permanencia; Estrategias y lineamientos para asegurar el ingreso, permanencia, egreso y titulación",
          "Proyección de la matrícula escolar",
          "Modelo educativo (modelo teórico - pedagógico del plan curricular)",
          "Modalidad de evaluación del plan de estudios (justificación teórica)",
          "Periodicidad de evaluación del plan de estudios",
          "Justificación de la modalidad elegida (incluir viabilidad del programa con base en la modalidad propuesta)",
          "Mapa curricular"
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

        switch ($requisition->procedure) {
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
        $secciones = array('Pertinencia social','Pertinencia económica','Estudio de campo laboral','Proyección de matricula','Financiamiento');
        $pertinenciaSocial = 0;
        $pertinenciaAcademica = 0;
        $pertinenciaLaboral = 0;
        $proyeccionMatricula = 0;
        $financiamiento = 0;
        for($f = 1; $f<30;$f++){
          for($j = 0;$j<29;$j++){
            if($f <15){
              if($opinions[$j]->opinion == $f){
                switch($opinions[$j]->status){
                  case 'suficiente':
                    $pertinenciaSocial += $opinions[$j]->top;
                    break;
                  case 'insuficiente':
                    $pertinenciaSocial += $opinions[$j]->top/2;
                    break;
                  case 'na':
                  default:
                    break;
                }
              }
            }elseif($f < 20){
              if($opinions[$j]->opinion == $f){
                switch($opinions[$j]->status){
                  case 'suficiente':
                    $pertinenciaAcademica += $opinions[$j]->top;
                    break;
                  case 'insuficiente':
                    $pertinenciaAcademica += $opinions[$j]->top/2;
                    break;
                  case 'na':
                  default:
                    break;
                }
              }
            }elseif ( $f < 26){
              if($opinions[$j]->opinion == $f){
                switch($opinions[$j]->status){
                  case 'suficiente':
                    $pertinenciaLaboral += $opinions[$j]->top;
                    break;
                  case 'insuficiente':
                    $pertinenciaLaboral += $opinions[$j]->top/2;
                    break;
                  case 'na':
                  default:
                    break;
                }
              }
            }elseif($f == 26){
              if($opinions[$j]->opinion == $f){
                switch($opinions[$j]->status){
                  case 'suficiente':
                    $proyeccionMatricula += $opinions[$j]->top;
                    break;
                  case 'insuficiente':
                    $proyeccionMatricula += $opinions[$j]->top/2;
                    break;
                  case 'na':
                  default:
                    break;
                }
              }
            }else{
              if($opinions[$j]->opinion == $f){
                switch($opinions[$j]->status){
                  case 'suficiente':
                    $financiamiento += $opinions[$j]->top;
                    break;
                  case 'insuficiente':
                    $financiamiento += $opinions[$j]->top/2;
                    break;
                  case 'na':
                  default:
                    break;
                }
              }
            }
          }
        }
        $template = new \PhpOffice\PhpWord\TemplateProcessor('DOCUMENTO_ESTADO.docx');
        $puntajes = array($pertinenciaSocial,$pertinenciaAcademica,$pertinenciaLaboral,$proyeccionMatricula,$financiamiento);
        $puntajeGeneral = 0;
        $puntajeDetallado = 0;
        for($f = 1; $f<20;$f++){
          for($j = 0;$j<19;$j++){
            if($f < 12){
              if($plans[$j]->plan == $f){
                switch($plans[$j]->status){
                  case 'cumple':
                    $template->setValue(array('plan'.$f-1,'seccionGeneral'.$f-1,'porcentajeGeneral'.$f-1), array($plans[$j]->plan,$planNames[$f-1],'100'));
                    $puntajeGeneral += 100;
                    break;
                  case 'parcialmente':
                    $template->setValue(array('plan'.$f-1,'seccionGeneral'.$f-1,'porcentajeGeneral'.$f-1), array($plans[$j]->plan,$planNames[$f-1],'50'));
                    $puntajeGeneral += 50;
                    break;
                  case 'na':
                    $template->setValue(array('plan'.$f-1,'seccionGeneral'.$f-1,'porcentajeGeneral'.$f-1), array($plans[$j]->plan,$planNames[$f-1],'0'));
                  default:
                    break;
                }
              }
            }else{
              if($plans[$j]->plan == $f){
                switch($plans[$j]->status){
                  case 'cumple':
                    $template->setValue(array('plan'.$f-1,'seccionDetallada'.$f-1,'porcentajeDetallado'.$f-1), array($plans[$j]->plan,$planNames[$f-1],'100'));
                    $puntajeDetallado += 100;
                    break;
                  case 'parcialmente':
                    $template->setValue(array('plan'.$f-1,'seccionDetallada'.$f-1,'porcentajeDetallado'.$f-1), array($plans[$j]->plan,$planNames[$f-1],'50'));
                    $puntajeDetallado += 50;
                    break;
                  case 'na':
                    $template->setValue(array('plan'.$f-1,'seccionDetallada'.$f-1,'porcentajeDetallado'.$f-1), array($plans[$j]->plan,$planNames[$f-1],'0'));
                  default:
                    break;
                }
              }
            }
          }
        }
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
        $rechazados = 0;
        for($i=1;$i<6;$i++){
          for($j = 0;$j<5;$j++){
            if($formats[$j]->format == $i){
              if($formats[$j]->valid){
                $estado = 'Aceptado';
              }
              else{
                $estado = 'Rechazado';
                $rechazados += 1;
              }
              $template->setValue(array('number'.$i-1,'format'.$i-1,'valid'.$i-1,'justification'.$i-1), array($formats[$j]->format,$formatNames[$i-1],$estado,ucfirst($formats[$j]->justification)));
            }
          }    
        }
        $template->setValue('rechazados',$rechazados);
        //Factibilidad y pertinencia
        for($i = 0;$i<5;$i++){
          $template->setValue(array('seccion'.$i,'puntaje'.$i), array($secciones[$i],$puntajes[$i]));
        }
        for($i = 0;$i<3;$i++){
          switch($comments[$i]->name){
            case 'opinionComment':
              $template->setValue('observacionOpinion',$comments[$i]->observation);
              break;
            case 'elementComment':
              $template->setValue('observacionElement',$comments[$i]->observation);
              break;
            case 'planComment':
              $template->setValue('observacionPlan',$comments[$i]->observation);
              break;
          }
        }    
        $template->setValue('totalPuntaje',$puntajes[0]+$puntajes[1]+$puntajes[2]+$puntajes[3]+$puntajes[4]);    
        //Elementos
        $ausentes = 0;
        $elementos = array();
        for($i=1;$i<27;$i++){
          for($j = 0;$j<26;$j++){
            if($elements[$j]->element == $i){
              if($elements[$j]->existing){
                $estado = 'Presente';
              }
              else{
                $estado = 'Ausente';
                array_push($elementos,array('numero' => ($ausentes+1),'elemento' => $elementNames[$i-1]));
                $ausentes += 1;
              }
            }
          }    
        }
        $template->cloneBlock('block_elemento', 0, true, false, $elementos);        
        $template->setValue('ausentes',$ausentes);

        //Planes
        $template->setValue('puntajeGeneral', $puntajeGeneral);
        $template->setValue('puntajeDetallado', $puntajeDetallado);
        $template->setValue('puntajeTotal',$puntajeGeneral + $puntajeDetallado);    
        
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
      if ($requisition->noEvaluacion != 1 && in_array($requisition->estado, ['activo', 'rechazado', 'pendiente'])) {
        // if($requisition->noEvaluacion == 5){
        //   $formatoInstalaciones = $requisition->formatoInstalaciones;
        //   if($formatoInstalaciones != null){
        //     unlink(public_path('img/formatos/instalaciones/' . $formatoInstalaciones));
        //   }  
        // }
        $requisition->numero_solicitud = null;
        $requisition->noEvaluacion = $requisition->noEvaluacion - 1;
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
      if ($data->status == 'activo') {
        $dateNow = date('Y-m-d');
        $dateVencimiento = $data->dueDate;
        $dateNow_time = strtotime($dateNow);
        $dateVencimiento_time = strtotime($dateVencimiento);
        if ($dateVencimiento_time < $dateNow_time) {
          $data->status = 'inactivo';
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
