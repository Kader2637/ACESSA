<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    /**
     * Generate signature for Zoom Meeting SDK
     */
    public function generateSignature(Request $request)
    {
        $request->validate([
            'meeting_number' => 'required',
            'role' => 'required|integer', // 0 = attendee, 1 = host
        ]);

        $sdkKey = env('ZOOM_SDK_KEY');
        $sdkSecret = env('ZOOM_SDK_SECRET');

        // Fallback for local testing if not configured
        if (empty($sdkKey) || empty($sdkSecret)) {
            $sdkKey = 'default_mock_key';
            $sdkSecret = 'default_mock_secret_key_testing_only';
        }

        $meetingNumber = $request->input('meeting_number');
        $role = $request->input('role');

        $iat = time() - 30; // backdate 30 secs to account for clock skew
        $exp = $iat + 86400; // expires in 24 hours
        $tokenExp = $exp;

        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'sdkKey' => $sdkKey,
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => $iat,
            'exp' => $exp,
            'tokenExp' => $tokenExp
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $sdkSecret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return response()->json([
            'signature' => $jwt,
            'sdkKey' => $sdkKey
        ], 200);
    }
}
