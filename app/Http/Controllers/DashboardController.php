<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Queries\Tenant;
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

        $tenants = Tenant::where()
            ->join('leasings', 'tenants.id', '=', 'leasings.id')
            ->join('assets', 'assets.id', '=', 'leasings.id')
            ->join('real_estates', 'real_estates.id', '=', 'leasings.id')
            ->join('owners', 'owners.id', '=', 'leasings.id')
            ->get();


        $response = [
            "real_estate_count" => $owner->realEstates->count(),
            "tenants_count" => $tenants->count(),
            "rent_payed_count" => 30,
            "total_rent_payed" => 200000,
            "total_rent" => 300000,
            "real_estates" => $owner->realEstates
        ];

        return response()->json(ApiResponse::getRessourceSuccess(200, $response));
    }
}
