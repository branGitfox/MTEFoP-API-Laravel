<?php

namespace App\Http\Controllers;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function increment(){
        $date = date('y-m-d');
        $find = DB::table('visitors')->where('date', $date)->first();
 
        //chercher dans la table visiteur si une ligne avec la date courante existe deja
    if($find){
      //si
      $increment = $find->nbr + 1;
      DB::update('update visitors set nbr = ?  where date = ?',[ $increment, $date]);      
    }else{
      //sinon
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


  //affichage periodique du nombre de visiteurs par periode

  public function showNumberOfVisitByPeriod(Request $request){
    if(!empty($request->start) && !empty($request->end)){
      $start = $request->start;
      $end = $request->end;

        $view_list = DB::table('visitors')->whereDate('date', '>=', $start)->whereDate('date', '<=', $end)->get(['nbr']);
        $counter = 0;
        foreach($view_list as $view){
          $counter+= $view->nbr;
        }

        return $counter;
    }else {
        $view_list = Visitor::all(['nbr']);
        $counter = 0;
        
        foreach($view_list as $view){
          $counter+= $view->nbr;
        }

        return $counter;
    }
  }

    /**
     * recuperation la liste de nombre de vue preparer pour le 
     */
    public function getViewPeriodForChartLine() {
      $views =  Visitor::all(['nbr']);
      $datas = [];
      foreach($views as $view){
          array_push($datas, ['nbr' => $view->nbr]);
      }

      return $datas;
    }



}
