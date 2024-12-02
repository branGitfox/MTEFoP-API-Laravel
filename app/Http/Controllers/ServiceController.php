<?php

namespace App\Http\Controllers;

use App\Models\Serv;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //recupere la liste des services par rapport - la direction

    public function getListOfServByDirection(Request $request) {
      $id_dir = $request->route('id_dir');

      $services= Serv::where('dir_id', $id_dir)->get();
      return $services;
    }


}
