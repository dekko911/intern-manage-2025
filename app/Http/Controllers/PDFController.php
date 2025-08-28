<?php

namespace App\Http\Controllers;

use App\Models\CallOfDuty;
use App\Models\InternAttend;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    protected int $targetMonth;

    protected int $searchMonth;

    public function __construct()
    {
        $this->searchMonth = request('m', today('Asia/Kuala_Lumpur')->isoFormat('M'));
        $this->targetMonth = today('Asia/Kuala_Lumpur')->month;
    }

    /**
     * Generate PDF For All Intern Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $attendances = InternAttend::latest('created_at')
            ->with(['user'])
            ->when($this->searchMonth, fn($m) => $m->whereMonth('created_at', $this->searchMonth))
            ->get()
            ->filter(
                fn($item) =>
                $item->created_at->month === $this->targetMonth
            );

        $callOfDuties = CallOfDuty::latest('created_at')->with(['user'])->get();

        $dataCoD = [
            'title_CoD' => 'PIKET KANTOR HP CREATIVE SPACE ' . today('Asia/Kuala_Lumpur')->year,
            'callOfDuties' => $callOfDuties,
        ];

        $data = [
            'title_absen' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
            'month' => Carbon::create()->month($this->searchMonth)->translatedFormat('F'),
            'datetime' => now('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y HH:mm'),
            'attendances' => $attendances,
        ];

        $pdf = Pdf::loadView('pdf/myPDF', $data, $dataCoD, 'UTF-8');

        $fileName = 'Absensi Magang-' . today('Asia/Kuala_Lumpur')->toDateString() . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Generate PDF By User id.
     *
     * @param mixed $userId
     * @return \Illuminate\Http\Response
     */
    public function generatePDFByUserId($userId)
    {
        $attendances = InternAttend::latest('created_at')
            ->where('user_id', $userId)
            ->with(['user'])
            ->when($this->searchMonth, fn($m) => $m->whereMonth('created_at', $this->searchMonth))
            ->get()
            ->filter(
                fn($item) =>
                $item->created_at->month === $this->targetMonth
            );

        $callOfDuties = CallOfDuty::latest('created_at')->where('user_id', $userId)->with(['user'])->get();

        $dataCoD = [
            'title_CoD' => 'PIKET KANTOR HP CREATIVE SPACE ' . today('Asia/Kuala_Lumpur')->year,
            'callOfDuties' => $callOfDuties,
        ];

        $data = [
            'title_absen' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
            'month' => Carbon::create()->month($this->searchMonth)->translatedFormat('F'),
            'datetime' => now('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y HH:mm'),
            'attendances' => $attendances,
        ];

        $pdf = Pdf::loadView('pdf/myPDF', $data, $dataCoD, 'UTF-8');

        $internName = InternAttend::where('user_id', $userId)->first()->user?->name;

        $fileName = 'Absensi Magang ' . (string) $internName . '-' . today('Asia/Kuala_Lumpur')->toDateString() . '.pdf';

        return $pdf->download($fileName);
    }
}
