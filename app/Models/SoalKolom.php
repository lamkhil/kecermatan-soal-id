<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalKolom extends Model
{
    use HasFactory;

    
    protected $table = 'soal_kolom';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];

    
}
