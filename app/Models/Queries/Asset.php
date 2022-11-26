<?php

namespace App\Models\Queries;

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
}
