<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreRealEstateRequest;
use App\Http\Requests\UpdateRealEstateRequest;
use App\Models\Commands\RealEstate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RealEstateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $owner = $request->get('owner');
        $realEstates = RealEstate::where('owner_id', $owner->id)->with('assets')
            ->withCount('assets')
            ->get();
        return response()->json(ApiResponse::getRessourceSuccess(200, $realEstates));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreRealEstateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRealEstateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Commands\RealEstate $realEstate
     * @return JsonResponse
     */
    public function show(string $realEstateId, Request $request)
    {
        $owner = $request->get('owner');
        $realEstate = RealEstate::where([['id', $realEstateId], ['owner_id', $owner->id]])
            ->withCount('assets')
            ->with('assets')
            ->first();
        if ($owner->id != $realEstate->owner_id) {
            return response()->json(ApiResponse::UNAUTHORIZED, 403);
        }
        return response()->json(ApiResponse::getRessourceSuccess(200, $realEstate));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Commands\RealEstate $realEstate
     * @return \Illuminate\Http\Response
     */
    public function edit(RealEstate $realEstate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateRealEstateRequest $request
     * @param \App\Models\Commands\RealEstate $realEstate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRealEstateRequest $request, RealEstate $realEstate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Commands\RealEstate $realEstate
     * @return \Illuminate\Http\Response
     */
    public function destroy(RealEstate $realEstate)
    {
        //
    }
}
