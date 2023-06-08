<?php

namespace App\UseCases;

use App\Helpers\Helpers;
use App\Models\Commands\Asset;
use App\Models\Commands\Leasing;
use App\Models\Commands\Payment;
use App\Models\Commands\PaymentDetail;
use App\Models\Commands\Rent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaidRentUseCase
{
    public static function manageAPenality($assetId): void
    {
        $currentDate = Carbon::now();
        $dateNow = Carbon::parse($currentDate);

        //apply a penality
        $rentsForApplyPenality = Rent::join('leasings','leasings.id','=','rents.leasing_id')
            ->join('assets', 'assets.id','=', 'leasings.asset_id')
            ->where('leasings.is_penalized',true)
            ->where('rents.is_apply_penality',true)
            ->where('assets.id',$assetId)
            ->where('rents.status','PENDING')
            ->where('penality','=',0)
            ->whereDate('deadline','<',$dateNow->format('Y-m-d'))
            ->select('rents.*')
            ->get();
        foreach ($rentsForApplyPenality as $rent) {
            $penality=Helpers::roundUpTo(round(($rent->amount-$rent->amount_paid) * (10/100)),100);
            $rent->penality=$penality;
            $rent->save();
        }
        //remove the penality
        $rentsForRemovePenality = Rent::join('leasings','leasings.id','=','rents.leasing_id')
            ->join('assets', 'assets.id','=', 'leasings.asset_id')
            ->where('leasings.is_penalized',false)
            ->orWhere('rents.is_apply_penality',false)
            ->where('assets.id',$assetId)
            ->where('rents.status','PENDING')
            ->where('penality','<>',0)
            ->whereDate('deadline','<',$dateNow->format('Y-m-d'))
            ->select('rents.*')
            ->get();
        foreach ($rentsForRemovePenality as $rent) {
            $rent->penality=0;
            $rent->save();
        }
    }

    public static function generateDetails(Rent $rent, $amount, Payment $payment,$paymentDate)
    {
        try {
            $paymentBalance = $amount;
            $rentBalance = $rent->amount - $rent->amount_paid;
            $paymentAmount = $paymentBalance >= $rentBalance ? $rentBalance : $paymentBalance;

            $rent->amount_paid += $paymentAmount;
            //creer detail payement
            $paymentDetailsPayload = [
                'amount' => $paymentAmount,
                'type' => PaymentDetail::RENTAL,
                'rent_id' => $rent->id,
                'payment_id' => $payment->id,
            ];
            PaymentDetail::create($paymentDetailsPayload);

            $paymentBalance -= $paymentAmount;
            //metta a jour la rente

            if ($rent->amount_paid >= ($rent->amount + $rent->penality)) {
                $paidAt =$paymentDate?$paymentDate:date('Y-m-d H:i:s');
                $rent->status = Rent::PAID;
                $rent->paid_at = $paidAt;
            }

            if ($paymentBalance > 0 && $rent->penality > 0 && ($rent->deadline<$paymentDate)) {
                $penalityPayAmount = $paymentBalance >= $rent->penality ? $rent->penality : $paymentBalance;
                $penalityPayload = [
                    'amount' => $penalityPayAmount,
                    'type' => PaymentDetail::PENALITY,
                    'rent_id' => $rent->id,
                    'payment_id' => $payment->id,
                ];
                PaymentDetail::create($penalityPayload);

                $rent->amount_paid += $penalityPayAmount;

                if ($rent->amount_paid >= ($rent->amount + $rent->penality)) {
                    $paidAt =$paymentDate?$paymentDate: date('Y-m-d H:i:s');
                    $rent->status = Rent::PAID;
                    $rent->paid_at = $paidAt;
                }

                $paymentBalance -= $penalityPayAmount;
            }
            $rent->save();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        return $paymentBalance;

    }

    private static function payManyRents( $assetId,$paymentBalance,$payment,$paymentDate)
    {
        try {
            $balance=$paymentBalance;

            $rents = Rent::join('leasings','leasings.id','=','rents.leasing_id')
                ->join('assets', 'assets.id','=', 'leasings.asset_id')
                ->where('assets.id',$assetId)
                ->where('rents.status','PENDING')
                ->select('rents.*')
                ->orderBy('rents.created_at', 'ASC')
                ->orderBy('rents.year', 'ASC')
                ->orderBy('rents.month', 'ASC')
                ->get();
            if($rents->isEmpty()){
                $currentDate = Carbon::now();
                $dateNow = Carbon::parse($currentDate);
                $leasing = Leasing::join('assets','assets.id','=','leasings.asset_id')
                    ->where('assets.id',$assetId)
                    ->where('leasings.ended_on', '=', null)
                    ->select('leasings.*')
                    ->orWhereDate('leasings.ended_on', '<', $dateNow->format('Y-m-d'))
                    ->first();
                PaidRentUseCase::generateLeasings($balance,$leasing->id);
//                $rents=Rent::where('status','PENDING')->get();
            }
            foreach ($rents as $rent) {
                if ($balance > 0) {
                    $balance = PaidRentUseCase::generateDetails($rent, $balance, $payment,$paymentDate);
                }
            }
        } catch (\Exception $e) {
            echo $e;
            Log::info($e->getMessage());
        }

        return $balance;
    }
    public static function paidRent($rentId, $amount, $payer, $assetId,$paymentDate): void
    {

        $amount = (int)$amount;
        $payer = (empty($payer)) ? 'OWNER' : $payer;
        try {
            //apply penality
            PaidRentUseCase::manageAPenality($assetId);

            $rent = Rent::join('leasings','leasings.id','=','rents.leasing_id')
                ->join('assets', 'assets.id','=', 'leasings.asset_id')
                ->where('assets.id',$assetId)
                ->where('rents.id',$rentId)
                ->where('rents.status','PENDING')
                ->select('rents.*')
                ->first();
            $paidOn = date('Y-m-d');
            $paidAt = date('Y-m-d H:i:s');
            $payId = Helpers::generateRandomString(30);
            $payload = [
                'pay_id' => $payId,
                'rent_id' => !empty($rent) ? $rent->id : null,
                'paid_on' => $paidOn,
                'paid_at' => $paidAt,
                'paid_by' => $payer,
                'amount' => $amount,
            ];
            $payment = Payment::create($payload);
            $paymentBalance = 0;
            if (!empty($rent)) {
                $paymentBalance = PaidRentUseCase::generateDetails($rent, $amount, $payment);
                if ($paymentBalance > 0) {
                    $paymentBalance=  PaidRentUseCase::payManyRents($assetId,$paymentBalance,$payment,$paymentDate);
                }
            }
             if(empty($rent)){
                $paymentBalance= PaidRentUseCase::payManyRents($assetId,$amount,$payment,$paymentDate);
            }
             if ($paymentBalance > 0) {
                //creer  a partir du dernier rent

                 $rent = Rent::join('leasings','leasings.id','=','rents.leasing_id')
                     ->join('assets', 'assets.id','=', 'leasings.asset_id')
                     ->where('assets.id',$assetId)
                     ->select('rents.*')
                     ->orderBy('rents.created_at', 'DESC')
                     ->orderBy('rents.year', 'DESC')
                     ->orderBy('rents.month', 'DESC')
                     ->first();

                if(empty(!$rent)){
                    PaidRentUseCase::generateLeasings($paymentBalance,$rent->leasing_id);
                    //payer les impayee dans l'ordre croissant la date de creation
                    //pay rents
                    PaidRentUseCase::payManyRents($assetId,$paymentBalance,$payment,$paymentDate);
                }
            }
            // }, 5);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }
    public static function generateLeasings($paymentBalance,$leasingId){
        $leasing=Leasing::where('id',$leasingId)->first();
        $nbrRentWhoToGenerate = ceil($paymentBalance / $leasing->amount);
        //generate rents
        CreateRentUseCase::createRent($leasing,$nbrRentWhoToGenerate);
    }
}
