<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AbsensiKaryawan;
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

        $currentTime = Carbon::now();

        if (!$absensi) {
            return redirect()->back()->with('error', 'Absensi tidak ditemukan.');
        }

        if (!$karyawan) {
            return redirect()->back()->with('error', 'Akun Karyawan Tidak Ditemukan');
        }

        $startTime = Carbon::parse($absensi->start_time);
        $endTime = Carbon::parse($absensi->end_time);

        if ($currentTime->lessThan($startTime)) {
            return redirect()->back()->with('error', 'Absensi Belum Dimulai');
        }

        $cek = AbsensiKaryawan::where([
            'id_karyawan' => $request->id_karyawan,
            'id_absensi' => $request->id_absensi,
        ])->first();

        if ($cek) {
            return redirect()->back()->with('error', 'Anda sudah absen untuk absensi ini.');
        }

        $status = ($currentTime > $endTime->addMinute()) ? 'Terlambat' : 'Hadir';

        AbsensiKaryawan::create([
            'id_karyawan' => $request->id_karyawan,
            'tanggal' => $currentTime,
            'status' => $status,
            'id_absensi' => $request->id_absensi,
        ]);

        if ($status === 'Terlambat') {
            return redirect()->back()->with('warning', 'Absensi Anda Terlambat');
        }

        return redirect()->back()->with('success', 'Berhasil Absen!');
    }
}
