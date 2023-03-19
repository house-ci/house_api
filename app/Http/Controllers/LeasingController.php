<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreLeasingRequest;
use App\Http\Requests\UpdateLeasingRequest;
use App\Models\Commands\Leasing;
use App\Models\Commands\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeasingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function end_rental($leasingId, Request $request)
    {
        $leasing = Leasing::where('id', $leasingId)->first();
        if (empty($leasing)) {
            $error = "Leasing does not exist!";
            return response()->json(ApiResponse::error(500, $error), 500);
        } elseif ($leasing->ended_on != null) {
            $error = "Leasing already ended!";
            return response()->json(ApiResponse::error(500, $error), 500);
        }
        $ownerId = $request?->owner?->id;
        $asset = DB::table('assets')
            ->join('leasings', 'leasings.asset_id', '=', 'assets.id')
            ->where('leasings.owner_id', '=', $ownerId)
            ->where('leasings.id', '=', $leasingId)
            ->select('assets.*')
            ->first();
        if (empty($asset)) {
            $error = "Asset not found!";
            return response()->json(ApiResponse::error(500, $error), 500);
        }
        DB::transaction(function () use ($leasing, $request, $asset) {
            //end the rental
            $leasing->ended_on = $request->ended_on;
            $leasing->save();
            $asset->is_available = true;
            $asset->save();
        });
        // release the house


        return response()->json(ApiResponse::getRessourceSuccess(201, $leasing));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreLeasingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($tenantId, $assetId, StoreLeasingRequest $request)
    {
        //Leasing exist?
        $leasing = Leasing::where([['tenant_id', $tenantId], ['asset_id', $assetId], ['is_active', true]])->first();
        if (!empty($leasing)) {
            $error = "Leasing already exist!";
            return response()->json(ApiResponse::error(500, $error), 500);
        }
        $ownerId = $request?->owner?->id;
        //Tenant
        $tenant = Tenant::where([['id', $tenantId], ['owner_id', $ownerId]])->first();
        if (empty($tenant)) {
            $error = "tenant not found!";
            return response()->json(ApiResponse::error(500, $error), 500);
        }
        //Asset
        $asset = DB::table('assets')
            ->join('real_estates', 'assets.real_estate_id', '=', 'real_estates.id')
            ->where('real_estates.owner_id', '=', $ownerId)
            ->where('assets.id', '=', $assetId)
            ->select('assets.*')
            ->first();
        if (empty($asset)) {
            $error = "Asset not found!";
            return response()->json(ApiResponse::error(500, $error), 500);
        }
        $data = $request->validated();

        try {
            //Leasing
            $data['tenant_id'] = $tenantId;
            $data['asset_id'] = $assetId;
            $data['is_active'] = true;

            DB::beginTransaction();
            try {
                $leasing = Leasing::create($data);
                DB::table('assets')
                    ->where('id', $asset->id)
                    ->update(['is_available' => false]);
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(ApiResponse::error(500, $ex->getMessage()), 500);
            }
        } catch (\Exception $e) {
            Log::critical($e->getMessage(), $e->getTrace());
            return response()->json(ApiResponse::SERVERERROR);
        }
        return response()->json(ApiResponse::getRessourceSuccess(201, $leasing));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Commands\Leasing $leasing
     * @return \Illuminate\Http\Response
     */
    public function show(Leasing $leasing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Commands\Leasing $leasing
     * @return \Illuminate\Http\Response
     */
    public function edit(Leasing $leasing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateLeasingRequest $request
     * @param \App\Models\Commands\Leasing $leasing
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeasingRequest $request, Leasing $leasing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Commands\Leasing $leasing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leasing $leasing)
    {
        Leasing::destroy($leasing->id);
    }
}
