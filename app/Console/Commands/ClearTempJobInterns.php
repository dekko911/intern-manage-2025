<?php

namespace App\Console\Commands;

use App\Models\TempJobIntern;
use Illuminate\Console\Command;

class ClearTempJobInterns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-temp-job-interns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting expired temp job interns';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $yearNow = today('Asia/Kuala_Lumpur')->year;
        $monthNow = today('Asia/Kuala_Lumpur')->month;
        $weekNumberNow = today('Asia/Kuala_Lumpur')->weekNumberInMonth;

        TempJobIntern::where(
            'expired_at',
            '<=',
            getDateFromWeekNumberInMonth($yearNow, $monthNow, $weekNumberNow)
        )->delete();

        $this->info('Expired temp job interns cleared!');
    }
}
