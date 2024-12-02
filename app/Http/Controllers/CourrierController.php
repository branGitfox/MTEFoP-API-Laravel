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
    public function addDoc (Request $request) {
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
        ]);

        Courrier::create($fields);

        return [
            'message' => 'Courrier creer avec succes'
        ];
    }

    /**
     * Pour recuperer la liste de tous les courriers
     */

     public function fetchDocs (Request $request) {
        $docs = DB::table('courriers')
                ->join('dirs', 'dirs.id', '=', 'courriers.dir_id')->get();
                return $docs;

     }          
                
}
