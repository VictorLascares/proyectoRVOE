<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Institution;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class InstitutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('getInstitutions');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user() != null) {
            $municipalities = Municipality::all();
            $institutions = Institution::paginate(12);
            return view('instituciones.index', compact('institutions', 'municipalities'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('instituciones.create');
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
            $this->validate($request, [
                'nombre' => ['required', 'max:255'],
                'titular' => ['required', 'max:60'],
                'repLegal' => ['required','max:60'],
                'email' => ['required','unique:institutions','email','max:60'],
                'municipalitie_id' => ['required'],
                'direccion' => ['required', 'max:255'],
                'logotipo' => ['required']
            ]);
            $institution = new Institution();
            if ($request->logotipo) {
                $path = $request->file('logotipo')->getRealPath();
                $image = Image::make($path, null);
                $image->resize(250, 250)
                    ->save();
                $response = Cloudinary()->upload($path);
                $secureURL = $response->getSecurePath();
                $public_id = $response->getPublicId();
            }

            $institution->logotipo = $secureURL;
            $institution->logo_public_id = $public_id;
            $institution->nombre = $request->nombre;
            $institution->titular = $request->titular;
            $institution->repLegal = $request->repLegal;
            $institution->email = $request->email;
            $institution->direccion = $request->direccion;
            $institution->municipalitie_id = $request->municipalitie_id;
            $institution->save();
            return redirect('institutions');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($institution)
    {
        if (Auth::user() != null) {
            $institution = Institution::find($institution);
            $municipalities = Municipality::all();
            $areas = Area::all();
            $careers = DB::table('institutions')
                ->join('careers', 'institutions.id', '=', 'careers.institution_id')
                ->select('careers.*')
                ->where('institutions.id', $institution->id)->paginate(5);
            return view('instituciones.show', compact('institution', 'careers', 'municipalities', 'areas'));
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
    public function update(Request $request, $institution)
    {
        if (Auth::user() != null) {
            $data = Institution::find($institution);
            $logotipo = $request->file('logotipo');
            if ($logotipo != null) {
                Cloudinary()->destroy($data->logo_public_id);

                $path = $request->file('logotipo')->getRealPath();
                $image = Image::make($path, null);
                $image->resize(250, 250)
                    ->save();
                $response = Cloudinary()->upload($path);
                $secureURL = $response->getSecurePath();
                $public_id = $response->getPublicId();

                $institution->logotipo = $secureURL;
                $institution->logo_public_id = $public_id;
            }
            $data->municipalitie_id = $request->municipalitie_id;
            $data->nombre = $request->nombre;
            $data->titular = $request->titular;
            $data->repLegal = $request->repLegal;
            $data->email = $request->email;
            $data->direccion = $request->direccion;
            $data->save();
            return redirect()->route('institutions.show',$institution);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($institution)
    {
        if (Auth::user() != null) {
            $data = Institution::find($institution);
            Cloudinary()->destroy($data->logo_public_id);
            $data->delete();
            return redirect('institutions');
        }
    }

    public function getInstitutions(Request $request)
    {
        if ($request->ajax()) {
            $institutions = Institution::where('municipalitie_id', $request->municipalityId)->get();
            $institutionArray = array();
            foreach ($institutions as $institution) {
                $institutionArray[$institution->id] = $institution->nombre;
            }
            return response()->json($institutionArray);
        }
    }
}
