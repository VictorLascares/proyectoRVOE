<?php

namespace App\Http\Controllers;

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
        $this->validate($request, [
            'municipio' => 'required'
        ]);

        if ($request->rvoe) {
            $requisition = Requisition::where('rvoe', $request->rvoe)->get();
        } else {
            $requisition = Requisition::where('career_id', $request->career_id)->get();
        }
        return redirect()->view('consult', compact('requisition'));
    }

    public function searchRequisitionInstitution(Request $request)
    {
        $this->validate($request, [
            'municipio' => 'required'
        ]);

        if ($request->rvoe) {
            $requisition = Requisition::where('rvoe', $request->rvoe)->get();
        } else {
            $requisition = Requisition::where('career_id', $request->career_id)->get();
        }
        return redirect()->view('consult', compact('requisition'));
    }

    public function searchRequisitionCareer(Request $request)
    {
        $this->validate($request, [
            'municipio' => 'required'
        ]);

        if ($request->rvoe) {
            $requisition = Requisition::where('rvoe', $request->rvoe)->get();
        } else {
            $requisition = Requisition::where('career_id', $request->career_id)->get();
        }
        return redirect()->view('consult', compact('requisition'));
    }
}
