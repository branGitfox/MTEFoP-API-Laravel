<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Enregistre le mesage envoye par les visiteurs dans la base de donnees
     */
    public function sendMessage(Request $request){
        $fields = $request->validate([
            'email' => 'required|email',
            'objet' => 'required|min:5',
            'message' => 'required|min:15'
        ]);

        Support::create($fields);
        return [
            'message' => 'message bien envoyee'
        ];
    }

    /**
     * Recuperation de la liste de messages filtrer decroissant
     */
    public function getMessages(){
        return DB::table('supports')->orderByDesc('su_id')->get();
    }

  
}
