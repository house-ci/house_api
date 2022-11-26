<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public function realEstates()
    {
        return $this->hasMany(RealEstate::class);
    }
}
