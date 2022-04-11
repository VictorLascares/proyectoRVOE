<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\RegisterContoller;
use App\Http\Controllers\SessionContoller;

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
Route::get('/consult', function () {
    return view('pages.consult');
});
Route::get('/dashboard', function () {
    return view('pages.dashboard');
});


//All secure URL's
//****************USER ROUTES*****************
//CRUD USER
Route::resource('users', UserController::class);
//CREATE USER
//Route::post('user', [UserController::class,'store']);
//SELECT USERS
//Route::get('users', [UserController::class,'index']);
//UPDATE USER
//Route::put('user/{id}', [UserController::class,'update']);
//VIEW USER
//Route::get('user/{id}', [UserController::class,'show']);
//DELETE USER
//Route::delete('user/{id}', [UserController::class,'destroy']);
//UPDATE PASSWORD
Route::put('user/update/{id}',[UserController::class,'updatePSW']);
//LOGOUT USER
Route::get('logout',[UserController::class,'logout']);
//LOGIN USER
Route::post('login',[SessionController::class,'login']);

//****************CAREER ROUTES*****************
//CRUD CAREER
Route::resource('career', CareerController::class);
//SELECT CAREER
//Route::get('careers', [CareerController::class,'index']);
//CREATE CAREER
//Route::post('career', [CareerController::class,'store']);
//VIEW CAREER
//Route::get('career/{id}', [CareerController::class,'show']);
//UPDATE CAREER
//Route::put('career/{id}', [CareerController::class,'update']);
//DELETE CAREER
//Route::put('career/{id}', [CareerController::class,'update']);


//****************INSTITUTION ROUTES*****************
//CRUD INSTITUTION
Route::resource('institutions', InstitutionController::class);
//CREATE INSTITUTION
//Route::post('institution', [InstitutionController::class,'store']);


//****************REQUISITION ROUTES*****************
//CRUD REQUISITION
Route::resource('requisition',RequisitionController::class);

//****************ELEMENT ROUTES*****************
//CRUD ELEMENT
Route::resource('element',ElementController::class);
    


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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
