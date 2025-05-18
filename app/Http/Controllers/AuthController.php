<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function loginOrRegist(Request $request)
    {
        return view("loginOrRegist", [
            'title' => 'LoginOrRegist',
        ]);
    }
    public function googleAuth()
    {
        return Socialite::driver('google')->redirect();
    }

    public function processLogin()
    {
        try {
            $user = Socialite::driver('google')->user();
            if (!$user) {
                return redirect()->route('loginOrRegist')->with('Error', 'Please try to log in again!');
            }

            $email = strtolower($user->getEmail());
            $name = $user->getName();

            if (!str_ends_with($email, '@john.petra.ac.id')) {
                return redirect()->route('loginOrRegist')->with('Error', 'Please use your Petra Christian University email to log in!');
            }

            $apiUrl = env('API_URL') . '/login';

            $response = Http::post($apiUrl, [
                'name' => $name,
                'email' => $email,
                'password' => env('API_SECRET')
            ]);

            if ($response->failed()) {
                return redirect()->route('loginOrRegist')->with('Error', 'There was an issue with the login request.');
            }

            $responseData = $response->json();
            $storedUser = $responseData['data'];
            if (!isset($storedUser['email'], $storedUser['name'], $storedUser['token'])) {
                return redirect()->route('loginOrRegist')->with('Error', 'Invalid response structure from the API.');
            }
            session([
                'email' => $storedUser['email'],
                'name' => $storedUser['name'],
                'token' => $storedUser['token']
            ]);

            // Dump and die to inspect session data
            $url = session('url');
            if ($url) {
                session()->forget('url');
                return redirect()->to($url);
            }
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return redirect()->route('loginOrRegist')->with('Error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function verifyEmail(Request $request)
    {
        // Extract id and hash from query parameters
        $id = $request->query('id');
        $hash = $request->query('hash');

        if (!$id || !$hash) {
            return redirect()->route('loginOrRegist')->with('Error', 'Invalid verification link!');
        }

        // Construct the API verification URL
        $apiUrl = env('API_URL') . "/email/verify/{$id}/{$hash}";

        // Make a GET request to the API's verification endpoint
        $response = Http::get($apiUrl);

        if ($response->successful()) {
            return redirect()->route('profile')->with('Success', 'Your email has been verified!');
        }

        // Handle specific error messages
        $status = $response->status();
        $errorMessage = $response->json('message') ?? 'Email verification failed!';

        if ($status === 403) {
            // Link expired or invalid
            return redirect()->route('loginOrRegist')->with('Error', 'Verification link has expired. Please request a new verification email.');
        }

        return redirect()->route('loginOrRegist')->with('Error', $errorMessage);
    }

    public function resendVerificationEmail(Request $request)
    {
        $response = Http::post(env('API_URL') . '/email/verification-notification');

        if ($response->successful()) {
            return redirect()->back()->with('Success', 'A new verification link has been sent to your email address.');
        }

        $errorMessage = $response->json('message') ?? 'Failed to resend verification email.';
        return redirect()->back()->with('Error', $errorMessage);
    }





    public function submitRegister(Request $request)
    {
        $apiUrl = env('API_URL') . '/register';
        $response = HTTP::post($apiUrl, [
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);
        $res = $response->json();
        return response()->json([
            'ok' => isset($res['success']) ? $res['success'] : false,
            'message' => $res['message'] ?? 'An error occurred during registration.',
        ], $response->status());
    }

    public function manualLogin(Request $request)
    {
        Log::info($request->all());
        $apiUrl = env('API_URL') . '/manualLogin';
        $response = HTTP::post($apiUrl, [
            'usernameOrEmail' => $request->get('usernameOrEmail'),
            'loginPassword' => $request->get('loginPassword')
        ]);
        Log::info($response);
        if ($response->failed()) {
            return redirect()->route('loginOrRegist')->with('Error', 'There was an issue with the login request.');
        }

        $responseData = $response->json();
        $storedUser = $responseData['data'];
        if (!isset($storedUser['email'], $storedUser['name'], $storedUser['token'], $storedUser['reputation'])) {
            return redirect()->route('loginOrRegist')->with('Error', 'Invalid response structure from the API.');
        }
        session([
            'email' => $storedUser['email'],
            'name' => $storedUser['name'],
            'token' => $storedUser['token'],
            'reputation' => $storedUser['reputation']
        ]);
        $url = session('url');
        if ($url) {
            session()->forget('url');
            return redirect()->to($url);
        }
        Log::info(session()->all());
        return redirect()->route('home');
    }


    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('loginOrRegist');
    }
}
