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

    protected $appends = ['meeting_number'];

    public function getMeetingNumberAttribute()
    {
        if (empty($this->zoom_link)) {
            return '';
        }

        // Extract meeting number from Zoom link (e.g. /j/123456789 or /s/123456789)
        $pattern = '/\/(?:j|s)\/(\d+)/';
        if (preg_match($pattern, $this->zoom_link, $matches)) {
            return $matches[1];
        }

        // Fallback: search for any sequence of 9-11 digits
        if (preg_match('/(\d{9,11})/', $this->zoom_link, $matches)) {
            return $matches[1];
        }

        return '';
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
