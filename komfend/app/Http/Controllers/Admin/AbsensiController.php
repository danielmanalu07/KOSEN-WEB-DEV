<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absensis = Absensi::orderBy('created_at', 'desc')->get();
        return view('Absensi.Index', compact('absensis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Absensi.Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i',
            'checkOut_time' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);
        $checkOut_time = Carbon::parse($request->checkOut_time);
        $now = Carbon::now();

        if ($startTime->lessThan($now->subMinute())) {
            return redirect()->back()->with('error', 'Waktu Mulai tidak boleh kurang dari atau Waktu Sekarang');
        }

        if ($endTime->lessThanOrEqualTo($startTime)) {
            return redirect()->back()->with('error', 'Waktu Selesai tidak boleh kurang dari atau sama dengan Waktu Mulai');
        }

        if ($checkOut_time->lessThanOrEqualTo($startTime) && $checkOut_time->lessThanOrEqualTo($endTime)) {
            return redirect()->back()->with('error', 'Waktu check out tidak boleh kurang dari atau sama dengan Waktu Mulai dan Waktu Selesai');
        }

        $absensi = new Absensi();
        $absensi->judul = $request->judul;
        $absensi->deskripsi = $request->deskripsi;
        $absensi->start_time = $request->start_time;
        $absensi->end_time = $request->end_time;
        $absensi->checkOut_time = $request->checkOut_time;
        $absensi->status = 'unpublished';
        $absensi->save();

        return redirect('/admin/absensis')->with('success', 'Absensi Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $absensi = Absensi::findOrFail($id);
        return view('Absensi.Edit', compact('absensi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i',
            'checkOut_time' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);
        $checkOut_time = Carbon::parse($request->checkOut_time);
        $now = Carbon::now();

        $absensi = Absensi::findOrFail($id);
        $absensi->judul = $request->judul;
        $absensi->deskripsi = $request->deskripsi;
        $absensi->start_time = $request->start_time;
        $absensi->end_time = $request->end_time;
        $absensi->checkOut_time = $request->checkOut_time;

        $absensi->update();

        return redirect('/admin/absensis')->with('success', 'Absensi Berhasil Diubah');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();
        return redirect('/admin/absensis')->with('success', 'Absensi Berhasil Dihapus');
    }

    public function toggleStatus(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        if ($absensi->status == 'unpublished') {
            $absensi->status = 'published';
        } else {
            $absensi->status = 'unpublished';
        }

        $absensi->save();

        return response()->json(['success' => true]);
    }

}
