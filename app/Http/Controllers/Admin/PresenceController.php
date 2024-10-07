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
    public function scan(Request $request)
    {
        // Validasi input
        $request->validate([
            'qr_code' => 'required|string',
            'attendance_id' => 'required|integer|exists:attendances,id',
        ]);
    
        // Ambil input QR Code dan attendance_id
        $qrCode = $request->input('qr_code');
        $attendanceId = $request->input('attendance_id');
    
        // Cari user berdasarkan QR code yang dipindai
        $user = User::where('qrcode', $qrCode)->first();
    
        // Jika user tidak ditemukan
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau QR code tidak valid.'
            ]);
        }
    
        // Cari absensi berdasarkan ID
        $attendance = Attendances::findOrFail($attendanceId);
    
        // Cek apakah user sudah melakukan presensi pada hari ini untuk attendance yang sama
        $presence = Presence::where('user_id', $user->id)
            ->where('attendance_id', $attendanceId)
            ->whereDate('presence_date', now()->toDateString())
            ->first();
    
        // Jika sudah melakukan presensi
        if ($presence) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan presensi sebelumnya hari ini.'
            ]);
        }
    
        // Cek apakah terlambat (dibandingkan dengan batas waktu masuk)
        $currentTime = now();
        $isLate = $currentTime->greaterThanOrEqualTo(Carbon::parse($attendance->batas_start_time));
    
        // Simpan data presensi baru
        Presence::create([
            'user_id' => $user->id,
            'attendance_id' => $attendanceId,
            'presence_date' => now()->toDateString(),
            'presence_enter_time' => now()->toTimeString(),
            'is_late' => $isLate,
        ]);
    
        // Berikan respons sukses
        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Presensi berhasil dicatat',
            'is_late' => $isLate ? 'Terlambat' : 'Tepat waktu',
        ]);
    }
    
    
}
