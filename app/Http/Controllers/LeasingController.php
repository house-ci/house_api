<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeasingRequest;
use App\Http\Requests\UpdateLeasingRequest;
use App\Models\Commands\Leasing;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLeasingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeasingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commands\Leasing  $leasing
     * @return \Illuminate\Http\Response
     */
    public function show(Leasing $leasing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commands\Leasing  $leasing
     * @return \Illuminate\Http\Response
     */
    public function edit(Leasing $leasing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLeasingRequest  $request
     * @param  \App\Models\Commands\Leasing  $leasing
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeasingRequest $request, Leasing $leasing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commands\Leasing  $leasing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leasing $leasing)
    {
        //
    }
}
