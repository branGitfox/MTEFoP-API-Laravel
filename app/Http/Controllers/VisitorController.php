<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function increment(){
        $find = DB::table('visitors')->where('v_id', 1)->first();
        $date = date('y-m-d');
        if($find){
            $increment = $find->nbr + 1;

          DB::update('update visitors set nbr = ?, date = ? where v_id = ?',[ $increment, $date, 1]);
          $new =DB::table('visitors')->where('v_id', 1)->first();
         return $new->nbr;
        }

            Visitor::create(['nbr' => 0]);
          $new =DB::table('visitors')->where('v_id', 1)->first();
            return $new->nbr;
        

        
    }
}
