<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreTenantsRequest;
use App\Http\Requests\UpdateTenantsRequest;
use App\Models\Commands\RealEstate;
use App\Models\Commands\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TenantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $owner = $request->get('owner');
        $tenants = Tenant::where('owner_id', $owner->id)
            ->get();
        return response()->json(ApiResponse::getRessourceSuccess(200, $tenants));
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
     * @param \App\Http\Requests\StoreTenantsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTenantsRequest $request)
    {
        $data = $request->validated();
        try {
            $data['owner_id'] = $request->owner->id;
            $estate = Tenant::create($data);
        } catch (\Exception $e) {
            Log::critical($e->getMessage(), $e->getTrace());
            return response()->json(ApiResponse::SERVERERROR);
        }
        return response()->json(ApiResponse::getRessourceSuccess(201, $estate));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Commands\Tenant $tenants
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Commands\Tenant $tenants
     * @return \Illuminate\Http\Response
     */
    public function edit(Tenant $tenants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateTenantsRequest $request
     * @param \App\Models\Commands\Tenant $tenants
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTenantsRequest $request, Tenant $tenants)
    {
        $data = $request->validated();
        $data['owner_id'] = $request?->owner?->id;
        try {
            foreach ($data as $key => $value) {
                $tenants->$key = $value;
            }
            $tenants->save();

        } catch (\Exception $e) {
            Log::critical($e->getMessage(), $e->getTrace());
            return response()->json(ApiResponse::SERVERERROR);
        }
        return response()->json(ApiResponse::getRessourceSuccess(200, $tenants));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Commands\Tenant $tenants
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenants)
    {
        //
    }
}
