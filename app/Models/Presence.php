<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    public $table = 'presences';

    public $fillable = [
        'user_id',
        'attendance_id',
        'presence_date',
        'presence_enter_time',
        'presence_out_time',
        'is_late'
    ];

    public function user() {
        return $this->belongsToMany(User::class, 'user_id');
    }
    public function attendance()
    {
        return $this->belongsTo(Attendances::class, 'attendance_id');
    }
}
