<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealEstate extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';
    protected $fillable = ["name", "number_of_floor", "city_id", "property_type_id", "lot", "block", "description", "owner_id"];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
