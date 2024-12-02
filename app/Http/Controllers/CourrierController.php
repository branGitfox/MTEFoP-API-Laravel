<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use Illuminate\Http\Request;

class CourrierController extends Controller
{
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
}
