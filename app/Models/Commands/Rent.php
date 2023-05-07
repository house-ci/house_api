<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rent extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    const PENDING = 'PENDING';
    const PAID = 'PAID';
    protected $keyType = 'string';
    protected $fillable = ["leasing_id", "amount", "currency", "label", "month", "year", "status","penality", "amount_paid","deadline","paid_at"];


    public function leasing()
    {
        return $this->belongsTo(Leasing::class);
    }
    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
