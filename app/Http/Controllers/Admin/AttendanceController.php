<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendances;

class AttendanceController extends Controller
{
    // Menampilkan daftar jadwal absensi
    public function index()
    {
        $attendances = Attendances::all();
        return view('Admin.User.Absensi.index', compact('attendances'));
    }

    // Menyimpan jadwal absensi baru ke database
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

        $attendance->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Jadwal absensi berhasil dibuat.');
    }

    // Menampilkan detail absensi tanpa QR Code
    public function show($id)
    {
        // Ambil data absensi berdasarkan id
        $attendance = Attendances::findOrFail($id);

        // Tampilkan view dengan data attendance
        return view('Admin.User.Absensi.show', compact('attendance'));
    }
}
