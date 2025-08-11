<?php

namespace App\Http\Controllers;

use App\Models\InternAttend;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $attendances = InternAttend::latest('created_at')->with(['user'])->get();

        $data = [
            'title' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
            'date' => today('Asia/Kuala_Lumpur')->isoFormat('MMMM'),
            'attendances' => $attendances,
        ];

        $pdf = Pdf::loadView('pdf/myPDF', $data);

        $fileName = 'Absensi Magang-' . today('Asia/Kuala_Lumpur')->toDateString() . '.pdf';
        return $pdf->download($fileName);
    }

    public function generatePDFByUserId($userId)
    {
        $attendances = InternAttend::latest('created_at')->where('user_id', $userId)->with(['user'])->get();

        $data = [
            'title' => 'ABSENSI MAGANG ' . today('Asia/Kuala_Lumpur')->year,
            'date' => today('Asia/Kuala_Lumpur')->isoFormat('MMMM'),
            'attendances' => $attendances,
        ];

        $pdf = Pdf::loadView('pdf/myPDF', $data);

        $fileName = 'Absensi Magang-' . today('Asia/Kuala_Lumpur')->toDateString() . '.pdf';
        return $pdf->download($fileName);
    }
}
