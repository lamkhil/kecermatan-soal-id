<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBundleList extends Model
{
    use HasFactory;

    protected $table = 'package_bundle_list';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];

    public function soal()
    {
        return $this->hasMany(Soal::class,'id','id_soal');
    }

    public function bundle()
    {
        return $this->hasMany(PackageBundle::class,'id','id_package_bundle');
    }
}
