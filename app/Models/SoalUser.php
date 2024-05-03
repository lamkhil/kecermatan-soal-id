<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalUser extends Model
{
    use HasFactory;

    protected $table = 'soal_users';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class,'id_soal','id');
    }
}
