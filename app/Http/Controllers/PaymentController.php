<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\MakePaymentRequest;
use App\Models\Commands\Payment;
use App\Models\Commands\PaymentDetail;
use App\Models\Commands\Rent;
use App\UseCases\PaidRentUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function leasingPayements($realEastateId,$assetId,Request $request)
    {
        $owner = $request->get('owner');

        $payments = Payment::join('payment_details', 'payment_details.payment_id', '=', 'payments.id')
            ->join('rents', 'rents.id', '=', 'payment_details.rent_id')
            ->join('leasings', 'leasings.id', '=', 'rents.leasing_id')
            ->join('tenants', 'tenants.id', '=', 'leasings.tenant_id')
            ->join('assets', 'assets.id', '=', 'leasings.asset_id')
            ->join('real_estates', 'real_estates.id', '=', 'assets.real_estate_id')
            ->where('real_estates.id', $realEastateId)
            ->where('tenants.owner_id', $owner->id)
            ->where('leasings.asset_id', $assetId)
            ->select('payments.*')
            ->with('details.rent')
            ->orderBy('payments.created_at', 'DESC')
            ->get();

        return response()->json(ApiResponse::getRessourceSuccess(200, $payments));
    }

    public function detailPayements($realEastateId,$assetId,$paymentId,Request $request)
    {
        $owner = $request->get('owner');

        $payments = PaymentDetail::join('rents', 'payment_details.rent_id', '=', 'rents.id')
            ->join('payments', 'payments.id', '=', 'payment_details.payment_id')
            ->join('leasings', 'leasings.id', '=', 'rents.leasing_id')
            ->join('tenants', 'tenants.id', '=', 'leasings.tenant_id')
            ->join('assets', 'assets.id', '=', 'leasings.asset_id')
            ->join('real_estates', 'real_estates.id', '=', 'assets.real_estate_id')
            ->where('real_estates.id', $realEastateId)
            ->where('payments.id', $paymentId)
            ->where('tenants.owner_id', $owner->id)
            ->where('leasings.asset_id', $assetId)
            ->select('payment_details.*')
            ->with('rent')
            ->orderBy('payment_details.created_at', 'DESC')
            ->get();

        return response()->json(ApiResponse::getRessourceSuccess(200, $payments));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($realEastateId,$assetId,MakePaymentRequest $request)
    {
        $owner = $request->get('owner');


        $leasings = DB::table('leasings')
            ->join('tenants', 'tenants.id', '=', 'leasings.tenant_id')
            ->join('assets', 'assets.id', '=', 'leasings.asset_id')
            ->join('real_estates', 'real_estates.id', '=', 'assets.real_estate_id')
            ->where('real_estates.id', '=', $realEastateId)
            ->where('tenants.owner_id', '=', $owner->id)
            ->where('leasings.asset_id', '=', $assetId)
            ->select('leasings.*')
            ->get();
        if (empty($leasings)) {
            $error = "Leasing does not exist!";
            return response()->json(ApiResponse::error(404, $error), 404);
        }
        PaidRentUseCase::paidRent($request->rentId, $request->paymentAmount, $request->payer, $assetId,$request->paymentDate);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
