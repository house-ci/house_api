<?php

namespace App\Models\Queries;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string';

    protected $fillable = [
        "full_name",
        "email",
        "phone_number",
        "identifier",
    ];

    public function realEstates()
    {
        return $this->hasMany(RealEstate::class);
    }

    public function assets()
    {
        return $this->hasManyThrough(Asset::class, RealEstate::class);
    }

    public function leasings()
    {
        return $this->hasManyThrough(Leasing::class, Asset::class);
    }

    public function tenants()
    {
        return $this->hasManyThrough(Tenant::class, Leasing::class);
    }

    public function rents()
    {
        return $this->hasManyThrough(Rent::class, Leasing::class);
    }
}
