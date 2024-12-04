<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serv extends Model
{
    /** @use HasFactory<\Database\Factories\ServFactory> */
    use HasFactory;

    protected $fillable = [
        'nom_serv',
        'dir_id',
        'porte_serv'
    ];

    public function serv() {
        return $this->belongsTo(Dir::class);
    }

    
}
