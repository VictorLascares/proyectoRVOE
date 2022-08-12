<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\RequisitionController;



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

Route::resource('/', HomeController::class);


//All secure URL's
//****************USER ROUTES*****************
//CRUD USER
Route::resource('users', UserController::class);
//UPDATE PASSWORD
Route::put('user/update/{id}',[UserController::class,'updatePSW'])->name('users.updatePSW');

//****************CAREER ROUTES*****************
//CRUD CAREER
Route::resource('careers', CareerController::class);
Route::get('careers', [CareerController::class,'getCareers']);

//****************INSTITUTION ROUTES*****************
//CRUD INSTITUTION
Route::resource('institutions', InstitutionController::class);
Route::get('getinstitutions', [InstitutionController::class, 'getInstitutions']);

//****************REQUISITION ROUTES*****************
//CRUD REQUISITION
Route::resource('requisitions',RequisitionController::class);
Route::post('requisitions/create/{career_id}', [RequisitionController::class,'crearPorCarrera']);

//Rutas para consultar rvoe
Route::get('/consult', [ConsultController::class,'index']);

// Consulta de requisicion por municipio
Route::get('/consultByMunicipality', [ConsultController::class,'searchRequisitionMunicipality']);

// Consulta de requisicion por Institucion
Route::get('/consultByInstitution', [ConsultController::class,'searchRequisitionInstitution']);

// Consulta de requisicion por Carrera
Route::get('/consultByCareer', [ConsultController::class,'searchRequisitionCareer']);

// Consulta de requisicion por Carrera
Route::get('/consultByRvoe', [ConsultController::class,'searchRequisitionRvoe']);

//Ruta para ver las requisiciones en el inicio
Route::get('/dashboard', [RequisitionController::class,'showRequisition']);

//****************FORMATS ROUTES*****************
//Ruta para realizar evaluación de los formatos
Route::get('/evaluate/formats/{requisition_id}', [FormatController::class,'evaluateFormats'])->name('evaluate.formats');
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
//LOGOUT USER
Route::post('logout',[LogoutController::class,'store'])->name('logout');
//LOGIN USER
Route::get('/login',[LoginController::class,'index'])->name('login');
Route::post('/login',[LoginController::class,'store']);

//Descargar archivo OTA
Route::get('/download/{requisition_id}',[RequisitionController::class,'downloadOta']);
Route::get('/evaluacion-anterior/{requisition_id}',[RequisitionController::class,'revertirEvaluacion']);
