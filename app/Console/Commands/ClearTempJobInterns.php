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
    protected $description = 'Delete expired temp job interns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yearNow = today('Asia/Kuala_Lumpur')->year;
        $monthNow = today('Asia/Kuala_Lumpur')->month;
        $weekNumberNow = today('Asia/Kuala_Lumpur')->weekNumberInMonth;
        $weekDayNow = today('Asia/Kuala_Lumpur')->dayOfWeek;

        TempJobIntern::where(
            'expired_at',
            '<=',
            ClearTempInternAttends::getDateFromWeekNumberInMonth($yearNow, $monthNow, $weekNumberNow, $weekDayNow)
        )->delete();

        $this->info('Expired temp job interns cleared!');
    }
}
