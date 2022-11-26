<?php

namespace App\Models\Commands;

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
}
