<?php

namespace App\Models\Commands;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $keyType = 'string';

        protected $fillable = [
            "pay_id",
            "paid_on",
            "rent_id",
            "paid_at",
            "paid_by",
            "amount",
        ];

        public function details(){
           return $this->hasMany(PaymentDetail::class);
        }
}
