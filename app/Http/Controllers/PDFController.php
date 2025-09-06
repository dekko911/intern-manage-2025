<?php

namespace App\Http\Controllers;

use App\Models\CallOfDuty;
use App\Models\InternAttend;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    protected int $targetMonth;

    protected int $searchByMonth;

    protected int $weekNum;

    public function __construct()
    {
        $this->searchByMonth = request('m', today('Asia/Kuala_Lumpur')->isoFormat('M'));
        $this->targetMonth = today('Asia/Kuala_Lumpur')->month;
        $this->weekNum = today('Asia/Kuala_Lumpur')->weekNumberInMonth;
    }

    /**
     * Generate PDF For All Intern Users.
     *
     * @return \Illuminate\Http\Response
     */
    // public function generatePDF()
    // {
    //     $attendances = InternAttend::latest('status')
    //         ->with(['user:id,name'])
    //         ->when(
    //             $this->searchByMonth,
    //             fn($m) =>
    //             $m->whereMonth('created_at', $this->searchByMonth)
    //         )
    //         ->get()
    //         ->filter(
    //             fn($item) =>
    //             $item->created_at->month === $this->targetMonth
    //         );

    //     $callOfDuties = CallOfDuty::latest('days')->with(['user:id,name'])->get()->groupBy('days');

    //     $dataCoD = [
    //         'title_CoD' => 'PIKET KANTOR HP CREATIVE SPACE ' . today('Asia/Kuala_Lumpur')->year,
    //         'callOfDuties' => $callOfDuties,
    //     ];

    //     $data = [
    //         'title_absen' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
    //         'month' => Carbon::create()->month($this->searchByMonth)->translatedFormat('F'),
    //         'datetime' => now('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y HH:mm'),
    //         'attendances' => $attendances,
    //     ];

    //     $pdf = Pdf::loadView('pdf/myPDF', $data, $dataCoD, 'UTF-8');

    //     $fileName = 'Data Rekap Magang HP Creative Space-' . today('Asia/Kuala_Lumpur')->isoFormat('MMMM Y') . '.pdf';

    //     return $pdf->download($fileName);
    // }

    /**
     * Generate PDF By User id.
     *
     * @param mixed $userId
     * @return \Illuminate\Http\Response
     */
    public function generatePDFByUserId()
    {
        $userId = Auth::user()->id;

        $searchByStatus = request('s');

        $attendances = InternAttend::latest('status')
            ->where('user_id', $userId)
            ->with(['user:id,name'])
            ->when(
                $this->searchByMonth,
                fn($m) =>
                $m->whereMonth('created_at', $this->searchByMonth)
            )
            ->when(
                $searchByStatus,
                fn($s) =>
                $s->whereStatus($searchByStatus)
            )
            ->get()
            ->filter(
                fn($item) =>
                $item->created_at->month === $this->targetMonth
            );

        $callOfDuties = CallOfDuty::latest('days')
            ->with(['user:id,name'])
            ->get()
            ->groupBy('days');

        $dataCoD = [
            'title_CoD' => 'PIKET KANTOR HP CREATIVE SPACE ' . today('Asia/Kuala_Lumpur')->year,
            'callOfDuties' => $callOfDuties,
        ];

        $data = [
            'title_absen' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
            'week' => $this->weekNum,
            'month' => Carbon::create()->month($this->searchByMonth)->translatedFormat('F'),
            'datetime' => now('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y HH:mm'),
            'attendances' => $attendances,
        ];

        $pdf = Pdf::loadView('pdf/myPDF', $data, $dataCoD, 'UTF-8');

        $internName = InternAttend::with(['user:id,name'])->where('user_id', $userId)->first()->user?->name;

        $fileName = 'Data Rekap Magang HP Creative Space  ' . (string) $internName . '-' . today('Asia/Kuala_Lumpur')->toDateString() . '.pdf';

        return $pdf->download($fileName);
    }
}
