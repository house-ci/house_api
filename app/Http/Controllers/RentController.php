<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreRentRequest;
use App\Http\Requests\UpdateRentRequest;
use App\Models\Commands\Rent;
use App\UseCases\Rents\GetRentPaidUseCase;
use App\UseCases\Rents\PaidRentUseCase;
use Illuminate\Http\Request;

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
        PaidRentUseCase::manageAPenality($assetId);

        $rents = Rent::join('leasings','leasings.id','rents.leasing_id')
            ->join('assets', 'assets.id', '=', 'leasings.asset_id')
            ->join('real_estates','real_estates.id' ,'=', 'assets.real_estate_id')
            ->where('real_estates.owner_id', '=', $ownerId)
            ->where('real_estates.id', '=', $realEastateId)
            ->where('assets.id', '=', $assetId)
            ->where('rents.status', '=', 'PENDING')
            ->select('rents.*')
            ->with(['leasing.tenant' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->orderBy('rents.status','DESC')
            ->orderBy('rents.created_at','ASC')
            ->orderBy('rents.year','ASC')
            ->orderBy('rents.month','ASC')
            ->get();

        return  response()->json(ApiResponse::getRessourceSuccess(200,$rents));
    }

    public function getRentsPaid($realEastateId,$assetId,Request $request){
        $ownerId = $request->get('owner')->id;
        $rents = GetRentPaidUseCase::allRentPaidOnAsset($realEastateId,$assetId,$ownerId);

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
