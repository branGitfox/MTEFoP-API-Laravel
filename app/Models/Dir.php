<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dir extends Model
{
    /** @use HasFactory<\Database\Factories\DirFactory> */
    use HasFactory;

    protected $fillable = [
        'nom_dir',
        'dg_id'
    ];

    public function dirs() {
        return $this->belongsTo(Dg::class);
    }

    public function dir() {
        return $this->hasMany(Serv::class);
    }

    public function direction() {
        return $this->hasMany(Courrier::class);
    }
}
