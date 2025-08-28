<?php

namespace App\Console\Commands;

use App\Models\TempInternAttend;
use Carbon\Carbon;
use Carbon\CarbonInterface;
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
    protected $description = 'Delete expired temp intern attends';

    /**
     * Converter for Date from Week Number in Month.
     *
     * @param int $year
     * @param int $month
     * @param int $weekNumber
     * @param int $weekday
     * @return Carbon
     */
    public static function getDateFromWeekNumberInMonth(int $year, int $month, int $weekNumber, int $weekday = 1)
    {
        $date = Carbon::create($year, $month, 1)
            ->setTimezone('Asia/Kuala_Lumpur')
            ->startOfMonth()
            ->startOfWeek(CarbonInterface::MONDAY)
            ->addWeeks($weekNumber - 1)
            ->addDays($weekday - 1);

        return $date;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yearNow = today('Asia/Kuala_Lumpur')->year;
        $monthNow = today('Asia/Kuala_Lumpur')->month;
        $weekNumberNow = today('Asia/Kuala_Lumpur')->weekNumberInMonth;
        $weekDayNow = today('Asia/Kuala_Lumpur')->dayOfWeek;

        TempInternAttend::where(
            'expired_at',
            '<=',
            self::getDateFromWeekNumberInMonth($yearNow, $monthNow, $weekNumberNow, $weekDayNow)
        )->delete();

        $this->info('Expired temp intern attends cleared!');
    }
}
