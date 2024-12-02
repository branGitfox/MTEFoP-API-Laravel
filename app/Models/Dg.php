<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dg extends Model
{
    /** @use HasFactory<\Database\Factories\DgFactory> */
    use HasFactory;

    protected $fillable = [
        'nom_dg'
    ];

    public function db () {
        return $this->hasMany(Dir::class);
    }

    
}
