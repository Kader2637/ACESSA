<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\StudentClassroomRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // === TEACHER ENDPOINTS ===

    public function createSession(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'title' => 'required|string|max:100',
            'duration' => 'required|integer|min:1|max:1440', // duration in minutes
        ]);

        // Generate unique code and token
        do {
            $code = strtoupper(Str::random(6));
        } while (AttendanceSession::where('code', $code)->exists());

        do {
            $token = Str::random(32);
        } while (AttendanceSession::where('token', $token)->exists());

        $session = AttendanceSession::create([
            'classroom_id' => $request->classroom_id,
            'title' => $request->title,
            'code' => $code,
            'token' => $token,
            'valid_until' => Carbon::now()->addMinutes((int) $request->duration),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Sesi absensi berhasil dibuat.',
            'data' => $session
        ]);
    }

    public function getSessions($classId)
    {
        $sessions = AttendanceSession::where('classroom_id', $classId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $sessions
        ]);
    }

    public function getSessionDetails($sessionId)
    {
        $session = AttendanceSession::with('classroom')->findOrFail($sessionId);
        
        // Get all active/accepted students in this classroom
        $enrolledStudents = StudentClassroomRelation::where('classroom_id', $session->classroom_id)
            ->where('status', 'accept')
            ->with('user')
            ->get();

        // Get records for this session
        $records = AttendanceRecord::where('attendance_session_id', $sessionId)
            ->get()
            ->keyBy('user_id');

        $data = $enrolledStudents->map(function ($relation) use ($records) {
            $student = $relation->user;
            $record = $records->get($student->id);

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'profile' => $student->image, // check user profile image
                'status' => $record ? 'Hadir' : 'Belum Hadir',
                'scanned_at' => $record ? $record->scanned_at->setTimezone('Asia/Jakarta')->format('H:i:s') : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'session' => [
                'id' => $session->id,
                'title' => $session->title,
                'code' => $session->code,
                'token' => $session->token,
                'valid_until' => $session->valid_until->toIso8601String(),
                'is_expired' => $session->isExpired(),
            ],
            'data' => $data
        ]);
    }

    // === STUDENT ENDPOINTS ===

    // Manual code submission
    public function submitByCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $student = Auth::user();
        $code = strtoupper($request->code);

        // Find active session
        $session = AttendanceSession::where('classroom_id', $request->classroom_id)
            ->where('code', $code)
            ->first();

        if (!$session) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode absensi tidak valid untuk kelas ini.'
            ], 404);
        }

        if ($session->isExpired()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode absensi telah kedaluwarsa.'
            ], 403);
        }

        // Check enrollment
        $isEnrolled = StudentClassroomRelation::where('classroom_id', $request->classroom_id)
            ->where('user_id', $student->id)
            ->where('status', 'accept')
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak terdaftar aktif di kelas ini.'
            ], 403);
        }

        // Check if already scanned
        $alreadyRecorded = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('user_id', $student->id)
            ->exists();

        if ($alreadyRecorded) {
            return response()->json([
                'status' => 'success', // return success but note it's already done
                'message' => 'Anda sudah mengisi absensi untuk sesi ini.'
            ]);
        }

        // Record attendance
        AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'user_id' => $student->id,
            'scanned_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi berhasil dicatat secara manual.'
        ]);
    }

    // Student personal attendance history for a classroom
    public function getStudentAttendance($classId)
    {
        $student = Auth::user();

        // Get all sessions
        $sessions = AttendanceSession::where('classroom_id', $classId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get student's records
        $records = AttendanceRecord::whereIn('attendance_session_id', $sessions->pluck('id'))
            ->where('user_id', $student->id)
            ->get()
            ->keyBy('attendance_session_id');

        $data = $sessions->map(function ($session) use ($records) {
            $record = $records->get($session->id);

            return [
                'id' => $session->id,
                'title' => $session->title,
                'code' => $session->code,
                'valid_until' => $session->valid_until->toIso8601String(),
                'status' => $record ? 'Hadir' : ($session->isExpired() ? 'Tidak Hadir' : 'Belum Absen'),
                'scanned_at' => $record ? $record->scanned_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : null,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // Web landing page for QR code scanning
    public function scanLanding($token)
    {
        $student = Auth::user();
        if (!$student) {
            // Should be guarded by web auth middleware, but just in case
            return redirect()->route('login');
        }

        // Find session
        $session = AttendanceSession::with('classroom')->where('token', $token)->first();

        if (!$session) {
            return view('pages.student.attendance.scan_result', [
                'status' => 'error',
                'message' => 'Tautan kode QR absensi tidak valid.',
                'classroom' => null
            ]);
        }

        $classroom = $session->classroom;

        if ($session->isExpired()) {
            return view('pages.student.attendance.scan_result', [
                'status' => 'error',
                'message' => 'Batas waktu absensi sesi ini telah kedaluwarsa.',
                'classroom' => $classroom
            ]);
        }

        // Check if student is enrolled in classroom
        $isEnrolled = StudentClassroomRelation::where('classroom_id', $classroom->id)
            ->where('user_id', $student->id)
            ->where('status', 'accept')
            ->exists();

        if (!$isEnrolled) {
            return view('pages.student.attendance.scan_result', [
                'status' => 'error',
                'message' => 'Anda tidak terdaftar aktif di kelas ' . $classroom->name . '.',
                'classroom' => $classroom
            ]);
        }

        // Check if already checked in
        $alreadyRecorded = AttendanceRecord::where('attendance_session_id', $session->id)
            ->where('user_id', $student->id)
            ->exists();

        if ($alreadyRecorded) {
            return view('pages.student.attendance.scan_result', [
                'status' => 'warning',
                'message' => 'Anda sudah mengisi absensi untuk sesi "' . $session->title . '" sebelumnya.',
                'classroom' => $classroom,
                'session' => $session
            ]);
        }

        // Record attendance
        AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'user_id' => $student->id,
            'scanned_at' => Carbon::now(),
        ]);

        return view('pages.student.attendance.scan_result', [
            'status' => 'success',
            'message' => 'Absensi Anda untuk sesi "' . $session->title . '" berhasil dicatat!',
            'classroom' => $classroom,
            'session' => $session
        ]);
    }
}
