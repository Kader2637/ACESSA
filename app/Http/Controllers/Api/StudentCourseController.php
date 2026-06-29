<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\StudentClassroomRelation;
use App\Models\User;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function show($id)
    {
        return view('pages.student.course' , compact('id'));
    }

    public function courseClass($id)
    {
        $courses = Course::where('classroom_id' , $id)->get();
        return response()->json([
            'status' => true,
            'data' => $courses,
        ],200);
    }

    public function studentCourse($id)
    {
        $students = StudentClassroomRelation::where('classroom_id', $id)
            ->with('user')
            ->get();

        $formattedData = $students->map(function ($relation) {
            $user = $relation->user;
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile' => $user->image,
                'id_relation' => $relation->id,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $formattedData,
        ], 200);
    }

    public function showstudent(Classroom $classroom)
{
    $classroom = Classroom::where('id', $classroom->id)->first();

    if ($classroom) {
        $user = User::find($classroom->user_id);
        $classroom->user = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile' => $user->image,
        ];
    }

    return response()->json([
        'status' => 'success',
        'data' => $classroom
    ], 200);
}

    public function showPage($id){

        return view('pages.student.detailMateri', compact('id'));
    }

    /**
     * Render the student's KHS page
     */
    public function khsPage()
    {
        return view('pages.student.khs');
    }

    /**
     * Get KHS grade card data for the student
     */
    public function getKhsData(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|integer'
        ]);

        $userId = \Illuminate\Support\Facades\Auth::id();
        $semesterId = $request->query('semester_id');

        // Fetch student's classrooms for the selected semester
        $relations = \App\Models\StudentClassroomRelation::where('user_id', $userId)
            ->where('status', 'accept')
            ->whereHas('classroom', function ($query) use ($semesterId) {
                $query->where('semester_id', $semesterId);
            })
            ->with(['classroom.user', 'classroom.semester'])
            ->get();

        $khsItems = [];
        $totalSks = 0;
        $totalSksLulus = 0;
        $totalSksGraded = 0;
        $weightedPoints = 0;

        foreach ($relations as $rel) {
            $classroom = $rel->classroom;
            if (!$classroom) continue;

            $totalSks += $classroom->sks;

            // Fetch course IDs in this classroom
            $courseIds = \App\Models\Course::where('classroom_id', $classroom->id)->pluck('id');

            // Fetch tasks in those courses
            $taskIds = \App\Models\TaskCourse::whereIn('course_id', $courseIds)->pluck('id');

            // Fetch student submissions for those tasks
            $submissions = \App\Models\AssigmentAsesmentTask::whereIn('task_course_id', $taskIds)
                ->where('user_id', $userId)
                ->get();

            // Calculate average grade
            $grades = $submissions->whereNotNull('grade')->map(function($sub) {
                return floatval($sub->grade);
            });

            $avgGrade = $grades->count() > 0 ? round($grades->average(), 2) : null;
            
            // Map numeric grade to letter grade and grade point (bobot)
            $letterGrade = '-';
            $bobot = 0.0;
            $status = 'Belum Dinilai';

            if ($avgGrade !== null) {
                if ($avgGrade >= 85) {
                    $letterGrade = 'A';
                    $bobot = 4.0;
                } else if ($avgGrade >= 75) {
                    $letterGrade = 'B';
                    $bobot = 3.0;
                } else if ($avgGrade >= 60) {
                    $letterGrade = 'C';
                    $bobot = 2.0;
                } else if ($avgGrade >= 50) {
                    $letterGrade = 'D';
                    $bobot = 1.0;
                } else {
                    $letterGrade = 'E';
                    $bobot = 0.0;
                }

                $status = ($avgGrade >= 60) ? 'Lulus' : 'Tidak Lulus';
                
                if ($avgGrade >= 60) {
                    $totalSksLulus += $classroom->sks;
                }

                $totalSksGraded += $classroom->sks;
                $weightedPoints += ($bobot * $classroom->sks);
            }

            $khsItems[] = [
                'classroom_id' => $classroom->id,
                'name' => $classroom->name,
                'code' => $classroom->codeClass,
                'sks' => $classroom->sks,
                'lecturer' => $classroom->user ? $classroom->user->name : '-',
                'nilai_angka' => $avgGrade !== null ? $avgGrade : '-',
                'nilai_huruf' => $letterGrade,
                'bobot' => $bobot,
                'status' => $status
            ];
        }

        $ips = $totalSksGraded > 0 ? round($weightedPoints / $totalSksGraded, 2) : 0.00;

        return response()->json([
            'success' => true,
            'data' => [
                'khs_items' => $khsItems,
                'total_sks' => $totalSks,
                'total_sks_lulus' => $totalSksLulus,
                'ips' => number_format($ips, 2)
            ]
        ], 200);
    }
}