<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courrier extends Model
{
    /** @use HasFactory<\Database\Factories\CourrierFactory> */
    use HasFactory;
    protected $fillable = [
        'provenance',
        'chrono',
        'ref',
        'dir_id',
        'motif',
        'caracteristique',
        'propr',
        'user_id',
        'status',
        'transfere'
    ];

    public function courriers () {
        return $this->belongsTo(User::class);
    }

    public function courrierss () {
        return $this->belongsTo(Dir::class);
    }
}
