<?php

use App\Http\Controllers\Api\CourseTeacherController;
use App\Http\Controllers\Api\TaskCourseController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher')->middleware(['auth', CheckRole::class . ':teacher'])->group(function () {

    // ── Dashboard ────────────────────────────────────────
    Route::get('/', function () {
        return view('pages.teacher.dashboard.index');
    })->name('teacher');

    // ── Classroom ────────────────────────────────────────
    Route::prefix('classroom')->group(function () {
        Route::get('/', function () {
            return view('pages.teacher.class.class');
        })->name('classroom.teacher');

        Route::get('/detail', function () {
            return view('pages.teacher.detailClass.detailClass');
        })->name('classroom.detail');
    });

    // ── Tugas & Assessment ───────────────────────────────
    Route::get('/task', function () {
        return view('pages.teacher.task.assignmentAssessment');
    })->name('task.assignmentAssessment');

    Route::get('/detailTask/{taskCourse}', [TaskCourseController::class, 'teacher'])->name('task.detailTask');

    // ── Riwayat Tugas (Halaman Baru) ─────────────────────
    Route::get('/task/history', function () {
        return view('pages.teacher.task.history');
    })->name('teacher.task.history');

    // ── Riwayat Zoom (Halaman Baru) ──────────────────────
    Route::get('/zoom/history', function () {
        return view('pages.teacher.zoom.history');
    })->name('teacher.zoom.history');

    // ── Statistik (Halaman Baru) ─────────────────────────
    Route::get('/statistics', function () {
        return view('pages.teacher.statistics.index');
    })->name('teacher.statistics');

    // ── Profil (Halaman Baru) ────────────────────────────
    Route::get('/profile', function () {
        return view('pages.teacher.profile.index');
    })->name('teacher.profile');

    Route::post('/profile/update', [UserController::class, 'profileUpdate'])->name('teacher.profile.update');
});

// ── Public teacher course routes ─────────────────────────
Route::middleware(['auth', CheckRole::class . ':teacher'])->group(function () {
    Route::get('/teacher/classroom/course/{id}', [CourseTeacherController::class, 'show']);
    Route::get('/teacher/course/detail/{id}', [CourseTeacherController::class, 'showPage'])->middleware('coi');
});
