<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BabSoal extends Model
{
    use HasFactory;
    
    protected $table = 'bab_soal';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];
}
