<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetail extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $keyType = 'string';

    const RENTAL = 'RENTAL';
    const PENALITY = 'PENALITY';
    const ADDITIONAL_FEE = 'ADDITIONAL_FEE';
    const PAYMENT_FEE = 'PAYMENT_FEE';

    protected $fillable=['type','amount','rent_id','payment_id'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }
}
