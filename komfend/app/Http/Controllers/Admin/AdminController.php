<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiKaryawan;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function Login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'username' => 'required',
                'password' => 'required|min:6',
            ]);

            if (Auth::guard('admin')->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
                return redirect()->route('Admin.Dashboard')->with('success', 'Anda Berhasil Masuk');
            } else {
                return redirect()->back()->with('error', 'Invalid username or password');
            }
        }
        return view('Admin.Login');
    }

    public function Logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('Admin.Login')->with('success', 'Anda Berhasil Keluar');
    }

    public function Dashboard()
    {
        $absensi = Absensi::count();
        $karyawan = Karyawan::count();
        $absen = AbsensiKaryawan::count();

        $karyawanData = Karyawan::select(
            DB::raw("COUNT(id) as total_karyawan"),
            DB::raw("DATE_FORMAT(created_at, '%b %Y') as bulan"),
            DB::raw("YEAR(created_at) as tahun"),
            DB::raw("MONTH(created_at) as bulan_angka")
        )
            ->groupBy('tahun', 'bulan_angka', 'bulan')
            ->orderByRaw('tahun ASC, bulan_angka ASC')
            ->get();

        $absensiData = AbsensiKaryawan::select(
            DB::raw("COUNT(id) as total_absen"),
            DB::raw("DATE_FORMAT(created_at, '%b %Y') as bulan"),
            DB::raw("YEAR(created_at) as tahun"),
            DB::raw("MONTH(created_at) as bulan_angka")
        )
            ->groupBy('tahun', 'bulan_angka', 'bulan')
            ->orderByRaw('tahun ASC, bulan_angka ASC')
            ->get();

        $total_karyawan = $karyawanData->pluck('total_karyawan');
        $bulan_karyawan = $karyawanData->pluck('bulan');

        $total_absen = $absensiData->pluck('total_absen');
        $bulan_absensi = $absensiData->pluck('bulan');

        return view('Admin.Dashboard', compact('absensi', 'karyawan', 'absen', 'total_karyawan', 'bulan_karyawan', 'total_absen', 'bulan_absensi'));
    }
    public function DataAbsen(Request $request)
    {
        $query = AbsensiKaryawan::query();

        if ($request->filter_by == 'week') {
            $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->filter_by == 'month') {
            $query->whereMonth('tanggal', now()->month);
        } elseif ($request->filter_by == 'year') {
            $query->whereYear('tanggal', now()->year);
        }

        $absens = $query->orderByRaw("FIELD(status, 'Hadir', 'Terlambat')")
            ->orderBy('tanggal', 'asc')->get();

        if ($request->get('export') == 'pdf') {
            $pdf = FacadePdf::loadView('pdf.dataAbsen', ['data' => $absens]);
            return $pdf->download('Data Absen.pdf');
        }

        return view('Admin.DataAbsensi', compact('absens'));
    }
}
