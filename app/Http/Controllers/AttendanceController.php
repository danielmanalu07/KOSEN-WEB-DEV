<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\Position;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::all();
        $positions = Position::all(); // Fetch the positions from the database

        return view('attendance.index', compact('attendances', 'positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch all positions from the database
        $positions = Position::all();

        // Return the create view with positions
        return view('attendance.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'start_time' => 'required|date_format:H:i',
            'batas_start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'batas_end_time' => 'required|date_format:H:i',
            'code' => 'nullable|string',
        ]);

        // Simpan absensi baru ke database
        Attendance::create($request->all());

        return redirect()->route('attendance.index')->with('success', 'Attendance created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        // Menampilkan detail satu absensi
        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch the specific attendance record
        $attendance = Attendance::findOrFail($id);

        // Fetch all positions from the database
        $positions = Position::all();

        // Return the edit view with attendance and positions
        return view('attendance.edit', compact('attendance', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'start_time' => 'required|date_format:H:i',
            'batas_start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'batas_end_time' => 'required|date_format:H:i',
            'code' => 'nullable|string',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        // Hapus absensi dari database
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success', 'Attendance deleted successfully.');
    }
}
