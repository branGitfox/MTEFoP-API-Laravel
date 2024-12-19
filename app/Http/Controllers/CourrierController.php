<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourrierController extends Controller
{
    /**
     * Pour l'enregistrement d'un courrier
     */
    public function addDoc(Request $request)
    {
        $fields = $request->validate(
            [
                'provenance' => 'required',
                'chrono' => 'required|unique:courriers',
                'ref' => 'required',
                'dir_id' => 'required|exists:dirs,d_id',
                'motif' => 'required',
                'caracteristique' => 'required',
                'proprietaire' => 'required',
                'user_id' => ['required', 'exists:users,id'],
                'status' => 'required',
                'transfere' => 'required'
            ],
            [
                'provenance.required' => 'Le champ provenance est requis',
                'chrono.required' => 'Le champ chrono est requis',
                'chrono.unique' => 'Le chrono existe deja',
                'ref.required' => 'Le champ reference est requis',
                'dir_id.required' => 'Le champ Direction est requis',
                'dir_id.exists' => 'Cette Direction n\'existe pas',
                'motif.required' => 'Le champ motif est requis',
                'caracteristique.required' => 'Le champ caracteristique est requis',
                'propr.required' => 'Le champ proprietaire est requis',
                'user_id.required' => 'Vous devez vous connecter avant de pouvoir poursuivre cette action',
                'user_id.exists' => 'Vous devez vous connecter avant de pouvoir poursuivre cette action',
                'status.required' => 'Le champ status est requis'
            ]
        );

        Courrier::create($fields);

        return [
            'message' => 'Courrier creer avec succes'
        ];
    }

    /**
     * Pour recuperer la liste de tous les courriers
     */

    public function fetchDocs(Request $request)
    {
        $docs = DB::table('courriers', 'courriers')
            ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->get(['*', 'courriers.created_at', 'courriers.status']);
        return $docs;
    }

    /**
     * Pour recuperer la liste de tous les courriers par direction
     */

    public function fetchDocsByDirection(Request $request)
    {
        $id_dir = $request->user()->id_dir;
        $docs = DB::table('courriers', 'courriers')
        ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->join('users', 'users.id', '=', 'courriers.user_id')->where('courriers.dir_id', '=', $id_dir)->get(['*', 'courriers.created_at', 'courriers.status']);
        return $docs;
    }


    /**
     * Pour recuperer un seul courrier par direction
     */

     public function fetchDocByOneByDirection(Request $request)
     {
         $id_doc = $request->route('id_doc');
         $docs = DB::table('courriers', 'courriers')
             ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->join('users', 'users.id', '=', 'courriers.user_id')->where('courriers.c_id', '=', $id_doc)->first(['*', 'courriers.created_at', 'courriers.status']);
         return $docs;
     }
    /**
     * Pour supprimer un courrier
     */

    public function deleteDoc(Request $request)
    {
        DB::table('courriers')->where('c_id', '=', $request->route('id_courrier'))->delete();
        return [
            'message' => 'Courrier supprime avec succes'
        ];
    }

    /**
     * Pour modifier  le champ livre (recu ou non recu)
     */

    public function updateLivre(Request $request)
    {
        $id_doc = $request->route('id_doc');
        $courrier = Courrier::where('c_id', $id_doc)->first(['status']);
        $new_status = '';
        if ($courrier->status == 'non reçu') 
        {
            $new_status = 'reçu';
        }else{
            $new_status = 'non reçu';
        }

       $updated =  DB::update('update courriers set status = ? where c_id = ?', [$new_status, $id_doc]);
        return [
            'message' => 'Courrier bien reçu'
        ];
    }

    /**
     * Recherche d'un document via son reference
     */
    public function findMyDoc(Request $request){
        $docs = DB::table('courriers', 'courriers')
        ->where('ref', $request->ref)
        ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->first(['*', 'courriers.created_at', 'courriers.status']);
        return $docs;
    }

    //************************************************ STATISTIQUE *****************************************************************************************************

    /**
     * Liste de dossier
     */

    public function courrierCount() {
      return Courrier::all(['created_at']);
    }

    /**
     * Liste des dates
     */

     public function listDate() {
        $list = Courrier::all(['created_at']);
        $dates = [];

        foreach($list as $date){
          $exp =  substr($date['created_at'], 0, 7);
             array_push($dates, $exp);
        }

       return array_unique($dates);
    }

    /**
     * Liste de dossier non livre
     */

    public function courrierNotLivred() {
        return DB::table('courriers')->where('status', 'non reçu')->get(['created_at']);
    }

     /**
     * Liste de dossier non livre
     */

     public function courrierLivred() {
        return DB::table('courriers')->where('status', 'reçu')->get(['created_at']);
    }

    /**
     * donnee de graphique
     */

     public function graph() {
        return [

        ];
    }
}
