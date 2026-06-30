<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZoomMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZoomMeetingController extends Controller
{
    public function index($course_id)
    {
        $meetings = ZoomMeeting::where('course_id', $course_id)
            ->orderBy('meeting_time', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $meetings
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'meeting_time' => 'required|date',
            'zoom_link' => 'nullable|url',
            'manual_passcode' => 'nullable|string|max:255',
        ]);

        $zoomLink = $request->zoom_link;
        $passcode = $request->manual_passcode;

        if ($zoomLink && !$passcode) {
            $urlParts = parse_url($zoomLink);
            if (isset($urlParts['query'])) {
                parse_str($urlParts['query'], $queryParts);
                if (isset($queryParts['pwd'])) {
                    $passcode = $queryParts['pwd'];
                }
            }
        }

        // If no manual link is provided, attempt to auto-generate via API
        if (!$zoomLink) {
            $accountId = config('services.zoom.account_id');
            $clientId = config('services.zoom.client_id');
            $clientSecret = config('services.zoom.client_secret');

            if ($accountId && $clientId && $clientSecret) {
                try {
                    // Request Access Token from Zoom (Server-to-Server OAuth)
                    $tokenResponse = Http::withoutVerifying()->asForm()->withHeaders([
                        'Authorization' => 'Basic ' . base64_encode("$clientId:$clientSecret"),
                    ])->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id={$accountId}");

                    if ($tokenResponse->successful()) {
                        $accessToken = $tokenResponse->json()['access_token'];

                        // Create meeting
                        $meetingResponse = Http::withoutVerifying()->withToken($accessToken)->post('https://api.zoom.us/v2/users/me/meetings', [
                            'topic' => $request->title,
                            'type' => 2, // Scheduled meeting
                            'start_time' => date('Y-m-d\TH:i:s', strtotime($request->meeting_time)),
                            'duration' => 60,
                            'timezone' => 'Asia/Jakarta',
                            'settings' => [
                                'join_before_host' => true,
                                'jbh_time' => 0,
                                'mute_upon_entry' => true,
                                'waiting_room' => false
                            ]
                        ]);

                        if ($meetingResponse->successful()) {
                            $zoomLink = $meetingResponse->json()['join_url'];
                            $passcode = $meetingResponse->json()['password'];
                        } else {
                            $errData = $meetingResponse->json();
                            return response()->json([
                                'success' => false,
                                'message' => 'API Zoom gagal membuat rapat: ' . ($errData['message'] ?? 'Unknown Error')
                            ], 422);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal autentikasi ke Zoom API: ' . $tokenResponse->body()
                        ], 422);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Koneksi ke Zoom API error: ' . $e->getMessage()
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kredensial otomatis belum lengkap (.env membutuhkan ZOOM_ACCOUNT_ID). Silakan isi Tautan Zoom Manual untuk melanjutkan pengujian.'
                ], 422);
            }
        }

        $meeting = ZoomMeeting::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
            'zoom_link' => $zoomLink,
            'passcode' => $passcode,
            'meeting_time' => $request->meeting_time,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan Zoom berhasil dijadwalkan!',
            'data' => $meeting
        ], 201);
    }

    public function destroy($id)
    {
        $meeting = ZoomMeeting::findOrFail($id);
        $meeting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan Zoom berhasil dihapus.'
        ]);
    }

    public function endMeeting($id)
    {
        $meeting = ZoomMeeting::findOrFail($id);
        $meeting->status = 'ended';
        $meeting->save();

        return response()->json([
            'success' => true,
            'message' => 'Sesi Zoom telah diakhiri.'
        ]);
    }

    public function allMeetings()
    {
        $meetings = ZoomMeeting::with('course')->orderBy('meeting_time', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $meetings
        ]);
    }
}
