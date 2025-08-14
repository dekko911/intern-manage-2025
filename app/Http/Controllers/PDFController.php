<?php

namespace App\Http\Controllers;

use App\Models\InternAttend;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    /**
     * Generate PDF For All Intern Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $attendances = InternAttend::latest('created_at')->with(['user'])->get();

        $data = [
            'title' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
            'month' => today('Asia/Kuala_Lumpur')->isoFormat('MMMM'),
            'datetime' => now('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y HH:mm'),
            'attendances' => $attendances,
        ];

        $pdf = Pdf::loadView('pdf/myPDF', $data);

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
        $attendances = InternAttend::latest('created_at')->where('user_id', $userId)->with(['user'])->get();

        $data = [
            'title' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
            'datetime' => now('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y HH:mm'),
            'attendances' => $attendances,
        ];

        $pdf = Pdf::loadView('pdf/myPDF', $data);

        $internName = InternAttend::where('user_id', $userId)->first()->user?->name;

        $fileName = 'Absensi Magang ' . (string) $internName . '-' . today('Asia/Kuala_Lumpur')->toDateString() . '.pdf';
        return $pdf->download($fileName);
    }
}
