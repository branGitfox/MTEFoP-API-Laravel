<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    public function sendMessage(Request $request){
        $fields = $request->validate([
            'email' => 'required|email',
            'objet' => 'required|min:5',
            'message' => 'required|min:15'
        ]);

        Support::create($fields);
        return [
            'message' => 'message bien envoyee'
        ];
    }

    public function getMessages(){
        return DB::table('supports')->orderByDesc('su_id')->get();
    }
}
