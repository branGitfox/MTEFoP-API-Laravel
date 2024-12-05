<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use Illuminate\Http\Request;

class MouvementController extends Controller
{
    /**
     * Transferer un courrier
     */
    public function makeMovement(Request $request){
            $fields = $request->validate([
            'ref_initial' =>'unique:mouvements|required',
            'ref_propre' =>'required',
            'courrier_id' =>'exists:courriers,id|required',
            'user_id' => 'exists:users,id|required',
            'type' => 'required',
            'status' => 'required',
            'propr' =>'required',
            'description' => 'required'
        ]);
        Mouvement::create($fields);

        return [
            'message' => 'Courrier bien transfere'
        ];
    }
}
