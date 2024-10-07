<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('nama', '!=', 'admin')->get();
        return view('Admin.User.Dashboard.index', compact('users'));
    }

    public function create()
    {
        return view('Admin.User.Dashboard.create');
    }

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
            $randomPassword = Str::random(8);

            // Create new user
            $user = new User();
            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->umur = $request->input('umur');
            $user->password = bcrypt($randomPassword); // Save the hashed password
            $user->tanggal_lahir = $request->input('tanggal_lahir');
            $user->phone = $request->input('phone');
            $user->status = 'aktif';
            $user->role = 'pegawai';

            // Save the user in the database
            $user->save();

            // Generate QR Code and store it in the storage
            $qrCodePath = 'qrcodes/user-' . $user->id . '.png';
            $qrCodeUrl = route('attendances.show', $user->id); // The URL the QR code will point to

            QrCode::format('png')->size(200)->generate($qrCodeUrl, storage_path('app/public/' . $qrCodePath));

            // Update the user with the QR code path
            $user->qrcode = 'storage/' . $qrCodePath; // Public path to access the QR code
            $user->save();

            // Store the password in the session temporarily
            session()->flash('generated_password', $randomPassword);

            // Redirect to the user's detail page
            return redirect()->route('users.show', $user->id);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Terjadi Kesalahan: ' . $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('Admin.User.Dashboard.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('Admin.User.Dashboard.edit', compact('user'));
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
        // Fetch the user and return a placeholder for the QR code URL
        $user = User::find($id);

        return response()->json([
            'qr_code_url' => $user ? route('attendances.show', $user->id) : null,
        ]);
    }
}
