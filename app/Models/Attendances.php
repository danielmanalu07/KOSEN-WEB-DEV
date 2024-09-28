<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;
    public $table = 'attendances';
    public $fillable = [
        'user_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'batas_end_time',
        'code'
    ];
    // Relasi ke presences
    public function presences()
    {
        return $this->hasMany(Presence::class, 'attendance_id'); // Kolom yang benar
    }
}
