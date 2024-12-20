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
        if(!empty($find)){
            $increment = $find->nbr + 1;

          DB::update('update visitors set nbr = ? where v_id = ?',[ $increment, 1]);
          $new =DB::table('visitors')->where('v_id', 1)->first();
         return $new->nbr;
        }else{

            return  Visitor::create(['nbr' => 0]);
        }

        
    }
}
