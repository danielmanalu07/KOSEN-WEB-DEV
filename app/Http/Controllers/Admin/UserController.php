<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('nama', '!=', 'admin')->get();
        return view('Admin.User.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

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
                'password' => 'required|min:6',
                'tanggal_lahir' => 'required',
                'phone' => 'required|numeric',
                'umur' => 'required|numeric',
            ]);

            $user = new User();
            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->umur = $request->input('umur');
            $user->password = Hash::make($request->input('password'));
            $user->tanggal_lahir = $request->input('tanggal_lahir');
            $user->phone = $request->input('phone');
            $user->status = 'aktif';
            $user->role = 'pegawai';

            $user->save();

            return response()->json(['success' => 'Data Berhasil Ditambahkan!']);

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Terjadi Kesalahan: ' . $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
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

            // Jika password diisi, lakukan update
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

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

}
