<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AssigmentAsesmentTaskController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassroomTeacherController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseTeacherController;
use App\Http\Controllers\Api\StudentClassroomRelationController;
use App\Http\Controllers\Api\StudentCourseController;
use App\Http\Controllers\Api\TaskCourseController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ZoomMeetingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ForumDiscussionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/Apiregister/teacher', [LoginController::class, 'registerTeacher']);
Route::post('/Apiregister/student', [LoginController::class, 'registerStudent']);
Route::post('/ApiLogout', [LoginController::class, 'ApiLogout'])->middleware('auth:api');

// approval user
Route::post('/accept/{id}', [LoginController::class, 'accept']);
Route::post('/reject/{id}', [LoginController::class, 'reject']);

// teacher
Route::get('/teacher/pending', [UserController::class, 'teacher']);

// classroom
Route::post('/classroom/teacher', [ClassroomController::class, 'store']);
Route::get('/classroom/teacher/data/{id}', [ClassroomController::class, 'classroomTeacher']);
Route::get('/classroom/show/{classroom}', [ClassroomController::class, 'show']);
Route::delete('/classroom/delete/{classroom}', [ClassroomController::class, 'destroy']);
Route::put('/classroom/update/{classroom}', [ClassroomController::class, 'update']);
Route::get('/my/classroom/teacher/data/{id}', [TeacherController::class, 'myClass']);

// admin approval class
Route::get('approval/classroom', [AdminController::class, 'approvalClass']);
Route::post('/acceptClass/{id}', [AdminController::class, 'acceptClass']);
Route::post('/rejectClass/{id}', [AdminController::class, 'rejectClass']);
Route::get('classroom/admin', [ClassroomController::class, 'classroomAdmin']);

// Classroom teacher
Route::get('/teacher/classroom/show/{classroom}', [ClassroomTeacherController::class, 'show']);
Route::get('/teacher/data/classroom/{id}', [ClassroomController::class, 'studentCourse']);
Route::get('/teacher', [UserController::class, 'teacherAll']);
Route::delete('/user/delete/{user}', [UserController::class, 'destroy']);
Route::get('/student', [UserController::class, 'studentAll']);
Route::get('/zoom/meetings', [ZoomMeetingController::class, 'allMeetings']);

// Course Teacher
Route::post('/teacher/course/create', [CourseController::class, 'store']);
Route::get('/teacher/course/data/{id}', [CourseTeacherController::class, 'courseClass']);
Route::delete('/teacher/course/delete/{course}', [CourseController::class, 'destroy']);
Route::get('/teacher/course/show/{course}', [CourseController::class, 'show']);
Route::put('/teacher/course/update/{course}', [CourseController::class, 'update']);

// student classroom
Route::get('/student/classroom/data/{id}', [StudentClassroomRelationController::class, 'index']);
Route::post('/classroom/join', [StudentClassroomRelationController::class, 'store']);
Route::get('/join/classroom/{id}', [ClassroomController::class, 'classroomStudent']);
Route::post('/Apiclassroom/join/{id}', [StudentClassroomRelationController::class, 'joinclass']);
Route::get('/student/classroom/show/{classroom}', [StudentCourseController::class, 'showstudent']);
Route::get('/student/data/classroom/{id}', [StudentCourseController::class, 'studentCourse']);

// student course
Route::get('/student/course/data/{id}', [StudentCourseController::class, 'courseClass']);
Route::get('/student/course/show/{course}', [CourseController::class, 'show']);

// kick student classroom
Route::delete('/kick/student/{studentClassroomRelation}', [StudentClassroomRelationController::class, 'destroy']);

// pending approval classroom teacher
Route::get('/pending/teacher/{id}', [ClassroomTeacherController::class, 'pendingStudent']);
Route::post('/accept/teacher/{id}', [ClassroomTeacherController::class, 'accept']);
Route::post('/reject/teacher/{id}', [ClassroomTeacherController::class, 'reject']);

// landing page
Route::get('/classroom', [ClassroomController::class, 'index']);

// forum diskusi
Route::get('/forum/discussion/{id}', [ForumDiscussionController::class, 'index']);
Route::post('/forum/discussion', [ForumDiscussionController::class, 'store']);
Route::delete('/forum/discussion/delete/{id}', [ForumDiscussionController::class, 'destroy']);
Route::get('/forum/discussions', [ForumDiscussionController::class, 'allDiscussions']);

// Task course
Route::get('/task/course/{id}', [TaskCourseController::class, 'index']);
Route::post('/task/course/post', [TaskCourseController::class, 'store']);
Route::put('/task/course/update/{taskCourse}', [TaskCourseController::class, 'update']);
Route::delete('/task/course/delete/{taskCourse}', [TaskCourseController::class, 'destroy']);
Route::get('/admin/tasks/data', [TaskCourseController::class, 'allTasks']);

// Assigment Asesment Task
Route::get('/Apiassigment/{id}', [AssigmentAsesmentTaskController::class, 'index']);
Route::post('/assigment/post', [AssigmentAsesmentTaskController::class, 'store']);
Route::put('/assigment/update/{assigmentAsesmentTask}', [AssigmentAsesmentTaskController::class, 'update']);
Route::delete('/assigment/delete/{assigmentAsesmentTask}', [AssigmentAsesmentTaskController::class, 'destroy']);

// Statistikaguru
Route::get('/count/statistika/{id}', [TeacherController::class, 'count']);

// Count admin
Route::get('count/statistika/admin/data', [AdminController::class, 'countAdmin']);

// assigment
Route::get('/not/assigment/task/{id}', [TeacherController::class, 'notStudentAssigment']);
Route::get('/done/assigment/task/{id}', [TeacherController::class, 'studentAssigment']);
Route::patch('/assigment/grade/{id}', [AssigmentAsesmentTaskController::class, 'grade'])->name('assigment.grade');

// count student
Route::get('count/student/{id}', [StudentClassroomRelationController::class, 'countStudent']);

// Zoom SDK & Live Captioning
Route::post('/zoom/signature', [App\Http\Controllers\Api\ZoomController::class, 'generateSignature']);
Route::post('/live-captions', [App\Http\Controllers\Api\LiveCaptionController::class, 'store']);
Route::get('/live-captions', [App\Http\Controllers\Api\LiveCaptionController::class, 'getCaptions']);
Route::get('/semesters', [App\Http\Controllers\Api\SemesterController::class, 'index']);
Route::get('/student/khs', [App\Http\Controllers\Api\StudentCourseController::class, 'getKhsData']);

// Zoom Meetings per Course
Route::get('/zoom-meetings/{course_id}', [App\Http\Controllers\Api\ZoomMeetingController::class, 'index']);
Route::post('/zoom-meetings', [App\Http\Controllers\Api\ZoomMeetingController::class, 'store']);
Route::delete('/zoom-meetings/{id}', [App\Http\Controllers\Api\ZoomMeetingController::class, 'destroy']);
Route::post('/zoom-meetings/end/{id}', [App\Http\Controllers\Api\ZoomMeetingController::class, 'endMeeting']);

// QR Barcode Attendance
Route::post('/attendance/create', [App\Http\Controllers\Api\AttendanceController::class, 'createSession']);
Route::get('/classroom/{classId}/attendance/sessions', [App\Http\Controllers\Api\AttendanceController::class, 'getSessions']);
Route::get('/attendance/session/{sessionId}/records', [App\Http\Controllers\Api\AttendanceController::class, 'getSessionDetails']);
Route::post('/attendance/session/{sessionId}/extend', [App\Http\Controllers\Api\AttendanceController::class, 'extendSession']);
Route::post('/attendance/code', [App\Http\Controllers\Api\AttendanceController::class, 'submitByCode']);
Route::get('/classroom/{classId}/attendance/my', [App\Http\Controllers\Api\AttendanceController::class, 'getStudentAttendance']);

