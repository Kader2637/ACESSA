<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'zoom_link',
        'passcode',
        'meeting_time',
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
