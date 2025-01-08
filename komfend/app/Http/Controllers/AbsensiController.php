<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AbsensiKaryawan;
use App\Models\Capture;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function Index()
    {
        $absensis = Absensi::where('status', 'published')->get();

        $absensiKaryawans = AbsensiKaryawan::with('karyawan')
            ->whereHas('absensi', function ($query) {
                $query->where('status', 'published');
            })
            ->get();

        return view('Karyawan.Absen', compact('absensis', 'absensiKaryawans'));
    }
    public function Store(Request $request)
    {
        $absensi = Absensi::where('id', $request->id_absensi)->first();
        $karyawan = Karyawan::where('id', $request->id_karyawan)->first();

        if (!$absensi) {
            return redirect()->back()->with('error', 'Absensi tidak ditemukan.');
        }

        if (!$karyawan) {
            return redirect()->back()->with('error', 'Akun Karyawan Tidak Ditemukan');
        }

        $now = Carbon::now();
        $startTime = Carbon::parse($absensi->start_time);
        $endTime = Carbon::parse($absensi->end_time);
        $checkOutTime = Carbon::parse($absensi->checkOut_time);

        $cek = AbsensiKaryawan::where([
            'id_karyawan' => $request->id_karyawan,
            'id_absensi' => $request->id_absensi,
        ])->first();

        if ($cek && is_null($cek->checkOut)) {
            if ($now->greaterThan($checkOutTime)) {
                return redirect()->back()->with('error', 'Check-out gagal! Waktu check-out sudah lewat.');
            }
            $cek->update(['checkOut' => $now]);
            return redirect()->back()->with('success', 'Check-out berhasil.');
        }

        if ($cek && !is_null($cek->checkOut)) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out sebelumnya.');
        }

        if ($now->lessThan($startTime)) {
            return redirect()->back()->with('error', 'Absensi Belum Dimulai');
        }

        if ($cek) {
            return redirect()->back()->with('error', 'Anda sudah absen untuk absensi ini.');
        }

        $status = ($now > $endTime->addMinute()) ? 'Terlambat' : 'Hadir';

        $absensiKaryawan = AbsensiKaryawan::create([
            'id_karyawan' => $request->id_karyawan,
            'checkIn' => $now,
            'checkOut' => null,
            'status' => $status,
            'id_absensi' => $request->id_absensi,
        ]);

        if ($request->photo) {
            $photoData = $request->photo;
            $photoName = 'photo_' . $karyawan->id . '_' . time() . '.png';
            $photoPath = public_path('photos/' . $photoName);
            file_put_contents($photoPath, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photoData)));

            Capture::create([
                'id_absensi_karyawans' => $absensiKaryawan->id,
                'photo' => $photoName,
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil Check-in!, Silahkan Ambil foto Anda!');
    }

}
