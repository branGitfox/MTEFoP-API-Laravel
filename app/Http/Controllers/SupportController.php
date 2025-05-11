<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Enregistre le message envoyé par les visiteurs dans la base de données
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
/*
 *supprimer un message
*
 */

public function deleteMessages(Request $request){
    $id= $request->route('id');
    DB::delete('delete from supports where su_id = ?', [$id]);
    return [
        'message' => "Message bien supprime"
    ];
}

public function deleteAllMessages(){
    DB::table('supports')->delete();
    return [
        'message' => 'Tous les messages ont ete bien supprime'
    ];
}


/*
*Effacer tout les messages
*

 */

  public function deleteAllMessage() {
  Support::delete('delete from suppors');

          return [
          'message' => "Tout les messsages ont ete supprimer"
          ];
  }



}

