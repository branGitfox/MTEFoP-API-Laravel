<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\DgController;
use App\Http\Controllers\DirController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\ServController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return DB::table('users')->join('dirs', 'dirs.d_id', '=', 'users.id_dir')->join('servs', 'servs.s_id', '=', 'users.id_serv')->where('users.id',$request->user()->id)->first();

})->middleware('auth:sanctum');

//route pour les ressources de l'api
Route::apiResource('/dg', DgController::class);
Route::apiResource('/dir', DirController::class);
Route::apiResource('/serv', ServController::class);

//route pour les action d'un utilisateur
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/updateStatus/{user_id}', [AuthController::class, 'updateStatus'])->middleware('auth:sanctum');
Route::post('/updateUser/password', [AuthController::class, 'updateUserPassword'])->middleware('auth:sanctum');
Route::post('/updateUser/info', [AuthController::class, 'updateUserInfo'])->middleware('auth:sanctum');
Route::get('/resetPassword/{email}/{token}', [AuthController::class, 'resetPassword']);
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);

//route pour la recuperation de la liste de services
Route::get('/services/{id_dir}', [ServiceController::class, 'getListOfServByDirection']);

//route pour les actions courriers
Route::post('/doc', [CourrierController::class, 'addDoc'])->middleware('auth:sanctum');
Route::get('/docs', [CourrierController::class, 'fetchDocs'])->middleware('auth:sanctum');
Route::get('/docsByDirection', [CourrierController::class, 'fetchDocsByDirection'])->middleware('auth:sanctum');
Route::get('/delDoc/{id_courrier}', [CourrierController::class, 'deleteDoc'])->middleware('auth:sanctum');

//route pour le transfert courriers
Route::post('/transDoc', [MouvementController::class, 'makeMovement']);
Route::get('/getDocsHistory/{doc_id}', [MouvementController::class, 'getMovements']);

