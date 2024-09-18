<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($user->role === 'admin') {
                    return redirect()->route('dashboard.admin')->with('success', 'Berhasil Masuk!');
                } elseif ($user->role === 'pegawai') {
                    return view('Halo Pegawai');
                } else {
                    return redirect()->back()->with('error', 'Peran tidak dikenali');
                }
            } else {
                return redirect()->back()->withErrors([
                    'error' => 'Email dan Password Salah',
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
