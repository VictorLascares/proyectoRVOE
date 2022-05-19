<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use App\Models\Format;
use App\Models\Institution;
use App\Models\Career;
use App\Models\Element;


class FormatController extends Controller
{
  //Funcion para realizar la evaluación de los formatos
  public function evaluateFormats($requisition_id)
  {
    $requisition = Requisition::find($requisition_id);
    $career = Career::find($requisition->career_id);
    $institution = Institution::find($career->institution_id);
    $formats = Format::searchrequisitionid($requisition->id)->get();

    if (isset($requisition)) {
      return response()->json([
        'requisition' => $requisition,
        'career' => $career,
        'institution' => $institution,
        'formats' => $formats
      ]);
    } else {
      return response()->json([
        'error' => 'Data not found'
      ]);
    }
  }

  //Funcion para realizar la actualización de los formatos
  public function updateFormats(Request $request)
  {
    $requisition = Requisition::find($request->requisition_id);
    if ($requisition->noEvaluacion < 4 && $requisition->estado == 'pendiente') {
      for ($formatName = 1; $formatName < 6; $formatName++) {
        $format = Format::searchformato($formatName)->searchrequisitionid($requisition->id)->first();
        $formato = 'anexo' . $formatName;
        $formatoj = $formato . 'j';
        $format->valido = true;
        if ($request->input($formato) == false || $request->input($formato) == 'false') {
          if (is_null($request->formatoj)) {
            $format->justificacion = 'No se encuentra el formato';
          } else {
            $format->justificacion = $request->$formatoj;
          }
          $format->valido = false;
          $requisition->estado = 'rechazado';
        }
        $format->save();
      }
      if ($requisition->noEvaluacion == '3') {
        for ($elementName = 1; $elementName < 53; $elementName++) {
          $element = new Element();
          $element->elemento = $elementName;
          $element->requisition_id = $requisition->id;
          $element->save();
        }
      }
      $requisition->noEvaluacion = $requisition->noEvaluacion + 1;
      $requisition->save();
    }
    return redirect(route('requisitions.show', $requisition->id));
  }
}
