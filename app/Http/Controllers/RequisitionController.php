<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Element;
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
            $requisition_max = Requisition::searchcareeridMax($request->career_id)->first();
            if (!is_null($requisition_max)) {
                $dateNow = date('Y-m-d');
                $dateBack = $requisition_max->fecha_vencimiento;
                $dateNow_time = strtotime($dateNow);
                $dateBack_time = strtotime($dateBack);
                if ($requisition_max->estado == 'revocado' || ($dateBack_time < $dateNow_time && $requisition_max->fecha_vencimiento != null)) {
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
                return view('requisiciones.show', compact('data','formatNames', 'career', 'institution', 'elements', 'plans', 'formats', 'area', 'errors'));
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
            if ($requisition->noEvaluacion == 6 and $requisition->ota == true) {
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
}
