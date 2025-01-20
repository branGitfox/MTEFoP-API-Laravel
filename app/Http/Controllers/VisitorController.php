<?php

namespace App\Http\Controllers;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function increment(){
        $date = date('y-m-d');
        $find = DB::table('visitors')->where('date', $date)->first();
 
    if($find){
      $increment = $find->nbr + 1;
      DB::update('update visitors set nbr = ?  where date = ?',[ $increment, $date]);      
    }else{
       Visitor::create(['nbr' => 1, 'date' => $date]);
        
    }

    //recuperer toutes les lignes de la table visiteurs puis boucler et additionner l e champ nbr
    $visits = DB::table('visitors')->get(['nbr']);
    $counter = 0;
    foreach($visits as $visit) {
      $counter+= $visit->nbr;

    }

    return $counter;
}

}
