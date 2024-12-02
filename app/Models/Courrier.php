<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courrier extends Model
{
    /** @use HasFactory<\Database\Factories\CourrierFactory> */
    use HasFactory;
    protected $fillable = [
        'prov',
        'chrono',
        'ref',
        'id_dir',
        'motif',
        'caracteristique',
        'propr',
        'id_user'
    ];
}
