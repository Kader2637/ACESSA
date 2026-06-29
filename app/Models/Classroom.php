<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomFactory> */
    use HasFactory;
    protected $guarded  = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function studentClassroomRelations() {
        return $this->hasMany(StudentClassroomRelation::class);
    }

    public function semester() {
        return $this->belongsTo(Semester::class);
    }
}
