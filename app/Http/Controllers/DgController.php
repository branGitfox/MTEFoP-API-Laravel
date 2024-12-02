<?php

namespace App\Http\Controllers;


use App\Models\Dg;
use Illuminate\Http\Request;

class DgController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Dg::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $field = $request->validate([
            'nom_dg' => ['required', 'unique:dgs']
        ], 
        [
            'nom_dg.required' => 'Le Champ Nom DG est requis',
            'nom_db.unique' => 'La DG existe deja'
        ]
    );
        
        Dg::create($field);

        return [
            'message' => 'Direction Generale creer avec success'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Dg $dg)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dg $dg)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dg $dg)
    {
        //
    }
}
