<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingWeb extends Model
{
    use HasFactory;

    protected $table = 'setting_web';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];

    
}
