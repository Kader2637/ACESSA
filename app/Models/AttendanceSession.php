<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'valid_until' => 'datetime',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function isExpired()
    {
        return $this->valid_until->isPast();
    }
}
