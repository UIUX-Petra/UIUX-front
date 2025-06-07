<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    public function getAllTags()
    {
        $api_url = env('API_URL') . '/tags';
        $response = Http::withToken(session('token'))->get($api_url);
        $tags = json_decode($response, true);
        $tags = $tags['data'];
        foreach ($tags as &$tag) {
            $tag['questions'] = (is_array($tag['group_question']) && $tag['group_question'] !== null)
                ? count($tag['group_question'])
                : 0;
        }
        // dd($tags);
        return $tags;
    }

    public function getTagOnly()
    {
        $api_url = env('API_URL') . '/tagOnly';
        $response = Http::withToken(session('token'))->get($api_url);
        $tags = json_decode($response, true);
        return $tags;
    }
    public function getTagLeaderboard($tagId)
    {
        $api_url = env('API_URL') . '/getLeaderboardByTag/' . $tagId;

        try {
            $response = Http::withToken(session('token'))->get($api_url);

            if (!$response->successful()) {
                Log::error("Failed to fetch leaderboard for tag: " . $tagId);
                return response()->json(['error' => 'Failed to fetch leaderboard data'], 500);
            }

            $responseData = $response->json();

            return response()->json($responseData['data']);
        } catch (\Exception $e) {
            Log::error('Error fetching leaderboard: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching leaderboard data'], 500);
        }
    }
}
