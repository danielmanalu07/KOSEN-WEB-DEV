<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKaryawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan',
        'checkIn',
        'checkOut',
        'status',
        'id_absensi',
    ];

    public function capture()
    {
        return $this->hasOne(Capture::class, 'id_absensi_karyawans');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'id_absensi');
    }
}
