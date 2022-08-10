<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $municipalities = Municipality::all();
        return view('pages.consult', [
            'municipalities' => $municipalities,
            'requisition' => []
        ]);
    }
}
