<?php
// Routes untuk student dengan middleware

use App\Http\Controllers\Api\StudentCourseController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::prefix('student')->middleware(['auth', CheckRole::class . ':student'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.student.index');
    })->name('student/dashboard');

    Route::get('/classroom', function () {
        return view('pages.student.class');
    })->name('student/classroom');

    Route::get('/diskusi', function () {
        return view('pages.student.diskusi');
    })->name('diskusi');

    Route::get('/taskColection', function () {
        return view('pages.student.task_collection');
    })->name('taskColection');

    Route::get('/khs', [App\Http\Controllers\Api\StudentCourseController::class, 'khsPage'])->name('student.khs');
});

Route::middleware(['auth',CheckRole::class . ':student'])->group(function () {
    Route::get('/join/classroom', function () {
        return view('pages.student.join_classroom.index');
    })->name('join.classroom');
    Route::get('/student/classroom/course/{id}', [StudentCourseController::class, 'show']);
    Route::get('/student/course/detail/{id}', [StudentCourseController::class, 'showPage'])->middleware('coi');
});
