<?php

namespace App\UseCases;

use App\Helpers\Helpers;
use App\Models\Commands\Leasing;
use App\Models\Commands\Payment;
use App\Models\Commands\PaymentDetail;
use App\Models\Commands\Rent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RentingUseCase
{
    public static function applayPenality($assetId)
    {
        $currentDate = Carbon::now();
        $dateNow = Carbon::parse($currentDate);

        $rents = Rent::join('leasings','leasings.id','=','rents.leasing_id')
            ->join('assets', 'assets.id','=', 'leasings.asset_id')
            ->where('assets.id',$assetId)
            ->where('rents.status','PENDING')
            ->where('penality','=',0)
            ->whereDate('deadline','<',$dateNow->format('Y-m-d'))
            ->select('rents.*')
            ->get();
        foreach ($rents as $rent) {
            $rent->penality=(($rent->amount-$rent->amount_paid) * (10/100));
            $rent->save();
        }
    }

    public static function generateDetail(Rent $rent, $amount, Payment $payment)
    {
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
            $paidAt = date('Y-m-d H:i:s');
            $rent->status = Rent::PAID;
            $rent->paid_at = $paidAt;
        }

        if ($paymentBalance > 0 && $rent->penality > 0) {
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
                $paidAt = date('Y-m-d H:i:s');
                $rent->status = Rent::PAID;
                $rent->paid_at = $paidAt;
            }

            $paymentBalance -= $penalityPayAmount;
        }
        $rent->save();

        return $paymentBalance;

    }

    private static function payManyRents( $assetId,$paymentBalance,$payment)
    {
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
        foreach ($rents as $rent) {
            if ($balance > 0) {
                $balance = RentingUseCase::generateDetail($rent, $balance, $payment);
            }
        }
        return $balance;
    }
    public static function payRent($rentId, $amount, $payer,  $assetId)
    {

        $amount = (int)$amount;
        $payer = (empty($payer)) ? 'TENANT' : $payer;
        try {
            //apply penality
            RentingUseCase::applayPenality($assetId);
//            $rent=Rent::where([['id',$rentId],['status','PENDING']])->first();

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
            if (!empty($rent) && $rent->status === Rent::PENDING) {
                $paymentBalance = RentingUseCase::generateDetail($rent, $amount, $payment);
                if ($paymentBalance > 0) {
                    $paymentBalance=  RentingUseCase::payManyRents($assetId,$paymentBalance,$payment);
                }
            }
             if(empty($rent)){
                $paymentBalance= RentingUseCase::payManyRents($assetId,$amount,$payment);
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
                    $leasing=Leasing::where('id',$rent->leasing_id)->first();
                    $nbrRentWhoToGenerate = ceil($paymentBalance / $leasing->amount);
                   //generate rents
                    RentUseCase::createRent($leasing,$nbrRentWhoToGenerate,$rent);
                    //payer les impayee dans l'ordre croissant la date de creation
                    //pay rents
                    RentingUseCase::payManyRents($assetId,$paymentBalance,$payment);
                }
            }
            // }, 5);
        } catch (\Exception $e) {
            echo $e;
            Log::info($e->getMessage());
        }
    }
}
