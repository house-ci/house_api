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
    protected $fillable=['started_on','ended_on','amount','currency','payment_deadline_day','agreement_url','is_active','asset_id','tenant_id','type','next_leasing_period'];

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
    const POSTPAY='POSTPAY';
    const PREPAY='PREPAY';
}
