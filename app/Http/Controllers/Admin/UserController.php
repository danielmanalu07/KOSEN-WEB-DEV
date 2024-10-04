<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('nama', '!=', 'admin')->get();
        return view('Admin.User.Dashboard.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.User.Dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'tanggal_lahir' => 'required|date',
                'phone' => 'required|numeric',
                'umur' => 'required|numeric',
            ]);

            // Generate a random password
            $randomPassword = Str::random(8); // Panjang password acak

            // Buat user baru
            $user = new User();
            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->umur = $request->input('umur');
            $user->password = bcrypt($randomPassword); // Simpan password yang di-hash
            $user->tanggal_lahir = $request->input('tanggal_lahir');
            $user->phone = $request->input('phone');
            $user->status = 'aktif';
            $user->role = 'pegawai';

            // Simpan pengguna ke database
            $user->save();

            // Generate QR Code untuk pengguna
            $qrCodePath = $this->generateQRCode($user->id);

            // Update kolom qrcode di database
            $user->qrcode = $qrCodePath;
            $user->save();

            // Kirim email informasi akun
            $mailData = [
                'recipient' => $request->email,
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'fromName' => env('MAIL_FROM_NAME'),
                'subject' => 'Account Information',
                'body' => view('Admin.User.Mail.AccountMail', ['user' => $user])->render(),
            ];

            Mail::send('Admin.User.Mail.AccountMail', ['user' => $user], function ($message) use ($mailData) {
                $message->to($mailData['recipient'])
                    ->from($mailData['fromEmail'], $mailData['fromName'])
                    ->subject($mailData['subject']);
            });

            // Kembalikan password acak untuk dikirimkan kepada pengguna
            return response()->json(['success' => 'Data Berhasil Ditambahkan!', 'password' => $randomPassword]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Terjadi Kesalahan: ' . $th->getMessage()], 500);
        }
    }

    public function generateQRCode($userId)
    {
        // Generate QR code in PNG format
        $qrCode = QrCode::format('png')->size(200)->generate(route('attendances.show', $userId));

        // Define a unique file name for the QR code
        $imageName = 'user-' . $userId . '.png';

        // Define the path for storing the QR code
        $path = public_path('storage/qrcodes/' . $imageName);

        // Check if the directory exists, if not, create it
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true); // Create directory recursively if it doesn't exist
        }

        // Store the generated PNG content to a file
        file_put_contents($path, $qrCode);

        // Return the file path
        return 'storage/qrcodes/' . $imageName;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('Admin.User.Dashboard.show', compact('user')); // Ganti dengan view detail user Anda
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('Admin.User.Dashboard.edit', compact('user')); // Ganti dengan view form edit Anda
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'nama' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'umur' => 'required|numeric',
                'tanggal_lahir' => 'required|date',
                'phone' => 'required|numeric',
            ]);

            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->umur = $request->input('umur');
            $user->tanggal_lahir = $request->input('tanggal_lahir');
            $user->phone = $request->input('phone');

            $user->save();

            return response()->json(['success' => 'Data Berhasil Diperbarui!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Terjadi Kesalahan: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Terjadi Kesalahan: ' . $th->getMessage()], 500);
        }
    }

    public function getcodeuser($id)
    {
        // Ambil data user berdasarkan ID
        $user = User::find($id);

        // Pastikan path QR code diambil dari kolom 'code'
        if ($user && $user->qrcode) {
            $qrCodePath = $user->qrcode;  // Mengambil path dari database
        } else {
            // Jika QR code belum ada di database, kita bisa buat atau berikan error
            $qrCodePath = null;
        }

        return response()->json([
            'qr_code_url' => $qrCodePath ? asset($qrCodePath) : null, // Menggunakan helper asset() untuk mendapatkan URL
        ]);
    }
    public function downloadQrCodePDF($id)
    {
        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Pastikan QR code ada di database
        if (!$user->qrcode) {
            return response()->json(['error' => 'QR Code tidak ditemukan untuk user ini.'], 404);
        }

        // Generate QR code dalam format PNG
        $qrCode = QrCode::format('png')->size(200)->generate(route('attendances.show', ['attendance' => $id]));

        // Encode QR code ke Data URI
        $dataUri = 'data:image/png;base64,' . base64_encode($qrCode);

        // Buat HTML untuk PDF
        $html = '<img src="' . $dataUri . '" style="width: 100%; height: auto;" />';

        // Download PDF
        return \PDF::loadHTML($html)->setWarnings(false)->download('qrcode_user_' . $id . '.pdf');
    }
}
