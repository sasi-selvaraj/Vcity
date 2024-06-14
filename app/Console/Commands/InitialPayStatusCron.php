<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Plot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InitialPayStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initialpaystatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $IST = new \DateTimeZone('Asia/Kolkata');
        $payment_status = Payment::where('payment_status', 1)->first();

        if ($payment_status) {
            $plotId = $payment_status->plot_id;
            $checkOtherPayments = Payment::where('plot_id', $plotId)
                ->where('payment_status', '!=', 1)
                ->get()
                ->toArray();

            if (empty($checkOtherPayments)) {
                $checkDaysBtw = Payment::where('payment_status', 1)
                    ->where('initial_updated_at', '<', Carbon::now($IST)->subDays(5))
                    ->get();

                if ($checkDaysBtw->isNotEmpty()) {
                    foreach ($checkDaysBtw as $status) {
                        $status->update([
                            'payment_status' => 5
                        ]);
                    }
                    $plots = Plot::where('id', $checkDaysBtw->first()->plot_id)->get();
                    foreach ($plots as $plot) {
                        $plot->update([
                            'status' => 'Available',
                            'balance_amount' => $plot->total_amount,
                            'paid_amount' => 0,
                        ]);
                    }
                }
            }
        }
    }
}
