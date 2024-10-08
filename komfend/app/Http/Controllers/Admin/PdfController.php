<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class PdfController extends Controller
{
    public function downloadKartuSatuan(Karyawan $karyawan)
    {
        // Render PDF menggunakan view dengan data karyawan
        $pdf = PDF::loadView('Karyawan/kartu-s', [
            'data' => $karyawan, // Pastikan ini mengirimkan $karyawan ke tampilan
            'qr' => asset($karyawan->qrcode) // Anda bisa gunakan ini untuk jika Anda perlu menggunakan URL
        ]);
    
        // Download file PDF dengan nama yang sesuai
        return $pdf->download('Kartu-Karyawan-' . $karyawan->id . '.pdf');
    }
    
    
}
