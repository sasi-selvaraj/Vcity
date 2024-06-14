<?php

namespace App\Console\Commands;

use App\Mail\PartialPaymentMail;
use App\Models\Payment;
use App\Models\Plot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PartialPayStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'partialpaystatus:cron';

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
        $plots = Plot::all();

        foreach ($plots as $plot) {
            $payment = Payment::with('marketers','projects','plots')
                ->where('payment_status', 2)
                ->where('plot_id', $plot->id)
                ->first();

            if ($payment) {
                $checkOtherPayments = Payment::where('plot_id', $payment->plot_id)
                    ->where('payment_status', 3)
                    ->first();

                if (empty($checkOtherPayments)) {
                    $partialUpdatedAt = Carbon::parse($payment->partial_updated_at, $IST);
                    $currentDate = Carbon::now($IST);
                    $daysDifference = $currentDate->diffInDays($partialUpdatedAt);
                    // if ($daysDifference == 22 || $daysDifference == 24 || $daysDifference == 26 || $daysDifference == 28) {
                    //     Mail::to(env('MAIL_USERNAME'))
                    //         ->send(new PartialPaymentMail($payment, $daysDifference));
                    // }
                }
            }
        }
    }
}

