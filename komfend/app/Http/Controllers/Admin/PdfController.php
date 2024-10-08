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
        // Verifikasi untuk User yang login apakah dia Admin
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden
    
        // Ambil path QR code yang sudah disimpan di database
        $qrCodePath = $karyawan->qrcode;
    
        // Render PDF menggunakan view dengan data karyawan dan path QR code
        $pdf = PDF::loadView('Karyawan/kartu-s', [
            'data' => $karyawan,
            'qr' => asset($qrCodePath) // Generate full URL to the QR code
        ]);
    
        // Download file PDF dengan nama yang sesuai
        return $pdf->download('Kartu-Karyawan-' . $karyawan->id . '.pdf');
    }
    
}
