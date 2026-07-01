<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
