<?php

namespace App\UseCases;

use App\Helpers\Helpers;
use App\Models\Commands\Asset;
use App\Models\Commands\Leasing;
use App\Models\Commands\Rent;
use Carbon\Carbon;
use DateInterval;
use DateTime;

class CreateRentUseCase
{
    public static function createRent(Leasing $leasing, $nbrMonth = 0, Rent $rentForNext = null): array
    {
        //get last rent
        $paymentDeadlineDay = $leasing->payment_deadline_day;
        $currentDate = Carbon::now();
        $dateNow = Carbon::parse($currentDate);

        $rents = Rent::join('leasings','leasings.id','=','rents.leasing_id')
            ->join('assets', 'assets.id','=', 'leasings.asset_id')
            ->join('tenants', 'tenants.id', '=', 'leasings.tenant_id')
            ->where('leasings.id', '=', $leasing->id)
            ->where('leasings.ended_on', '=', null)
            ->orWhereDate('leasings.ended_on', '<', $dateNow->format('Y-m-d'))
            ->select('rents.*')
            ->orderBy('rents.created_at', 'DESC')
            ->first();

        $asset = Asset::join('leasings','leasings.asset_id','=','assets.id')
            ->where('leasings.id', '=', $leasing->id)
            ->select('assets.*')
            ->first();

        if (empty($rents)) {
            $rentForPay = Helpers::getDayOfMonth($paymentDeadlineDay, $leasing->started_on, $dateNow->format('Y-m-t'),$asset->frequency_in_month);
        } else if ($nbrMonth > 0 && !empty($rentForNext)) {
            $month = $rentForNext->month;
            $year = $rentForNext->year;
            $day = 1;
            $lastLeasingAt = date('Y-m-d', strtotime("$year-$month-$day"));
            $lastLeasingDate = (new DateTime($lastLeasingAt))->add(new DateInterval('P'.$asset->frequency_in_month.'M'))->format('d-m-Y');

            $lastLeasingEndDate = (new DateTime($lastLeasingDate))->add(new   DateInterval('P'.($asset->frequency_in_month+$nbrMonth).'M'))->format('Y-m-t');
            $rentForPay = Helpers::getDayOfMonth($paymentDeadlineDay, $lastLeasingDate, $lastLeasingEndDate,$asset->frequency_in_month);
        } else {
            $month = $rents->month;
            $year = $rents->year;
            $day = 1;
            $lastLeasingDate = date('Y-m-d', strtotime("$year-$month-$day"));
            $lastLeasingDate = (new DateTime($lastLeasingDate))->add(new DateInterval('P'.$asset->frequency_in_month.'M'))->format('d-m-Y');

            $lastLeasingEndDate = (new DateTime($dateNow))->add(new   DateInterval('P'.$asset->frequency_in_month.'M'))->format('Y-m-t');
            // add 7 days to the date
            $rentForPay = Helpers::getDayOfMonth($paymentDeadlineDay, $lastLeasingDate, $lastLeasingEndDate,$asset->frequency_in_month);
        }
        foreach ($rentForPay as $rentDeadLine) {
            $rentRealDeadLine = $leasing->type == Leasing::PREPAY ? $rentDeadLine : Helpers::subtractMonth($rentDeadLine,$asset->frequency_in_month);

            $rentMonth = date('m', strtotime($rentRealDeadLine));
            $rentYear = date('Y', strtotime($rentRealDeadLine));
            $label = '#' . $leasing->id . "-$rentMonth-$rentYear";

            $dmonth = date('m', strtotime($rentDeadLine));
            $dyear = date('Y', strtotime($rentDeadLine));
            if (!Rent::where('label', '=', $label)->exists()) {
                Rent::create([
                    'leasing_id' => $leasing->id,
                    'amount' => $leasing->amount,
                    'currency' => 'XOF',
                    'label' => $label,
                    'month' => $rentMonth,
                    'year' => $rentYear,
                    'status' => Rent::PENDING,
                    'amount_paid' => 0,
                    'deadline' => date('Y-m-d', strtotime("$dyear-$dmonth-$paymentDeadlineDay")),
                    'paid_at' => null,
                    'penality' => 0,
                ]);

                $nextMonth = new DateTime(date('Y-m', strtotime("$dyear-$dmonth-$paymentDeadlineDay")));
                $nextMonthJobDate = $nextMonth->format('Y-m');

                Leasing::where('id', "=", $leasing->id)->update(['next_leasing_period' => $nextMonthJobDate]);
            }
        }
        // min date
        return $rentForPay;
    }
}
