<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];

    public function babSoal()
    {
        return $this->hasOne(BabSoal::class,'id','id_bab_soal');
    }

    public function soalBaris()
    {
        return $this->hasMany(SoalBaris::class,'id_soal','id');
    }

}
