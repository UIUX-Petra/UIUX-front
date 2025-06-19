<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getReportReasons(){
        $api_url = env('API_URL') . '/report-reasons';
        $response = Http::withToken(session('token'))->get($api_url);
        $reasons = json_decode($response, true);
        return $reasons;
    }

    public function submitReport(Request $request)
    {
        // 1. Validasi input dari browser
        $validated = $request->validate([
            'reportable_id' => 'required|uuid',
            'reportable_type' => 'required|string|in:question,answer,comment',
            'report_reason_id' => 'required|uuid',
        ]);

        // 2. Siapkan URL dan data untuk dikirim ke API Server
        $apiUrl = env('API_URL') . '/reports'; // Endpoint API yang sesungguhnya

        // 3. Panggil API Server menggunakan Http client
        $response = Http::withToken(session('token'))->post($apiUrl, [
            'reportable_id' => $validated['reportable_id'],
            'reportable_type' => $validated['reportable_type'],
            'report_reason_id' => $validated['report_reason_id'],
            // 'additional_notes' => 'Jika Anda ingin menambahkannya nanti'
        ]);

        // 4. Teruskan response dari API kembali ke browser
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => $response->json()['message'] ?? 'Report submitted successfully.',
            ]);
        } else {
            // Jika API gagal (misal: sudah pernah lapor), teruskan pesan errornya
            $errorMessage = $response->json()['message'] ?? 'Failed to submit report.';
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], $response->status()); // Teruskan juga status code errornya
        }
    }
}
