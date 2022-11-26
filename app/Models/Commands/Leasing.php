<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leasing extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
