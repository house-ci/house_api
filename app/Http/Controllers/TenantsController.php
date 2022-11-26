<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenantsRequest;
use App\Http\Requests\UpdateTenantsRequest;
use App\Models\Commands\Tenant;

class TenantsController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTenantsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTenantsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commands\Tenant  $tenants
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commands\Tenant  $tenants
     * @return \Illuminate\Http\Response
     */
    public function edit(Tenant $tenants)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTenantsRequest  $request
     * @param  \App\Models\Commands\Tenant  $tenants
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTenantsRequest $request, Tenant $tenants)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commands\Tenant  $tenants
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenants)
    {
        //
    }
}
