<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $keyType = 'string';

    protected $fillable = [
        'number_of_rooms', 'description', 'door_number', 'is_available', 'amount', 'currency', 'payment_deadline_day',
        'extras', 'real_estate_id'
    ];

    public function leasings()
    {
        return $this->hasMany(Leasing::class);
    }

    public function realEstate()
    {
        return $this->belongsTo(RealEstate::class);
    }

    public function owner()
    {
        return $this->realEstate()->owner();
    }
}
