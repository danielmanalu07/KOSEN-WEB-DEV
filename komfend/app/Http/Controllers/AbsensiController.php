<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function Index()
    {
        return view('Karyawan.Absen');
    }

    public function Store(Request $request)
    {
        $cek = Absensi::where([
            'id_karyawan' => $request->id_karyawan,
            'tanggal' => date('Y-m-d'),
        ])->first();

        if ($cek) {
            return redirect()->back()->with('error', 'Anda Sudah Absen');
        }

        Absensi::create([
            'id_karyawan' => $request->id_karyawan,
            'tanggal' => date('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Berhasil Absen');
    }
}
