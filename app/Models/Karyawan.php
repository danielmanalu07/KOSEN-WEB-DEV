<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    public $table = 'karyawans';

    protected $fillable = [
        'id',
        'nama',
        'photo',
        'email',
        'tanggal_lahir',
        'phone',
        'qrcode',
        'status',
    ];
}
