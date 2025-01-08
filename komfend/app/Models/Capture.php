<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capture extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_absensi_karyawans',
        'photo',
    ];

    public function absensiKaryawan()
    {
        return $this->belongsTo(AbsensiKaryawan::class, 'id_absensi_karyawans');
    }
}
