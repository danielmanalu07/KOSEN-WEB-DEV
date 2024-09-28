<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Attendances;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendances::all();
        return view('Admin.User.Absensi.index', compact('attendances'));
    }

    // Menyimpan jadwal absensi baru ke database dan generate QR Code
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required|max:500',
            'start_time' => 'required',
            'end_time' => 'required',
            'batas_end_time' => 'required',
        ]);

        // Simpan jadwal absensi
        $attendance = Attendances::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'batas_end_time' => $request->batas_end_time,
        ]);

        // Generate QR Code dan simpan path ke database
        $attendance->code = $this->generateQRCode($attendance->id);
        $attendance->save();

        return redirect()->route('Admin.User.Absensi.index')->with('success', 'Jadwal absensi berhasil dibuat dengan QR Code.');
    }

    // Generate QR Code untuk absensi
    // private function generateQRCode($attendanceId)
    // {
    //     $qrCodeContent = route('presences.create', ['attendance_id' => $attendanceId]);
    //     $qrCode = QrCode::format('png')->size(200)->generate($qrCodeContent);

    //     // Tentukan lokasi sementara untuk menyimpan QR Code
    //     $tempFilePath = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
    //     file_put_contents($tempFilePath, $qrCode);

    //     // Tentukan path tujuan file
    //     $destinationPath = public_path('qrcodes/attendance_' . $attendanceId . '.png');

    //     // Pindahkan file ke lokasi tujuan
    //     rename($tempFilePath, $destinationPath);

    //     return 'qrcodes/attendance_' . $attendanceId . '.png'; // Simpan path QR Code di database
    // }
    // Generate QR Code untuk absensi
    private function generateQRCode($attendanceId)
    {
        $qrCodeContent = route('presences.create', ['attendance_id' => $attendanceId]);
        // Use the 'png' format with GD
        $qrCode = QrCode::format('png')->size(200)->generate($qrCodeContent);

        // Tentukan path tujuan file
        $destinationPath = public_path('qrcodes/attendance_' . $attendanceId . '.png');

        // Simpan QR Code ke file
        file_put_contents($destinationPath, $qrCode);

        return 'qrcodes/attendance_' . $attendanceId . '.png'; // Simpan path QR Code di database
    }


    // Menampilkan QR Code di halaman detail absensi
    // Menampilkan QR Code di halaman detail absensi
    public function show($id)
    {
        // Ambil data attendance berdasarkan id
        $attendance = Attendances::findOrFail($id);

        // Generate QR Code jika belum ada
        if (!$attendance->code) {
            $attendance->code = $this->generateQRCode($attendance->id);
            $attendance->save();
        }

        // Tampilkan view dengan data attendance
        return view('Admin.User.Absensi.show', compact('attendance'));
    }
}
