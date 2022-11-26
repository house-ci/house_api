<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
