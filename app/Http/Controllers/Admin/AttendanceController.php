<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Attendances;

class AttendanceController extends Controller
{
    // Menampilkan daftar jadwal absensi
    public function index()
    {
        $attendances = Attendances::all();
        return view('Admin.User.Absensi.index', compact('attendances'));
    }

    // Menyimpan jadwal absensi baru ke database dan generate QR Code
    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'title' => 'required',
            'description' => 'required|max:500',
            'start_time' => 'required',
            'end_time' => 'required',
            'batas_end_time' => 'required',
        ]);

        // Simpan data absensi ke dalam database
        $attendance = Attendances::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'batas_end_time' => $request->batas_end_time,
        ]);

        // Generate QR Code dan simpan path ke dalam database
        $attendance->code = $this->generateQRCode($attendance->id);
        $attendance->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Jadwal absensi berhasil dibuat dengan QR Code.');
    }

    // Fungsi untuk generate QR Code dan menyimpannya ke folder public/qrcodes

    // Generate QR code in SVG format
    public function generateQRCode($attendanceId)
    {
        // Generate QR code in SVG format (no need for Imagick)
        $qrCode = QrCode::format('svg')->size(200)->generate(route('attendances.show', $attendanceId));

        // Define a file path for storing the QR code
        $path = 'qrcodes/attendance-' . $attendanceId . '.svg';

        // Store the generated SVG content to a file
        \Storage::disk('public')->put($path, $qrCode);

        // Return the file path
        return 'storage/' . $path;
    }


    // Menampilkan QR Code di halaman detail absensi
    public function show($id)
    {
        // Ambil data absensi berdasarkan id
        $attendance = Attendances::findOrFail($id);

        // Jika QR Code belum digenerate, generate QR Code
        if (!$attendance->code) {
            $attendance->code = $this->generateQRCode($attendance->id);
            $attendance->save();
        }

        // Tampilkan view dengan data attendance dan QR code
        return view('Admin.User.Absensi.show', compact('attendance'));
    }
}
