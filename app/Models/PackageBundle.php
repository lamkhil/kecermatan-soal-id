<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBundle extends Model
{
    use HasFactory;

    protected $table = 'package_bundle';

    protected $guarded = [];

    protected $hidden = 
    [
        'id',
    ];

    public function listSoal()
    {
        return $this->hasMany(PackageBundleList::class,'id_package_bundle','id');
    }
}
