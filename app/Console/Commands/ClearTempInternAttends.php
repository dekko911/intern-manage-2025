<?php

namespace App\Console\Commands;

use App\Models\TempInternAttend;
use Illuminate\Console\Command;

class ClearTempInternAttends extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-temp-intern-attends';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting expired temp intern attends';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $yearNow = today('Asia/Kuala_Lumpur')->year;
        $monthNow = today('Asia/Kuala_Lumpur')->month;
        $weekNumberNow = today('Asia/Kuala_Lumpur')->weekNumberInMonth;

        TempInternAttend::where(
            'expired_at',
            '<=',
            getDateFromWeekNumberInMonth($yearNow, $monthNow, $weekNumberNow)
        )->delete();

        $this->info('Expired temp intern attends cleared!');
    }
}
