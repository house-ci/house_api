<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    public function realEstates()
    {
        return $this->hasMany(RealEstate::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    public function parent(){
        return $this->belongsTo(City::class, 'parent_id', 'id');
    }

    public function children(){
        return $this->hasMany(City::class, 'parent_id', 'id');
    }
}
