<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getReportReasons()
    {
        $api_url = env('API_URL') . '/report-reasons';
        $response = Http::withToken(session('token'))->get($api_url);
        $reasons = json_decode($response, true);
        return $reasons;
    }

    public function submitReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'reportable_id' => 'required|uuid',
                'reportable_type' => 'required|string|in:question,answer,comment',
                'report_reason_id' => 'required|uuid',
            ]);

            $apiUrl = env('API_URL') . '/reports';

            $response = Http::withToken(session('token'))->post($apiUrl, $validated);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => $response->json()['message'] ?? 'Report submitted successfully.',
                ]);
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to submit report due to a server issue.';
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], $response->status());
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An internal server error occurred. Please try again later.'
            ], 500);
        }
    }
}
