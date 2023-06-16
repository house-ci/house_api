<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreRealEstateRequest;
use App\Http\Requests\UpdateRealEstateRequest;
use App\Models\Commands\RealEstate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
        $realEstates = RealEstate::where('owner_id', $owner->id)
            ->withCount('assets')
            ->with(['assets' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(ApiResponse::getRessourceSuccess(200, $realEstates));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRealEstateRequest $request
     * @return JsonResponse
     */
    public function store(StoreRealEstateRequest $request)
    {
        $data = $request->validated();
        try {
            $data['owner_id'] = $request->owner->id;
            $estate = RealEstate::create($data);
        } catch (\Exception $e) {
            Log::critical($e->getMessage(), $e->getTrace());
            return response()->json(ApiResponse::SERVERERROR);
        }
        return response()->json(ApiResponse::getRessourceSuccess(200, $estate));
    }

    /**
     * Display the specified resource.
     *
     * @param string $realEstateId
     * @param Request $request
     * @return JsonResponse
     */
    public function show(string $realEstateId, Request $request)
    {
        $owner = $request->get('owner');
        $realEstate = RealEstate::where([['id', $realEstateId], ['owner_id', $owner->id]])
            ->withCount('assets')
            ->with(['assets' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->first();
        if (empty($realEstate)) {
            return response()->json(ApiResponse::NOTFOUND, 404);
        }
        if ($owner->id != $realEstate->owner_id) {
            return response()->json(ApiResponse::UNAUTHORIZED, 403);
        }
        return response()->json(ApiResponse::getRessourceSuccess(200, $realEstate));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRealEstateRequest $request
     * @param RealEstate $realEstate
     * @return JsonResponse
     */
    public function update(UpdateRealEstateRequest $request, RealEstate $realEstate)
    {
        if ($realEstate?->owner_id != $request?->owner?->id) {
            return response()->json(ApiResponse::UNAUTHORIZED, 403);
        }
        $data = $request->validated();
        try {
            foreach ($data as $key => $value) {
                $realEstate->$key = $value;
            }
            $realEstate->save();

        } catch (\Exception $e) {
            Log::critical($e->getMessage(), $e->getTrace());
            return response()->json(ApiResponse::SERVERERROR);
        }
        return response()->json(ApiResponse::getRessourceSuccess(200, $realEstate));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RealEstate $realEstate
     * @return Response
     */
    public function destroy(RealEstate $realEstate)
    {
        RealEstate::destroy($realEstate->id);
    }
}
