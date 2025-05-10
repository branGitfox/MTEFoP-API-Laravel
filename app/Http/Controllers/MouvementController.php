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
            'ref_initial' =>'required',
            'ref_propre' =>'required',
            'courrier_id' =>'exists:courriers,c_id|required',
            'user_id' => 'exists:users,id|required',
            'type' => 'required',
            'status' => 'required',
            'propr' =>'',
            'description' => 'required',
            'transfere' => 'required',
            'serv_id' => 'required',
            'current_trans_id' =>'required',
            'id_dg' => 'required',
            'current_trans_id_dir' => 'required'

        ]);
        Mouvement::create($fields);
        DB::update('update courriers set transfere = ? where c_id = ?', ['oui',$request->courrier_id]);

        return [
            'message' => 'Courrier bien transfere'
        ];
    }

        /**
     * Transferer un courrier
     */
    public function makeMovementMove(Request $request){
        $fields = $request->validate([
        'ref_initial' =>'required',
        'ref_propre' =>'required',
        'courrier_id' =>'exists:courriers,c_id|required',
        'user_id' => 'exists:users,id|required',
        'type' => 'required',
        'status' => 'required',
        'propr' =>'',
        'description' => 'required',
        'transfere' => 'required',
        'serv_id' => 'required',
        'current_trans_id' =>'required',
        'id_dg' => 'required',
        'current_trans_id_dir' => 'required'

    ]);

    Mouvement::create($fields);
    DB::update('update mouvements set transfere = ? where m_id = ?', ['oui',$request->route('m_id')]);

    return [
        'message' => 'Courrier bien transfere'
    ];
}

    /**
     * Recuperation des historiques de transfert
     */

     public function getMovements(Request $request){
        $doc_id = $request->route('doc_id');

        $history = DB::table('mouvements', 'mouvements')
        ->where('courrier_id', '=', $doc_id)

        ->leftJoin('servs', 'servs.s_id', '=', 'mouvements.serv_id')
        ->leftJoin('dirs', 'dirs.d_id', '=', 'mouvements.id_dg')
        ->join('users', 'users.id', '=', 'mouvements.user_id')->get(['*', 'mouvements.created_at']);
        return $history;
     }

     /**
      * Recupere la liste de courrier transfere dans une direction
      */

      public function getListTransDocByDirection(Request $request)
      {
        $id_dir = $request->user()->id_dir;
        $trans =  DB::table('mouvements', 'mouvements')
        ->join('servs', 'servs.s_id', '=', 'mouvements.serv_id')
        ->join('courriers', 'courriers.c_id', '=', 'mouvements.courrier_id')
        ->join('users', 'users.id', '=', 'mouvements.user_id')
        ->where('servs.dir_id',$id_dir)->get(['*', 'mouvements.created_at', 'mouvements.status', 'mouvements.ref_initial', 'mouvements.ref_propre', 'mouvements.serv_id', 'mouvements.propr', 'mouvements.transfere']);
        return $trans;
      }

      /**
       *  Recupere la liste de courrier dans un service
       *
       */


      public function getListTransDocByService(Request $request)
      {
        $id_serv = $request->user()->id_serv;
        $trans =  DB::table('mouvements', 'mouvements')
        ->join('servs', 'servs.s_id', '=', 'mouvements.serv_id')
        ->join('courriers', 'courriers.c_id', '=', 'mouvements.courrier_id')
        ->join('users', 'users.id', '=', 'mouvements.user_id')
        ->where('mouvements.serv_id',$id_serv)->get(['*', 'mouvements.created_at', 'mouvements.status', 'mouvements.ref_initial', 'mouvements.ref_propre', 'mouvements.serv_id', 'mouvements.propr', 'mouvements.transfere']);
        return $trans;
      }



        /**
       *  Recupère la liste de courrier transferé dans un service
       *
       */


       public function getListTransferedDocByService(Request $request)
       {
         $id_serv = $request->user()->id_serv;
         $trans =  DB::table('mouvements', 'mouvements')
         ->leftjoin('servs', 'servs.s_id', '=', 'mouvements.serv_id')
         ->leftjoin('dirs', 'dirs.d_id', '=', 'mouvements.id_dg')
         ->join('courriers', 'courriers.c_id', '=', 'mouvements.courrier_id')
         ->join('users', 'users.id', '=', 'mouvements.user_id')
         ->where('mouvements.current_trans_id', $id_serv)->get(['*', 'mouvements.created_at', 'mouvements.status', 'mouvements.ref_initial', 'mouvements.ref_propre', 'mouvements.serv_id', 'mouvements.propr', 'mouvements.transfere']);
         return $trans;
       }    

                  /**
       *  Recupere la liste de courrier transfere vers un service
       *
       */


       public function getListTransferedDocToService(Request $request)
       {
         $id_dir = $request->user()->id_dir;
         $trans =  DB::table('mouvements', 'mouvements')     ->where('mouvements.current_trans_id_dir', $id_dir)
         ->leftjoin('servs', 'servs.s_id', '=', 'mouvements.serv_id')
         ->leftjoin('dirs', 'dirs.d_id', '=', 'mouvements.id_dg')
         ->join('courriers', 'courriers.c_id', '=', 'mouvements.courrier_id')
         ->join('users', 'users.id', '=', 'mouvements.user_id')

        ->get(['*', 'mouvements.created_at', 'mouvements.status', 'mouvements.ref_initial', 'mouvements.ref_propre', 'mouvements.serv_id', 'mouvements.propr', 'mouvements.transfere']);
         return $trans;
       }


       /**recuperation de la liste de courriers transferer d'un service a l'sp */
       public function moveServiceToSp(Request $request) {
        $id_dir = $request->user()->id_dir;
        $courriers= DB::table('mouvements', 'mouvements')
        ->leftjoin('servs', 'servs.s_id', '=', 'mouvements.current_trans_id')
        ->leftjoin('dirs', 'dirs.d_id', '=', 'mouvements.id_dg')
        ->join('courriers', 'courriers.c_id', '=', 'mouvements.courrier_id')
        ->join('users', 'users.id', '=', 'mouvements.user_id')
        ->where('mouvements.id_dg', $id_dir)->get(['*', 'mouvements.created_at', 'mouvements.transfere', 'mouvements.status']);
        return $courriers;
       }

    /**
     * Pour modifier  le champ livre (recu ou non recu)
     */

    public function updateLivre(Request $request)
    {
        $id_doc = $request->route('id_doc');
        $courrier = Mouvement::where('m_id', $id_doc)->first(['status']);
        $new_status = '';
        if ($courrier->status == 'non reçu')
        {
            $new_status = 'reçu';
        }else{
            $new_status = 'non reçu';
        }

        DB::update('update mouvements set status = ? where m_id = ?', [$new_status, $id_doc]);
        return [
            'message' => 'Courrier bien reçu'
        ];
    }

      /**
     * Pour recuperer un seul courrier par service
     */

     public function fetchDocByOneByService(Request $request)
     {
         $id_doc = $request->route('id_doc');
         $docs = DB::table('mouvements', 'mouvements')
         ->join('courriers', 'courriers.c_id', '=', 'mouvements.courrier_id')
             ->join('servs', 'servs.s_id', '=', 'mouvements.serv_id')->join('users', 'users.id', '=', 'mouvements.user_id')->where('mouvements.m_id', '=', $id_doc)->first(['*', 'mouvements.created_at', 'mouvements.status']);
         return $docs;
     }

           /**
     * Pour recuperer un seul courrier par service *service vers sp
     */

     public function fetchDocByOneByServiceToSp(Request $request)
     {
         $id_doc = $request->route('id_doc');
         $docs = DB::table('mouvements', 'mouvements')
         ->join('courriers', 'courriers.c_id', '=', 'mouvements.courrier_id')
             ->join('servs', 'servs.s_id', '=', 'mouvements.current_trans_id')->join('users', 'users.id', '=', 'mouvements.user_id')->where('mouvements.m_id', '=', $id_doc)->first(['*', 'mouvements.created_at', 'mouvements.status']);
         return $docs;
     }
}
