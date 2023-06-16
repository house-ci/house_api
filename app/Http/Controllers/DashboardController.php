<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\UseCases\Rents\GetRentPaidUseCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $owner = ($request->owner ?? $request->get('owner'));
//dd($owner->realEstates);
        $tenants = DB::table('tenants')
            ->join('leasings', 'tenants.id', '=', 'leasings.tenant_id')
            ->join('assets', 'assets.id', '=', 'leasings.asset_id')
            ->join('real_estates', 'real_estates.id', '=', 'assets.real_estate_id')
            ->join('owners', 'owners.id', '=', 'real_estates.owner_id')
            ->where('owners.id',$owner->id)
            ->select('tenants.id')
            ->distinct()
            ->get();
       $allCurrentRent=GetRentPaidUseCase::allCurrentRent($owner->id);
        $previousUnpaidRents=GetRentPaidUseCase::previousUnpaidRents($owner->id);

        foreach ($owner->realEstates  as $key => $value) {
            $owner->$key = $value;
            $value['current_rents']=GetRentPaidUseCase::allCurrentRent($owner->id,$value->id);
            $value['previous_unpayed_rents']=GetRentPaidUseCase::previousUnpaidRents($owner->id,$value->id);

        }
        $response = [
            "real_estate_count" => $owner->realEstates->count(),
            "tenants_count" => $tenants->count(),
            "rent_payed_count" => $allCurrentRent->total_rents_pay_count,
            "total_rent_payed" => $allCurrentRent->total_rents_paid_amount,
            "total_rent" => $allCurrentRent->total_rents,
            "total_rent_count" => $allCurrentRent->total_rents_count,
            "previous_unpaid_rents"=>$previousUnpaidRents,
            "real_estates" => $owner->realEstates,
        ];

        return response()->json(ApiResponse::getRessourceSuccess(200, $response));
    }
}
