<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'id_users','id');
    }

    public function package()
    {
        return $this->belongsTo(PackageBundle::class,'id_package','id');
    }

}
