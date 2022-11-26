<?php

namespace App\Http\Controllers;

use App\Models\Queries\Owner;
use App\Models\Queries\RealEstate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $ownerIdentifier = $request->header('X-OwnerIdentifier');
        $owner = Owner::where(
            ['identifier' => $ownerIdentifier]
        )->first();

        $response = Owner::select('owners.*', '');

        $response = [
            "real_estate_count" => $owner->realEstates()->count(),
            "tenants_count" => $owner->realEstates()->assets()->leasings()->tenants()->count(),
            "rent_payed_count" => 30,
            "total_rent_payed" => 200000,
            "total_rent" => 300000,
            "real_estates" => []
        ];
    }
}
