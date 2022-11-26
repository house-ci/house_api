<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public function leasings()
    {
        return $this->hasMany(Leasing::class);
    }

    public function realEstate(){
        return $this->belongsTo(RealEstate::class);
    }

    public function owner(){
        return $this->realEstate()->owner();
    }
}
