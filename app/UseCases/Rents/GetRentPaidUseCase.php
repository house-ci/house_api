<?php

namespace App\UseCases\Rents;

use App\Models\Commands\Rent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GetRentPaidUseCase
{
    public static function allOwnerEstatRent($ownerId, $date = null)
    {
        $currentDate = Carbon::now();
        $dateNow = Carbon::parse($currentDate);
        return DB::table('real_estates')
            ->join('assets', 'assets.real_estate_id', '=', 'real_estates.id')
            ->join('leasings', 'leasings.asset_id', '=', 'assets.id')
            ->join('rents', 'rents.leasing_id', '=', 'leasings.id')
            ->where('real_estates.owner_id', '=', $ownerId)
            ->where('rents.year', '=', $date == null ? date('Y') : $date)
            ->where('rents.month', '=', $date == null ? date('m') : $date)
            ->orWhereDate('leasings.ended_on', '<', $dateNow->format('Y-m-d'))
            ->selectRaw("sum(leasings.amount) as total_amount")
            ->selectRaw("count(leasings.id) as total_rents_count")
            ->first();
    }
//select r.status,
//count(r.id) as rent_count,sum(r.amount+r.penality-r.amount_paid) as total_amount_unpaid from rents r
//inner join leasings l on r.leasing_id = l.id
//inner join assets a on l.asset_id = a.id
//inner join tenants t on l.tenant_id = t.id
//where  extract(day from r.deadline::timestamp - current_date::timestamp)<0 and r.status='PENDING'
//group by r.status
    public static function previousUnpaidRents($ownerId,$real_estate_id=null)
    {
        if(empty($real_estate_id)){
            return Rent::join('leasings', 'rents.leasing_id', 'leasings.id')
                ->join('assets', 'leasings.asset_id', 'assets.id')
                ->join('tenants', 'leasings.tenant_id', 'tenants.id')
                ->where('tenants.owner_id', $ownerId)
                ->whereRaw("EXTRACT(DAY FROM rents.deadline - CURRENT_DATE) < 0 AND rents.status='PENDING'")
                ->select(DB::raw("count(rents.id) as rent_count"), DB::raw("sum(rents.amount+rents.penality-rents.amount_paid) as total_amount_unpayed"))
                ->first();
        }else{
            return Rent::join('leasings', 'rents.leasing_id', 'leasings.id')
                ->join('assets', 'leasings.asset_id', 'assets.id')
                ->join('tenants', 'leasings.tenant_id', 'tenants.id')
                ->where('tenants.owner_id', $ownerId)
                ->where('assets.real_estate_id', $real_estate_id)
                ->whereRaw("EXTRACT(DAY FROM rents.deadline - CURRENT_DATE) < 0 AND rents.status='PENDING'")
                ->select(DB::raw("count(rents.id) as rent_count"), DB::raw("sum(rents.amount+rents.penality-rents.amount_paid) as total_amount_unpayed"))
                ->first();
        }

    }

    public static function allCurrentRent($ownerId,$real_estate_id=null)
    {
        if(empty($real_estate_id)){
            return Rent::join('leasings', 'rents.leasing_id', 'leasings.id')
                ->join('assets', 'leasings.asset_id', 'assets.id')
                ->join('tenants', 'leasings.tenant_id', 'tenants.id')
                ->where('tenants.owner_id', $ownerId)
                ->whereRaw("EXTRACT(DAY FROM rents.deadline - CURRENT_DATE) BETWEEN 0 AND assets.frequency_in_month * 30")
                ->select(DB::raw("count(rents.id) as total_rents_count"), DB::raw("sum(rents.amount+rents.penality) as total_rents"),
                    DB::raw("sum(case when rents.status='PENDING' then 1 else 0 end) as toal_rents_unpay_count"), DB::raw("sum(case when rents.status='PENDING' then rents.amount_paid else 0 end) as total_rents_unpaid_amount"),
                    DB::raw("sum(case when rents.status='PAID' then 1 else 0 end) as total_rents_pay_count"), DB::raw("sum(case when rents.status='PAID' then rents.amount_paid else 0 end) as total_rents_paid_amount"))
                ->first();
        }else{
            return Rent::join('leasings', 'rents.leasing_id', 'leasings.id')
                ->join('assets', 'leasings.asset_id', 'assets.id')
                ->join('tenants', 'leasings.tenant_id', 'tenants.id')
                ->where('tenants.owner_id', $ownerId)
                ->where('assets.real_estate_id', $real_estate_id)
                ->whereRaw("EXTRACT(DAY FROM rents.deadline - CURRENT_DATE) BETWEEN 0 AND assets.frequency_in_month * 30")
                ->select(DB::raw("count(rents.id) as total_rents_count"), DB::raw("sum(rents.amount+rents.penality) as total_rents"),
                    DB::raw("sum(case when rents.status='PENDING' then 1 else 0 end) as toal_rents_unpay_count"), DB::raw("sum(case when rents.status='PENDING' then rents.amount_paid else 0 end) as total_rents_unpaid_amount"),
                    DB::raw("sum(case when rents.status='PAID' then 1 else 0 end) as total_rents_pay_count"), DB::raw("sum(case when rents.status='PAID' then rents.amount_paid else 0 end) as total_rents_paid_amount"))
                ->first();
        }

    }

    public static function allRentPaidOnAsset($realEastateId, $assetId, $ownerId)
    {
        return DB::table('real_estates')
            ->join('assets', 'assets.real_estate_id', '=', 'real_estates.id')
            ->join('leasings', 'leasings.asset_id', '=', 'assets.id')
            ->join('rents', 'rents.leasing_id', '=', 'leasings.id')
            ->where('real_estates.owner_id', '=', $ownerId)
            ->where('real_estates.id', '=', $realEastateId)
            ->where('assets.id', '=', $assetId)
            ->where('rents.status', '=', 'PAID')
            ->select('rents.*')
            ->orderBy('rents.status', 'DESC')
            ->orderBy('rents.created_at', 'ASC')
            ->orderBy('rents.year', 'ASC')
            ->orderBy('rents.month', 'ASC')
            ->get();
    }
}
