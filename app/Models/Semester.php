<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'semester_number',
        'is_active',
    ];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
