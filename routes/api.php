<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\DgController;
use App\Http\Controllers\DirController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\ServController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\VisitorController;
use App\Models\Mouvement;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return DB::table('users')->join('dirs', 'dirs.d_id', '=', 'users.id_dir')->join('servs', 'servs.s_id', '=', 'users.id_serv')->where('users.id',$request->user()->id)->first();

})->middleware('auth:sanctum');
Route::get('/userCount', [AuthController::class, 'userCount'] )->middleware('auth:sanctum');



//route pour les ressources de l'api
Route::apiResource('/dg', DgController::class);
Route::apiResource('/dir', DirController::class);
Route::apiResource('/serv', ServController::class);

//route pour les action d'un utilisateur
Route::post('/register', [AuthController::class, 'register'])->middleware('auth:sanctum');
Route::get('/usersList', [AuthController::class, 'usersList'])->middleware('auth:sanctum');
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
Route::post('/docUpdateLivre/{id_doc}', [CourrierController::class, 'updateLivre'])->middleware('auth:sanctum');
Route::get('/docs', [CourrierController::class, 'fetchDocs'])->middleware('auth:sanctum');
Route::get('/docsByDirection', [CourrierController::class, 'fetchDocsByDirection'])->middleware('auth:sanctum');
Route::get('/docByDirection/{id_doc}', [CourrierController::class, 'fetchDocByOneByDirection'])->middleware('auth:sanctum');
Route::get('/delDoc/{id_courrier}', [CourrierController::class, 'deleteDoc'])->middleware('auth:sanctum');
Route::post('/findDoc', [CourrierController::class, 'findMyDoc']);

//route pour le transfert courriers
Route::post('/transDoc', [MouvementController::class, 'makeMovement'])->middleware('auth:sanctum');
Route::post('/transDocMove/{m_id}', [MouvementController::class, 'makeMovementMove'])->middleware('auth:sanctum');
Route::post('/docUpdateLivreMove/{id_doc}', [MouvementController::class, 'updateLivre'])->middleware('auth:sanctum');
Route::get('/moveByDirection', [MouvementController::class, 'getListTransDocByDirection'])->middleware('auth:sanctum');
Route::get('/docByService/{id_doc}', [MouvementController::class, 'fetchDocByOneByService'])->middleware('auth:sanctum');
Route::get('/moveByService', [MouvementController::class, 'getListTransDocByService'])->middleware('auth:sanctum');
Route::get('/moveTransferedByService', [MouvementController::class, 'getListTransferedDocByService'])->middleware('auth:sanctum');
Route::get('/getDocsHistory/{doc_id}', [MouvementController::class, 'getMovements']);

//route pour les statistiques
Route::get('/stats/count', [CourrierController::class, 'courrierCount'])->middleware('auth:sanctum');
Route::post('/stats/gotByOwnerCount', [CourrierController::class, 'courrierGotByOwnerCount'])->middleware('auth:sanctum');
Route::post('/stats/notGotByOwnerCount', [CourrierController::class, 'courrierNotGotByOwnerCount'])->middleware('auth:sanctum');
Route::get('/stats/date', [CourrierController::class, 'listDate'])->middleware('auth:sanctum');
Route::get('/stats/notLivred', [CourrierController::class, 'courrierNotLivred'])->middleware('auth:sanctum');
Route::post('/stats/notLivredByPeriod', [CourrierController::class, 'courrierNotLivredByPeriod'])->middleware('auth:sanctum');
Route::get('/stats/livred', [CourrierController::class, 'courrierLivred'])->middleware('auth:sanctum');
Route::post('/stats/livredByPeriod', [CourrierController::class, 'courrierLivredByPeriod'])->middleware('auth:sanctum');
Route::get('/stats/graph', [CourrierController::class, 'graph'])->middleware('auth:sanctum');
Route::get('/stats/countByService', [CourrierController::class, 'numberOfDocByService'])->middleware('auth:sanctum');
Route::post('/stats/countByDirection', [CourrierController::class, 'numberOfDocByDirection']);
Route::post('/stats/countByDirectionByPeriod', [CourrierController::class, 'numberOfDocByDirectionByPeriod']);
Route::post('/stats/period', [CourrierController::class, 'filterPeriodDate']);

//route pour l'augmentation du nombre de visiteurs
Route::get('/visitors', [VisitorController::class, 'increment']);
Route::get('/visitors/charts', [VisitorController::class, 'getViewPeriodForChartLine'])->middleware('auth:sanctum');
Route::post('/visitors/period', [VisitorController::class, 'showNumberOfVisitByPeriod'])->middleware('auth:sanctum');

//route pour le support technique
Route::post('/support', [SupportController::class, 'sendMessage']);
Route::get('/getMessages', [SupportController::class, 'getMessages']);

