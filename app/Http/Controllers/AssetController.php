<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Commands\Asset;
use App\Models\Queries\RealEstate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(string $realEstateId, Request $request)
    {
        $realEstate = RealEstate::where('id', $realEstateId)->first();
        if (empty($realEstate)) {
            return response()->json(ApiResponse::NOTFOUND, 404);
        }
        $assets = Asset::where('real_estate_id', $realEstate->id)
            ->get();
        $realEstate['assets'] = $assets;
        return response()->json(ApiResponse::getRessourceSuccess(200, $realEstate));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store(string $realEstateId, StoreAssetRequest $request)
    {
        $realEstate = RealEstate::where('id', $realEstateId)->first();
        if (empty($realEstate)) {
            return response()->json(ApiResponse::NOTFOUND, 404);
        }
        $data = $request->validated();
        try {
            $data['real_estate_id'] = $realEstateId;
            $asset = Asset::create($data);
        } catch (\Exception $e) {
            Log::critical($e->getMessage(), $e->getTrace());
            return response()->json(ApiResponse::SERVERERROR);
        }
        return response()->json(ApiResponse::getRessourceSuccess(201, $asset));

    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(string $realEstateId, string $assetId, Request $request)
    {
        $realEstate = RealEstate::where('id', $realEstateId)->first();
        if (empty($realEstate)) {
            return response()->json(ApiResponse::NOTFOUND, 404);
        }
        $assets = Asset::where('id', $assetId)
            ->get();
        if (empty($assets)) {
            return response()->json(ApiResponse::NOTFOUND, 404);
        }
        $realEstate['assets'] = $assets;
        return response()->json(ApiResponse::getRessourceSuccess(200, $realEstate));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $realEstateId
     * @param string $assetId
     * @param UpdateAssetRequest $request
     * @return JsonResponse
     */
    public function update(string $realEstateId, string $assetId, UpdateAssetRequest $request)
    {
        $realEstate = RealEstate::where('id', $realEstateId)->first();
        if (empty($realEstate)) {
            return response()->json(ApiResponse::NOTFOUND, 404);
        }
        $asset = Asset::where([['real_estate_id', $realEstateId], ['id', $assetId]])->first();
        if (empty($asset)) {
            return response()->json(ApiResponse::NOTFOUND, 404);
        }

        $data = $request->validated();
        try {
            $asset->update($data);
        } catch (\Exception $e) {
            Log::critical($e->getMessage(), $e->getTrace());
            return response()->json(ApiResponse::SERVERERROR);
        }
        return response()->json(ApiResponse::getRessourceSuccess(200, $asset));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Asset $asset
     * @return Response
     */
    public function destroy(Asset $asset)
    {
        //
    }
}
