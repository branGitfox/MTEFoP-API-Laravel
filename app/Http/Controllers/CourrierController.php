<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourrierController extends Controller
{
    public function addDoc (Request $request) {
        $request->validate([
            'prov' => 'required',
            'chrono' => 'required|unique:courriers',
            'ref' => 'required',
            'id_dir' => 'required|exists:dirs,id',
            'motif' => 'required',
            'caracteristique' => 'required',
            'propr' => 'required',
            'id_user' => 'required|exists:users',
            'status' => 'required'
        ]);
    }
}
