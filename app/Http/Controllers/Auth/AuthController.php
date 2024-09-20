<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ]);

            // Ambil user berdasarkan email
            $user = User::where('email', $request->email)->first();

            // Cek apakah password yang dimasukkan sama dengan password di database (tanpa hash)
            if ($user && $user->password === $request->password) {

                // Jika password sesuai, login secara manual
                Auth::login($user);

                // Cek peran pengguna dan arahkan ke halaman yang sesuai
                if ($user->role === 'admin') {
                    return redirect()->route('dashboard.admin')->with('success', 'Berhasil Masuk!');
                } elseif ($user->role === 'pegawai') {
                    return response()->json('Halo Pegawai');
                } else {
                    return redirect()->back()->with('error', 'Peran tidak dikenali');
                }
            } else {
                return redirect()->back()->withErrors([
                    'error' => 'Email atau Password salah',
                ]);
            }
        }
        return view('Auth.login');
    }


    public function Logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Berhasil Keluar!');
    }
}
