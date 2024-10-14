<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = Karyawan::all();
        return view('Karyawan.Index', compact('karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Karyawan.Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'photo' => 'required|mimes:png,jpg,jpeg',
        ]);

        $user = new Karyawan();
        $user->nama = $request->input('nama');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->tanggal_lahir = $request->input('tanggal_lahir');
        $user->status = 'aktif';

        if ($request->file('photo')) {
            $fileName = $request->nama . '.' . $request->file('photo')->extension();
            $filePath = public_path('gambar/users');

            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }

            $request->file('photo')->move($filePath, $fileName);
            $user->photo = 'gambar/users/' . $fileName;
        }

        $user->save();

        $qrData = $user->id;
        $qrFileName = 'qrcode-' . $user->id . '.png';
        $qrPath = public_path('qrcodes/' . $qrFileName);

        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0755, true);
        }

        QrCode::format('png')
            ->size(200)
            ->generate($qrData, $qrPath);

        $user->qrcode = 'qrcodes/' . $qrFileName;
        $user->save();

        try {
            $mailData = [
                'recipient' => $request->email,
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'fromName' => env('MAIL_FROM_NAME'),
                'subject' => 'Information Account',
                'body' => view('Mail.SendMail', ['user' => $user])->render(),
            ];
            Mail::send('Mail.SendMail', ['user' => $user], function ($message) use ($mailData) {
                $message->to($mailData['recipient'])
                    ->from($mailData['fromEmail'], $mailData['fromName'])
                    ->subject($mailData['subject']);
            });
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi Kesalahan Pengiriman Email. Silahkan Coba Lagi' . $th);
        }

        return redirect('/admin/users')->with('success', 'Data Berhasil Ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $karyawans = Karyawan::findOrFail($id);

        return view('Karyawan.Show', compact('karyawans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'photo' => 'nullable|mimes:png,jpg,jpeg',
        ]);

        $karyawan = Karyawan::findOrFail($id);

        $karyawan->nama = $request->input('nama');
        $karyawan->email = $request->input('email');
        $karyawan->phone = $request->input('phone');
        $karyawan->tanggal_lahir = $request->input('tanggal_lahir');
        $karyawan->status = $request->input('status', 'aktif');

        if ($request->file('photo')) {
            if ($karyawan->photo && file_exists(public_path($karyawan->photo))) {
                unlink(public_path($karyawan->photo));
            }

            $fileName = $request->nama . '.' . $request->file('photo')->extension();
            $filePath = public_path('gambar/users');

            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }

            $request->file('photo')->move($filePath, $fileName);
            $karyawan->photo = 'gambar/users/' . $fileName;
        }

        $qrData = $karyawan->id;
        $qrFileName = 'qrcode-' . $karyawan->id . '.png';
        $qrPath = public_path('qrcodes/' . $qrFileName);

        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'), 0755, true);
        }

        QrCode::format('png')
            ->size(200)
            ->generate($qrData, $qrPath);

        $karyawan->qrcode = 'qrcodes/' . $qrFileName;

        $karyawan->save();

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if ($karyawan->photo && file_exists(public_path($karyawan->photo))) {
            File::delete(public_path($karyawan->photo));
        }

        if ($karyawan->qrcode && file_exists(public_path($karyawan->qrcode))) {
            File::delete(public_path($karyawan->qrcode));
        }

        $karyawan->delete();

        return redirect('/admin/users')->with('success', 'Data karyawan berhasil dihapus!');
    }

    public function scan($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('Karyawan.Scan', compact('karyawan'));
    }
}
