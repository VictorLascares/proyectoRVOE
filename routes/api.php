<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\InstitutionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//****************USER ROUTES*****************
//CRUD USER
Route::resource('user', UserController::class);
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
Route::resource('institution', InstitutionController::class);
//CREATE INSTITUTION
//Route::post('institution', [InstitutionController::class,'store']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
