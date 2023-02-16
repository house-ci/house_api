<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leasing extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

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
