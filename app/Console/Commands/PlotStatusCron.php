<?php

namespace App\Console\Commands;

use App\Models\Plot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PlotStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plotstatus:cron';

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
        $plot_status = Plot::where('status', 'Hold')
            ->where('status_updated_at', '<=', Carbon::now($IST)->subHours(3))
            ->get();
        foreach ($plot_status as $status) {
            $status->update([
                'status' => 'Available'
            ]);
        }
    }
}
