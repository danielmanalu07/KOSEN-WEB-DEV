<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function Login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'username' => 'required',
                'password' => 'required|min:6',
            ]);

            if (Auth::guard('admin')->attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
                return redirect()->route('Admin.Dashboard')->with('success', 'Anda Berhasil Masuk');
            } else {
                return redirect()->back()->with('error', 'Invalid username or password');
            }
        }
        return view('Admin.Login');
    }

    public function Logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('Admin.Login')->with('success', 'Anda Berhasil Keluar');
    }

    public function Dashboard()
    {
        return view('Admin.Dashboard');
    }
}
