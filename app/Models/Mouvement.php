<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    protected $fillable = [
        'ref_initial',
        'ref_propre',
        'courrier_id',
        'user_id',
        'type',
        'status',
        'propr',
        'description',
        'transfere',
        'serv_id',
        'current_trans_id'
    ];
}
