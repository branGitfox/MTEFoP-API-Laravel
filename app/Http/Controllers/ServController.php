<?php

namespace App\Http\Controllers;


use App\Models\Serv;
use Illuminate\Http\Request;

class ServController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Serv::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields =$request->validate([
            'nom_serv' => 'required|unique:servs',
            'dir_id' => ['required', 'exists:dirs,d_id'],
            'porte_serv' => 'required'
        ], [
            'nom_serv.required' => 'Le champ Nom service est requis',
            'nom_serv.unique' => 'Le service existe deja',
            'dir_id.required' => 'Le champ DIR  est requis',
            'dir_id.exists' => 'La DIR n\'existe pas',
            'porte_serv' => 'Le champ porte es requis'
        ]);

        Serv::create($fields);
        return [
            'message' => 'service creee avec succes'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Serv $serv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Serv $serv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Serv $serv)
    {
        //
    }
}
