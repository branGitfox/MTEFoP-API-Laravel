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
        $fields = $request->validate([
            'provenance' => 'required',
            'chrono' => 'required|unique:courriers',
            'ref' => 'required',
            'dir_id' => 'required|exists:dirs,id',
            'motif' => 'required',
            'caracteristique' => 'required',
            'propr' => 'required',
            'user_id' => ['required', 'exists:users,id'],
            'status' => 'required'
        ],
        [
            'provenance.required' => 'Le champ provenance est requis',
            'chrono.required' => 'Le champ chrono est requis',
            'chrono.unique' => 'Le chrono existe deja',
            'ref.required' => 'Le champ reference est requis',
            'dir_id.required' => 'Le champ Direction est requis',
            'dir_id.exists' => 'Cette Direction n\'existe pas',
            'motif.required' => 'Le champ motif est requis',
            'caracteristique.required' => 'Le champ caracteristique est requis' ,
            'propr.required' => 'Le champ proprietaire est requis',
            'user_id.required' => 'Vous devez vous connecter avant de pouvoir poursuivre cette action' ,
            'user_id.exists' => 'Vous devez vous connecter avant de pouvoir poursuivre cette action' ,
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
        $docs = DB::table('courriers')
            ->join('dirs', 'dirs.id', '=', 'courriers.dir_id')->get();
        return $docs;
    }
}
