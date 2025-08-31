<?php

use Carbon\Carbon;
use Carbon\CarbonInterface;

if (!function_exists('getDateFromWeekNumberInMonth')) {
    /**
     * Converter for Date from Week Number in Month Start At MONDAY. Indo: Pada Saat Hari Senin entah di minggu ke berapa pun itu, akan dicari/reset/hapus dll.
     *
     * @param int $year
     * @param int $month
     * @param int $weekNumber
     * @param int $weekday
     * @return Carbon
     */
    function getDateFromWeekNumberInMonth(int $year, int $month, int $weekNumber, int $weekday = 1): Carbon
    {
        return Carbon::create($year, $month, 1)
            ->startOfMonth()
            ->startOfWeek(CarbonInterface::MONDAY)
            ->addWeeks($weekNumber)
            ->addDays($weekday);
    }
}
