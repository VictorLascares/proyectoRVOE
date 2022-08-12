<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Institution;
use App\Models\Municipality;
use App\Models\Requisition;
use Illuminate\Http\Request;

class ConsultController extends Controller
{
    public function index()
    {
        $municipalities = Municipality::all();
        return view('pages.consult', [
            'municipalities' => $municipalities,
            'requisition' => []
        ]);
    }

    public function searchRequisitionMunicipality(Request $request)
    {
        if ($request->ajax()) {
            $institutions = Institution::where('municipalitie_id', $request->municipalityId)->get();
            $careers = array();
            foreach ($institutions as $institution) {
                $result = Career::where('institution_id', $institution->id)->get(); 
                foreach ($result as $career) {
                    array_push($careers, $career);
                }
            }
            $requisitions = array();
            foreach ($careers as $career) {
                $result = Requisition::where('career_id', $career->id)->get();
                foreach ($result as $requisition) {
                    array_push($requisitions, $requisition);
                }
            }
            return response()->json([
                'requisitions' => $requisitions,
                'careers' => $careers
            ]);
        }
    }

    public function searchRequisitionInstitution(Request $request)
    {
        if ($request->ajax()) { 
            $careers = Career::where('institution_id', $request->institutionId)->get();
            $requisitions =  array();
            foreach ($careers as $career) {
                $result = Requisition::where('career_id', $career->id)->get();
                foreach ($result as $requisition) {
                    array_push($requisitions, $requisition);
                }
            }
            return response()->json([
                'requisitions' => $requisitions,
                'careers' => $careers
            ]);
        }

    }

    public function searchRequisitionCareer(Request $request)
    {
        if ($request->ajax()) {
            $requisition = Requisition::where('career_id', $request->careerId)->get();
            $career = Career::where('id', $request->careerId)->get();
            return response()->json([
                'requisition' => $requisition,
                'career' => $career
            ]);
        }
    }

    public function searchRequisitionRvoe(Request $request)
    {
        if ($request->ajax()) {
            $requisition = Requisition::where('rvoe', $request->rvoe)->get();
            $career = Career::where('id', $requisition->career_id)->get();
            return response()->json([
                'requisition' => $requisition,
                'career' => $career
            ]);
        }
    }
}
