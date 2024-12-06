<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MouvementController extends Controller
{
    /**
     * Transferer un courrier
     */
    public function makeMovement(Request $request){
            $fields = $request->validate([
            'ref_initial' =>'unique:mouvements|required',
            'ref_propre' =>'required',
            'courrier_id' =>'exists:courriers,c_id|required',
            'user_id' => 'exists:users,id|required',
            'type' => 'required',
            'status' => 'required',
            'propr' =>'required',
            'description' => 'required',
            'transfere' => 'required',
            'serv_id' => 'required'
        ]);
        Mouvement::create($fields);

        return [
            'message' => 'Courrier bien transfere'
        ];
    }

    /**
     * Recuperation des historiques de transfert
     */

     public function getMovements(Request $request){
        $doc_id = $request->route('doc_id');

        $history = DB::table('mouvements')
        ->where('courrier_id', '=', $doc_id)
        ->join('servs', 'servs.s_id', '=', 'mouvements.serv_id')
        ->join('users', 'users.id', '=', 'mouvements.user_id')->get();

        return $history;
     }
}
