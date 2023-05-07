<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreRentRequest;
use App\Http\Requests\UpdateRentRequest;
use App\Models\Commands\Rent;

use App\UseCases\RentingUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function getRentsUnpaid($realEastateId,$assetId,Request $request){
        $ownerId = $request->get('owner')->id;

        //apply penality
        RentingUseCase::applayPenality($assetId);

        $rents = DB::table('real_estates')
            ->join('assets', 'assets.real_estate_id', '=', 'real_estates.id')
            ->join('leasings', 'leasings.asset_id', '=', 'assets.id')
            ->join('rents','rents.leasing_id','=','leasings.id')
            ->where('real_estates.owner_id', '=', $ownerId)
            ->where('real_estates.id', '=', $realEastateId)
            ->where('assets.id', '=', $assetId)
            ->where('rents.status', '=', 'PENDING')
            ->select('rents.*')
            ->orderBy('rents.status','DESC')
            ->orderBy('rents.created_at','ASC')
            ->orderBy('rents.year','ASC')
            ->orderBy('rents.month','ASC')
            ->get();

        return  response()->json(ApiResponse::getRessourceSuccess(200,$rents));
    }

    public function getRentsPaid($realEastateId,$assetId,Request $request){
        $ownerId = $request->get('owner')->id;
        $rents = DB::table('real_estates')
            ->join('assets', 'assets.real_estate_id', '=', 'real_estates.id')
            ->join('leasings', 'leasings.asset_id', '=', 'assets.id')
            ->join('rents','rents.leasing_id','=','leasings.id')
            ->where('real_estates.owner_id', '=', $ownerId)
            ->where('real_estates.id', '=', $realEastateId)
            ->where('assets.id', '=', $assetId)
            ->where('rents.status', '=', 'PAID')
            ->select('rents.*')
            ->orderBy('rents.status','DESC')
            ->orderBy('rents.created_at','ASC')
            ->orderBy('rents.year','ASC')
            ->orderBy('rents.month','ASC')
            ->get();

        return  response()->json(ApiResponse::getRessourceSuccess(200,$rents));
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
     * @param  \App\Http\Requests\StoreRentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commands\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function show(Rent $rent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commands\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function edit(Rent $rent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRentRequest  $request
     * @param  \App\Models\Commands\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRentRequest $request, Rent $rent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commands\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rent $rent)
    {
        //
    }
}
