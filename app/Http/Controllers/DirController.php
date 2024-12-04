<?php

namespace App\Http\Controllers;


use App\Models\Dir;
use Illuminate\Http\Request;

class DirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Dir::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $fields = $request->validate([
            'nom_dir'=> 'required|unique:dirs',
            'dg_id' => ['required', 'exists:dgs,id'],
            'porte_dir' => 'required'
       ],

       [
        'nom_dir' => 'Le champ Nom DIR est requis',
        'dg_id.required' => 'Le champ DG est requis',
        'dg_id.exists' => 'Le DG n\'existe pas',
        'nom_dir.unique' => 'La DIR existe deja',
        'porte_dir.required' => 'Le champ porte est requis'
       ]
        
    );

        Dir::create($fields);

        return [
            'message' => 'direction creee avec succes'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Dir $dir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dir $dir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dir $dir)
    {
        //
    }
}
