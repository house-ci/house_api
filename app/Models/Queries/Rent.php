<?php

namespace App\Models\Queries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public function leasing()
    {
        return $this->belongsTo(Leasing::class);
    }
}
