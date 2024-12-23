<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;

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
}
