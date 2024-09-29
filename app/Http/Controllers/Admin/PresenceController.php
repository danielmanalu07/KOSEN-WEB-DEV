<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attendances;
use App\Models\Presence;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PresenceController extends Controller
{
    // Menampilkan daftar kehadiran
    public function index()
    {
        $attendances = Attendances::all(); // Get all attendance records
        return view('Admin.User.Presence.index', [
            "title" => "Data Kehadiran",
            "attendances" => $attendances, // Pass the collection to the view
        ]);
    }

    // Menampilkan detail kehadiran
    // public function show(Attendances $attendance, $id)
    // {
    //     // Ambil tanggal 30 hari terakhir
    //     $priodDate = collect();
    //     for ($i = 0; $i < 30; $i++) {
    //         $priodDate->push(Carbon::now()->subDays($i)->toDateString());
    //     }

    //     // Load related data for attendance
    //     $attendance->load(['presences.user']);

    //     // Find the attendance record by ID
    //     $attendanceRecord = Attendances::findOrFail($id);

    //     // Jika QR Code belum digenerate, generate QR Code
    //     if (!$attendanceRecord->code) {
    //         $attendanceRecord->code = $this->generateQRCode($attendanceRecord->id);
    //         $attendanceRecord->save();
    //     }

    //     // Ambil histori kehadiran user
    //     $history = $attendance->presences()->with('user')->get();

    //     return view('Admin.User.Presence.show', [
    //         "title" => "Data Detail Kehadiran",
    //         "attendance" => $attendance,
    //         "priodDate" => $priodDate,
    //         "history" => $history,
    //         "code" => $attendanceRecord->code, // Pass the code directly
    //     ]);
    // }
    public function show($id)
    {
        // Ambil data absensi berdasarkan id
        $attendance = Attendances::findOrFail($id);

        // Jika QR Code belum digenerate, generate QR Code
        if (!$attendance->code) {
            $attendance->code = $this->generateQRCode($attendance->id);
            $attendance->save();
        }

        // Define the date range for the last 30 days
        $priodDate = [];
        for ($i = 0; $i < 30; $i++) {
            $priodDate[] = now()->subDays($i)->toDateString();
        }

        // Ambil data histori absensi
        $history = $attendance->presences()->with('user')->get();

        // Tampilkan view dengan data attendance dan QR code
        return view('Admin.User.Presence.show', compact('attendance', 'priodDate', 'history'));
    }

    // Menampilkan QR Code untuk absensi
    // public function showQrcode(Request $request)
    // {
    //     $code = $request->query('code');
    //     $qrcode = $this->getQrCode($code);

    //     return view('Admin.User.Presence.index', [
    //         "title" => "Generate Absensi QRCode",
    //         "qrcode" => $qrcode,
    //         "code" => $code,
    //     ]);
    // }
    // public function showQrcode(Request $request, $id)
    // {
    //     $attendance = Attendances::findOrFail($id);

    //     // Jika QR Code belum digenerate, generate QR Code
    //     if (!$attendance->code) {
    //         $attendance->code = $this->generateQRCode($attendance->id);
    //         $attendance->save();
    //     };
    //     // Menampilkan view dengan QR code
    //     return view('Admin.User.Presence.qrcode', compact('attendance'));
    // }


    // Mendownload QR Code dalam bentuk PDF
    public function downloadQrCodePDF(Request $request)
    {
        $code = $request->query('code');
        $qrcode = $this->getQrCode($code);

        $html = '<img src="' . $qrcode . '" />';
        return Pdf::loadHTML($html)->setWarnings(false)->download('qrcode.pdf');
    }

    // Fungsi untuk mengambil QR code
    public function getQrCode(?string $code): string
    {
        if (!$code) {
            throw new \InvalidArgumentException('Code cannot be null');
        }

        $attendance = Attendances::where('code', $code)->firstOrFail();

        return asset("qrcodes/{$attendance->code}.png");
    }

    // Menampilkan data karyawan yang tidak hadir
    public function notPresent(Attendances $attendance, Request $request)
    {
        $byDate = $request->query('display-by-date', now()->toDateString());

        $presences = Presence::where('attendance_id', $attendance->id)
            ->where('presence_date', $byDate)
            ->get(['presence_date', 'user_id']);

        if ($presences->isEmpty()) {
            $notPresentData = [
                [
                    "not_presence_date" => $byDate,
                    "users" => User::with('position')
                        ->onlyEmployees()
                        ->get()
                        ->toArray(),
                ],
            ];
        } else {
            $notPresentData = $this->getNotPresentEmployees($presences);
        }

        return view('presences.not-present', [
            "title" => "Data Karyawan Tidak Hadir",
            "attendance" => $attendance,
            "notPresentData" => $notPresentData,
        ]);
    }

    // Menyimpan data kehadiran user
    public function presentUser(Request $request, Attendances $attendance)
    {
        $validated = $request->validate([
            'user_id' => 'required|numeric',
            "presence_date" => "required|date",
        ]);

        $user = User::findOrFail($validated['user_id']);

        $presence = Presence::where('attendance_id', $attendance->id)
            ->where('user_id', $user->id)
            ->where('presence_date', $validated['presence_date'])
            ->first();

        if ($presence) {
            return back()->with('failed', 'User sudah terdaftar hadir pada tanggal tersebut.');
        }

        Presence::create([
            "attendance_id" => $attendance->id,
            "user_id" => $user->id,
            "presence_date" => $validated['presence_date'],
            "presence_enter_time" => now()->toTimeString(),
            "presence_out_time" => now()->toTimeString(),
        ]);

        return back()->with('success', "Berhasil menyimpan kehadiran untuk {$user->name}.");
    }

    // Fungsi helper untuk mendapatkan karyawan yang tidak hadir
    private function getNotPresentEmployees($presences)
    {
        $uniquePresenceDates = $presences->unique("presence_date")->pluck('presence_date');
        $uniquePresenceDatesAndUserIds = $uniquePresenceDates->map(function ($date) use ($presences) {
            return [
                "presence_date" => $date,
                "user_ids" => $presences->where('presence_date', $date)->pluck('user_id')->toArray(),
            ];
        });

        $notPresentData = [];
        foreach ($uniquePresenceDatesAndUserIds as $presence) {
            $notPresentData[] = [
                "not_presence_date" => $presence['presence_date'],
                "users" => User::with('position')
                    ->onlyEmployees()
                    ->whereNotIn('id', $presence['user_ids'])
                    ->get()
                    ->toArray(),
            ];
        }
        return $notPresentData;
    }
}
