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


    // Mendownload QR Code dalam bentuk PDF
    public function downloadQrCodePDF(Request $request)
    {
        $attendanceId = $request->query('attendanceId');

        // Generate QR code
        $qrCode = QrCode::format('png')->size(200)->generate(route('attendances.show', ['attendance' => $attendanceId]));

        // Encode QR code to Data URI
        $dataUri = 'data:image/png;base64,' . base64_encode($qrCode);

        // Create HTML for PDF
        $html = '<img src="' . $dataUri . '" style="width: 100%; height: auto;" />';

        // Download PDF
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
    // public function presentUser(Request $request, Attendances $attendance)
    // {
    //     $validated = $request->validate([
    //         'user_id' => 'required|numeric',
    //         "presence_date" => "required|date",
    //     ]);

    //     $user = User::findOrFail($validated['user_id']);

    //     $presence = Presence::where('attendance_id', $attendance->id)
    //         ->where('user_id', $user->id)
    //         ->where('presence_date', $validated['presence_date'])
    //         ->first();

    //     if ($presence) {
    //         return back()->with('failed', 'User sudah terdaftar hadir pada tanggal tersebut.');
    //     }

    //     Presence::create([
    //         "attendance_id" => $attendance->id,
    //         "user_id" => $user->id,
    //         "presence_date" => $validated['presence_date'],
    //         "presence_enter_time" => now()->toTimeString(),
    //         "presence_out_time" => now()->toTimeString(),
    //     ]);

    //     return back()->with('success', "Berhasil menyimpan kehadiran untuk {$user->name}.");
    // }

    // // Fungsi helper untuk mendapatkan karyawan yang tidak hadir
    // private function getNotPresentEmployees($presences)
    // {
    //     $uniquePresenceDates = $presences->unique("presence_date")->pluck('presence_date');
    //     $uniquePresenceDatesAndUserIds = $uniquePresenceDates->map(function ($date) use ($presences) {
    //         return [
    //             "presence_date" => $date,
    //             "user_ids" => $presences->where('presence_date', $date)->pluck('user_id')->toArray(),
    //         ];
    //     });

    //     $notPresentData = [];
    //     foreach ($uniquePresenceDatesAndUserIds as $presence) {
    //         $notPresentData[] = [
    //             "not_presence_date" => $presence['presence_date'],
    //             "users" => User::with('position')
    //                 ->onlyEmployees()
    //                 ->whereNotIn('id', $presence['user_ids'])
    //                 ->get()
    //                 ->toArray(),
    //         ];
    //     }
    //     return $notPresentData;
    // }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'attendance_id' => 'required|numeric',
        ]);
    
        // Parse the URL and extract the attendance ID if the QR code contains a URL
        $qrCode = $request->qr_code;
        $parsedUrl = parse_url($qrCode);
    
        // If the URL contains an attendance ID, extract it from the path
        if (isset($parsedUrl['path'])) {
            $pathParts = explode('/', $parsedUrl['path']);
            $attendanceId = end($pathParts); // Assuming the ID is the last part of the URL
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid QR code format.']);
        }
    
        // Find the attendance record based on the extracted attendance ID
        $attendance = Attendances::find($attendanceId);
    
        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Attendance not found.']);
        }
    
        // Proceed with user presence check and save logic here
        $user = User::where('qrcode', $request->qr_code)->first();
    
        if ($user) {
            $isLate = false;
            $current_time = now();
            $start_time = \Carbon\Carbon::parse($attendance->start_time);
    
            if ($current_time->greaterThan($start_time)) {
                $isLate = true;
            }
    
            Presence::create([
                'user_id' => $user->id,
                'attendance_id' => $attendance->id,
                'presence_date' => now()->toDateString(),
                'presence_enter_time' => $current_time->toTimeString(),
                'qr_code' => $request->qr_code,
                'is_late' => $isLate,
            ]);
    
            return response()->json([
                'success' => true,
                'user' => [
                    'nama' => $user->nama,
                    'photo' => $user->photo,
                    'isLate' => $isLate,
                ],
            ]);
        }
    
        return response()->json(['success' => false]);
    }
    
}
