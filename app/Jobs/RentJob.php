<?php

namespace App\Jobs;

use App\Models\Commands\Leasing;
use App\UseCases\Rents\CreateRentUseCase;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $currentHour = (int) date('H');
//        if ($currentHour >= 1 && $currentHour <= 6) {
//            Log::info('Execution at ' . Carbon::now());
            $currentDate = Carbon::now();
            $dateNow = Carbon::parse($currentDate);
            Leasing::where('next_leasing_period', '=', null)
                ->orWhere('next_leasing_period', '=', $dateNow->format('Y-m'))
                ->where('ended_on', '=', null)
                ->orWhereDate('ended_on', '<', $dateNow->format('Y-m-d'))
                ->orderBy('created_at')
                ->chunk(100, function ($leasings): void {
                    foreach ($leasings as $leasing) {
                        CreateRentUseCase::createRent($leasing);
                    }
                });
//        }
    }
}
