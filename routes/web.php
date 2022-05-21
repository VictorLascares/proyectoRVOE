<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\PlanController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.home');
});


//All secure URL's
//****************USER ROUTES*****************
//CRUD USER
Route::resource('users', UserController::class);
//UPDATE PASSWORD
Route::put('user/update/{id}',[UserController::class,'updatePSW']);
//LOGOUT USER
Route::get('logout',[UserController::class,'logout']);
//LOGIN USER
Route::post('login',[SessionController::class,'login']);

//****************CAREER ROUTES*****************
//CRUD CAREER
Route::resource('careers', CareerController::class);
Route::get('careers', [CareerController::class,'getCareers']);

//****************INSTITUTION ROUTES*****************
//CRUD INSTITUTION
Route::resource('institutions', InstitutionController::class);


//****************REQUISITION ROUTES*****************
//CRUD REQUISITION
Route::resource('requisitions',RequisitionController::class);
Route::post('requisitions/create/{career_id}', [RequisitionController::class,'crearPorCarrera']);

//Ruta para consultar rvoe
Route::get('/consult', [RequisitionController::class,'searchRequisition']);
//Ruta para ver las requisiciones en el inicio
Route::get('/dashboard', [RequisitionController::class,'showRequisition']);




//****************FORMATS ROUTES*****************
//Ruta para realizar evaluación de los formatos
Route::get('/evaluate/formats/{requisition_id}', [FormatController::class,'evaluateFormats']);
//Ruta para actualizar los formatos
Route::post('/update/formats', [FormatController::class,'updateFormats']);


//****************ELEMENTS ROUTES*****************
//Ruta para realizar evaluación de los elementos
Route::get('/evaluate/elements/{requisition_id}', [ElementController::class,'evaluateElements']);
//Ruta para actualizar los elementos
Route::post('/update/elements', [ElementController::class,'updateElements']);

//****************PLANS ROUTES*****************
//Ruta para realizar evaluación de los elementos
Route::get('/evaluate/plans/{requisition_id}', [PlanController::class,'evaluatePlans']);
//Ruta para actualizar los elementos
Route::post('/update/plans', [PlanController::class,'updatePlans']);


//*************************AUTH USER**************************
Route::get('iniciar',['App\Http\Controllers\SessionController','iniciar']);
Route::get('salir',['App\Http\Controllers\SessionController','salir']);

//RUTA PARA VALIDAR LAS CREDENCIALES
Route::post('validar',['App\Http\Controllers\SessionController','validar']);

//RUTAS PARA REDIRIGIR A LAS VISTAS
Route::get('login', function(){
    return view('auth.login');
});
Route::get('register', function(){
    return view('auth.register');
});

Route::post('/verificarcorreo',['App\Http\Controllers\RegisterController','verificarCorreo']);


//Descargar archivo OTA
Route::get('/download/{requisition_id}',[RequisitionController::class,'downloadOta']);
Route::get('/evaluacion-anterior/{requisition_id}',[RequisitionController::class,'revertirEvaluacion']);
