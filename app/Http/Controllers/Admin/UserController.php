<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

            // Create a new user
            $user = new User();
            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->umur = $request->input('umur');
            $user->password = bcrypt($randomPassword);
            $user->tanggal_lahir = $request->input('tanggal_lahir');
            $user->phone = $request->input('phone');
            $user->status = 'aktif';
            $user->role = 'pegawai';

            // Save the user to the database
            $user->save();

            // Send email with account information
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

            return response()->json(['success' => 'Data Berhasil Ditambahkan!', 'password' => $randomPassword]);
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
