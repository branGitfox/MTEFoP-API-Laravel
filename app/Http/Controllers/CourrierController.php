<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use App\Models\Dir;

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
                'transfere' => 'required',
                'cin' => 'required',
                'tel' => 'required',

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
                'status.required' => 'Le champ status est requis',
                'cin.required' => 'le champ cin requis',
                'tel.required' => 'Le champ numero est requis'
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
        $value =       count(  $docs = DB::table('courriers', 'courriers')
            ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->orderBy('courriers.created_at', 'desc')->get(['*', 'courriers.created_at', 'courriers.status']));
        if(isset($_GET['lines'])){
            $lines = $_GET['lines'];
            if($lines == 'all'){
                $value =       count(  $docs = DB::table('courriers', 'courriers')
                ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->orderBy('courriers.created_at', 'desc')->get(['*', 'courriers.created_at', 'courriers.status']));
            }else{
                $value = $lines;
            }
        }
        $docs = DB::table('courriers', 'courriers')
            ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->orderBy('courriers.created_at', 'desc')->paginate($value==0?1:$value,['*', 'courriers.created_at', 'courriers.status']);
            // get(['*', 'courriers.created_at', 'courriers.status'])
        return $docs;
    }

    /**
     * Pour recuperer la liste de tous les courriers par direction
     */

    public function fetchDocsByDirection(Request $request)
    {
        $id_dir = $request->user()->id_dir;
        $value =       count(  DB::table('courriers', 'courriers')
            ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->join('users', 'users.id', '=', 'courriers.user_id')->where('courriers.dir_id', '=', $id_dir)->get(['*', 'courriers.created_at', 'courriers.status', 'courriers.transfere']));
        if(isset($_GET['lines'])){
            $lines = $_GET['lines'];
            if($lines == 'all'){
                $value =       count(  DB::table('courriers', 'courriers')
                    ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->join('users', 'users.id', '=', 'courriers.user_id')->where('courriers.dir_id', '=', $id_dir)->get(['*', 'courriers.created_at', 'courriers.status', 'courriers.transfere']));
            }else{
                $value = $lines;
            }
        }

        $docs = DB::table('courriers', 'courriers')
        ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->join('users', 'users.id', '=', 'courriers.user_id')->where('courriers.dir_id', '=', $id_dir)->paginate($value==0?1:$value,['*', 'courriers.created_at', 'courriers.status', 'courriers.transfere']);
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
     * Pour supprimer un courrier
     */

    public function updateDoc(Request $request)
    {
        $fields = $request->validate(
            [
                'provenance' => 'required',
                'chrono' => 'required',
                'ref' => 'required',
                'dir_id' => 'required|exists:dirs,d_id',
                'motif' => 'required',
                'caracteristique' => 'required',
                'proprietaire' => 'required',
                'user_id' => ['required', 'exists:users,id'],
                'status' => 'required',
                'transfere' => 'required',
                'cin' => 'required',
                'tel' => 'required',

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
                'status.required' => 'Le champ status est requis',
                'cin.required' => 'le champ cin requis',
                'tel.required' => 'Le champ numero est requis'
            ]
        );
        DB::table('courriers')->where('c_id', '=', $request->route('id_courrier'))->update($fields);
        return [
            'message' => 'Courrier mofidie avec succes'
        ];
    }

    /**recuperation d'un courrier par id */
    public function getDoc(Request $request)
    {
        $id_doc = $request->route('id_doc');
        return DB::table('courriers')->where('c_id', '=', $id_doc)->first();
    }

    /**
     * Pour modifier  le champ livre (recu ou non recu)
     */

    public function updateLivre(Request $request)
    {
        $id_doc = $request->route('id_doc');
        $courrier = Courrier::where('c_id', $id_doc)->first(['status']);
        $new_status = '';
        if ($courrier->status == 'non reçu') {
            $new_status = 'reçu';
        } else {
            $new_status = 'non reçu';
        }

        DB::update('update courriers set status = ? where c_id = ?', [$new_status, $id_doc]);
        return [
            'message' => 'Courrier bien reçu'
        ];
    }

    /**
     * Recherche d'un document via son reference
     */
    public function findMyDoc(Request $request)
    {
        $docs = DB::table('courriers', 'courriers')
            ->where('ref', $request->ref)
            ->join('dirs', 'dirs.d_id', '=', 'courriers.dir_id')->first(['*', 'courriers.created_at', 'courriers.status']);
        return $docs;
    }

    //************************************************ STATISTIQUE *****************************************************************************************************

    /**
     * recupère le nombre total de courriers enregistrés
     */

    public function courrierCount()
    {
        return Courrier::all(['created_at']);
    }

    /**
     * nombre courrier decharger
     */
    public function courrierGotByOwnerCount(Request $request)
    {
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;
            return count(DB::table('mouvements')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('type', 'recuperation')->get(['type']));
        } else {

            return count(DB::table('mouvements')->where('type', 'recuperation')->get(['type']));
        }
    }

    /**
     * nombre courrier decharger
     */
    public function courrierNotGotByOwnerCount(Request $request)
    {
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;
            return count(DB::table('mouvements')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('type', 'transfert')->get(['type']));
        } else {

            return count(DB::table('mouvements')->where('type', 'transfert')->get(['type']));
        }
    }

    /**
     * Recupère la liste des dates existant pour le filtrage periodique des données
     */

    public function listDate()
    {
        $list = Courrier::all();
        $dates = [];

        foreach ($list as $date) {
            $exp =  substr($date->created_at, 0, 7);


            array_push($dates, $exp);
        }

        $unique =     array_unique($dates);
        $filtered = [];
        foreach ($unique as $date) {
            array_push($filtered, $date);
        }

        return $filtered;
    }

    /**
     * Liste de dossier non livre
     */

    public function courrierNotLivred()
    {
        return DB::table('courriers')->where('status', 'non reçu')->get(['created_at']);
    }

    /**
     * Liste de dossier non livre par periode
     */

    public function courrierNotLivredByPeriod(Request $request)
    {
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;

            return DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('status', 'non reçu')->get(['created_at']);
        } else {

            return DB::table('courriers')->where('status', 'non reçu')->get(['created_at']);
        }
    }

    /**
     * Liste de dossier non livre
     */

    public function courrierLivred()
    {
        return DB::table('courriers')->where('status', 'reçu')->get(['created_at']);
    }

    /**
     * Liste de dossier non livre
     */

    public function courrierLivredByPeriod(Request $request)
    {
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;

            return DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('status', 'reçu')->get(['created_at']);
        } else {
            return DB::table('courriers')->where('status', 'reçu')->get(['created_at']);
        }
    }

    /**
     * Recupèration du nombre de courriers par direction pour le dashboard  Admin
     */

    public function numberOfDocByDirectionAdmin(Request $request)
    {
        $data = [];
        $dirs = Dir::all();
        $got = 0;
        $notGot = 0;
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;

            //calcul de nombre de courriers pour chaque direction
            foreach ($dirs as $dir) {
                $list_doc = DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('dir_id', $dir['d_id'])->get(['created_at']);
                $servs = DB::table('servs')->where('dir_id', $dir['d_id'])->get();
                foreach ($servs as $serv) {
                    $getGot = Db::table('mouvements')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('serv_id', $serv->s_id)->where('type', 'recuperation')->get(['created_at']);
                    $got += count($getGot);
                    // $getNotGot = Db::table('mouvements')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('serv_id', $serv->s_id)->where('type', 'transfert')->get(['created_at']);
                    $notGot = count($list_doc) - $got;
                }



                array_push($data, [$dir['nom_dir'], $list_doc, $got, $notGot]);
            }
        } else {


            //calcul de nombre de courriers pour chaque direction
            foreach ($dirs as $dir) {
                $list_doc = DB::table('courriers')->where('dir_id', $dir['d_id'])->get(['created_at']);
                $servs = DB::table('servs')->where('dir_id', $dir['d_id'])->get();

                foreach ($servs as $serv) {
                    $getGot = Db::table('mouvements')->where('serv_id', $serv->s_id)->where('type', 'recuperation')->get(['created_at']);
                    // $getNotGot = Db::table('mouvements')->where('serv_id', $serv->s_id)->where('type', 'transfert')->get(['created_at']);
                    $notGot = count($list_doc) - $got;
                    $got += count($getGot);
                }
                array_push($data, [$dir['nom_dir'], $list_doc, $got, $notGot]);
            }
        }





        return $data;
    }


    /**
     * Recuperation de nombre de courriers par direction Admin
     */

    public function numberOfDocByDirection(Request $request)
    {
        $data = [];
        $dirs = Dir::all();
        $got = 0;


        //calcul de nombre de courriers pour chaque direction
        foreach ($dirs as $dir) {
            $list_doc = DB::table('courriers')->where('dir_id', $dir['d_id'])->get(['created_at']);
            $notGot = DB::table('courriers')->where('dir_id', $dir['d_id'])->where('status', 'non reçu')->get(['created_at']);
            $got = DB::table('courriers')->where('dir_id', $dir['d_id'])->where('status', 'reçu')->get(['created_at']);

            array_push($data, [$dir['nom_dir'], $list_doc, $got, $notGot]);
        }

        //recuperation de la liste de direction





        return $data;
    }


    /**
     * Recuperation de nombre de courriers par Service
     */

    public function numberOfDocByService(Request $request)
    {
        //recuperation de la liste de direction
        $servs = DB::table('servs')->where('dir_id', $request->user()->id_dir)->get();
        $data = [];
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;


            //calcul de nombre de courriers pour chaque direction
            foreach ($servs as $serv) {
                $list_doc = DB::table('mouvements', 'mouvements')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('mouvements.serv_id', $serv->s_id)->get(['*']);
                array_push($data, [$serv->nom_serv, $list_doc]);
            }
        } else {
            //calcul de nombre de courriers pour chaque direction
            foreach ($servs as $serv) {
                $list_doc = DB::table('mouvements', 'mouvements')->where('mouvements.serv_id', $serv->s_id)->get(['*']);
                array_push($data, [$serv->nom_serv, $list_doc]);
            }
        }

        return $data;
    }

    /**
     * Recuperation de nombre de courriers par Service
     */

    public function numberOfDocByServiceNoFilter(Request $request)
    {
        //recuperation de la liste de direction
        $servs = DB::table('servs')->get();
        $data = [];
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;
            //calcul de nombre de courriers pour chaque direction
            foreach ($servs as $serv) {
                $list_doc = DB::table('mouvements', 'mouvements')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('mouvements.serv_id', $serv->s_id)->get(['*']);
                array_push($data, [$serv->nom_serv, $list_doc]);
            }
        } else {
            //calcul de nombre de courriers pour chaque direction
            foreach ($servs as $serv) {
                $list_doc = DB::table('mouvements', 'mouvements')->where('mouvements.serv_id', $serv->s_id)->get(['*']);
                array_push($data, [$serv->nom_serv, $list_doc]);
            }
        }


        return $data;
    }

    /**
     * Recuperation de nombre de courriers par direction par periode
     */

    public function numberOfDocByDirectionByPeriod(Request $request)
    {
        //recuperation de la liste de direction
        $dirs = Dir::all();
        $data = [];
        $got = 0;
        $notGot = 0;
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;

            //calcul de nombre de courriers pour chaque direction
            foreach ($dirs as $dir) {

                $list_doc = DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('dir_id', $dir['d_id'])->get(['created_at']);
                $notGot = DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('dir_id', $dir['d_id'])->where('status', '=', 'non reçu')->get(['created_at']);
                $got = DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('dir_id', $dir['d_id'])->where('status', '=', 'reçu')->get(['created_at']);
                array_push($data, [$dir['nom_dir'], $list_doc, $got, $notGot]);
            }
        } else {
            //calcul de nombre de courriers pour chaque direction
            foreach ($dirs as $dir) {

                $list_doc = DB::table('courriers')->where('dir_id', $dir['d_id'])->get(['created_at']);
                $notGot = DB::table('courriers')->where('dir_id', $dir['d_id'])->where('dir_id', $dir['d_id'])->where('status','=', 'non reçu')->get(['created_at']);
                $got = DB::table('courriers')->where('dir_id', $dir['d_id'])->where('status', '=', 'reçu')->get(['created_at']);
                array_push($data, [$dir['nom_dir'], $list_doc, $got, $notGot]);
            }
        }


        return $data;
    }

    /**
     * Gere le filtrage de la date periodique
     */

    public function filterPeriodDate(Request $request)
    {
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;
            return  DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->get(['created_at']);
        } else {
            return  DB::table('courriers')->get(['created_at']);
        }
    }

    public function getAdressIp()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * nombre de courriers dans une direction
     */
    public function countDocByDirection(Request $request)
    {
        if (!empty($request->start) && !empty($request->end)) {
            $start = $request->start;
            $end = $request->end;
            return  count(DB::table('courriers')->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->where('dir_id', $request->user()->id_dir)->get(['created_at']));
        } else {
            return  count(DB::table('courriers')->where('dir_id', $request->user()->id_dir)->get(['created_at']));
        }
    }
}
