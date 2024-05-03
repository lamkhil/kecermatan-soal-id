<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalBaris extends Model
{
    use HasFactory;

    protected $table = 'soal_baris';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];

    public function soalKolom()
    {
        return $this->hasMany(SoalKolom::class,'id_soal_baris','id');
    }
}
