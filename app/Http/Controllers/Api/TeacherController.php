<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssigmentAsesmentTask;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\StudentClassroomRelation;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function count($id)
    {
        $countTeacher = User::where('role', 'teacher')
        ->where('status', 'accept')
        ->count();
        $countClassroom = Classroom::where('user_id', $id)->count();


        return response()->json([
            'status' => 'success',
            'countTeacher' => $countTeacher,
            'countClassroom' => $countClassroom,
        ],200);
    }

    public function countCourse($id)
    {
    }


    public function studentAssigment($id)
    {
        $data = AssigmentAsesmentTask::where('task_course_id', $id)
                    ->with('user')
                    ->get();

        $formattedData = $data->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'task_course_id' => $assignment->task_course_id,
                'user_id' => $assignment->user_id,
                'name' => $assignment->user->name,
                'link' => $assignment->link,
                'file' => $assignment->file,
                'grade' => $assignment->grade,
                'created_at' => $assignment->created_at,
                'updated_at' => $assignment->updated_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedData
        ], 200);
    }

    public function notStudentAssigment($id)
    {
        $assigments = AssigmentAsesmentTask::where('task_course_id', $id)->get();

        if ($assigments->isEmpty()) {
            $classroomid = null;
        } else {
            $classroomid = $assigments->first()->taskCourse->course->classroom->id;
        }

        $data = [];

        if ($classroomid) {
            $assignedUserIds = $assigments->pluck('user_id')->toArray();

            $students = StudentClassroomRelation::where('classroom_id', $classroomid)
                        ->whereNotIn('user_id', $assignedUserIds)
                        ->with('user')
                        ->get();

            $data = $students->map(function ($relation) {
                return [
                    'user_id' => $relation->user_id,
                    'name' => $relation->user->name,
                    'email' => $relation->user->email
                ];
            });
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function myClass($id)
    {
        $data = Classroom::where('user_id' , $id)
        ->where('status' , 'accept')
        ->withCount(['studentClassroomRelations as student_count' => function($query) {
            $query->where('status', 'accept');
        }])
        ->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
