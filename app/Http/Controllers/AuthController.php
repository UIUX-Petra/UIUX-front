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
            $googleUser = Socialite::driver('google')->stateless()->user();
            if (!$googleUser) {
                return redirect()->route('loginOrRegist')->with('Error', 'Google authentication failed. Please try again!');
            }

            $email = strtolower($googleUser->getEmail());
            $name = $googleUser->getName();

            if (!preg_match('/@(john|peter)\.petra\.ac\.id$/', $email)) {
                return redirect()->route('loginOrRegist')->with('Error', 'Please use your Petra Christian University email to log in!');
            }

            $apiUrl = env('API_URL') . '/auth/socialite';
            $response = Http::post($apiUrl, [
                'name' => $name,
                'email' => $email,
                'secret' => env('API_SECRET')
            ]);

            if ($response->failed()) {
                $errorMessage = $response->json('message') ?? 'Login via Google failed. Please try again.';
                Log::error('Socialite API login error: ' . $errorMessage . ' Status: ' . $response->status() . ' Body: ' . $response->body());
                return redirect()->route('loginOrRegist')->with('Error', $errorMessage);
            }

            $responseData = $response->json();
            if (!isset($responseData['success']) || !$responseData['success'] || !isset($responseData['data'])) {
                Log::error('Socialite API login error: Invalid success or data structure. Resp: ' . $response->body());
                return redirect()->route('loginOrRegist')->with('Error', 'Invalid response from API after social login.');
            }

            $storedUser = $responseData['data'];
            if (!isset($storedUser['email'], $storedUser['name'], $storedUser['token'], $storedUser['id'])) {
                Log::error('Socialite API login error: Missing user data. Resp: ' . $response->body());
                return redirect()->route('loginOrRegist')->with('Error', 'Incomplete user data received from API.');
            }

            session([
                'email' => $storedUser['email'],
                'name' => $storedUser['name'],
                'token' => $storedUser['token'],
                'user_id' => $storedUser['id'],
                'reputation' => $storedUser['reputation'] ?? 0,
            ]);

            $url = session('url');
            if ($url) {
                session()->forget('url');
                return redirect()->to($url);
            }
            return redirect()->route('popular');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google Socialite InvalidStateException: ' . $e->getMessage());
            return redirect()->route('loginOrRegist')->with('Error', 'Login session expired or was invalid. Please try again.');
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::error('Google Socialite Guzzle ConnectException: ' . $e->getMessage());
            return redirect()->route('loginOrRegist')->with('Error', 'Could not connect to Google. Please check your internet connection and try again.');
        } catch (\Exception $e) {
            Log::error('Google Socialite general error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->route('loginOrRegist')->with('Error', 'An unexpected error occurred during Google login. Please try again.');
        }
    }

    public function handlePendingVerification(Request $request)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect()->route('loginOrRegist')->with('Error', 'Invalid verification link (missing token).');
        }

        $apiUrl = env('API_URL') . "/email/verify-pending/{$token}";
        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $responseData = $response->json();
            if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                $storedUser = $responseData['data'];
                session([
                    'email' => $storedUser['email'],
                    'name' => $storedUser['name'],
                    'token' => $storedUser['token'],
                    'user_id' => $storedUser['id'],
                    'reputation' => $storedUser['reputation'] ?? 0,
                ]);
                return redirect()->route('popular')->with('Success', $responseData['message'] ?? 'Email verified! You are now logged in.');
            } else {
                $errorMessage = $responseData['message'] ?? 'Verification succeeded but login failed.';
                return redirect()->route('loginOrRegist')->with('Error', $errorMessage);
            }
        }

        $errorMessage = $response->json('message') ?? 'Email verification failed.';
        if ($response->status() == 410) {
            $errorMessage .= ' Please try registering again.';
        }
        return redirect()->route('loginOrRegist')->with('Error', $errorMessage);
    }

    public function verifyEmail(Request $request)
    {
        $id = $request->query('id');
        $hash = $request->query('hash');

        if (!$id || !$hash) {
            return redirect()->route('loginOrRegist')->with('Error', 'Invalid verification link parameters!');
        }

        $queryString = http_build_query($request->query());
        $apiUrl = env('API_URL') . "/email/verify/{$id}/{$hash}?{$queryString}";

        $response = Http::get($apiUrl);

        if ($response->successful()) {
            return redirect()->route('loginOrRegist')->with('Success', $response->json('message') ?? 'Your email has been verified! Please login.');
        }

        $errorMessage = $response->json('message') ?? 'Email verification failed!';
        if ($response->status() === 403) {
            $errorMessage .= ' The link might be expired or invalid.';
        }
        return redirect()->route('loginOrRegist')->with('Error', $errorMessage);
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate(['email_to_resend' => 'required|email']);
        $userEmail = $request->input('email_to_resend');

        session()->flash('verification_email_sent_to', $userEmail);

        $apiUrlPending = env('API_URL') . '/email/resend-pending-verification';
        $responsePending = Http::post($apiUrlPending, ['email' => $userEmail]);

        if ($responsePending->successful()) {
            return redirect()->back()->with('Success', $responsePending->json('message') ?? 'A new verification link has been sent if a pending registration exists.');
        }

        if ($responsePending->status() == 404 || $responsePending->status() == 410) {
            $apiUrlDefault = env('API_URL') . '/email/verification-notification';
            $responseDefault = Http::post($apiUrlDefault, ['email' => $userEmail]);

            if ($responseDefault->successful()) {
                return redirect()->back()->with('Success', $responseDefault->json('message') ?? 'A new verification link has been sent.');
            }
            $errorMessage = $responseDefault->json('message') ?? 'Failed to resend verification email.';
            return redirect()->back()->with('Error', $errorMessage);
        }

        $errorMessage = $responsePending->json('message') ?? 'Could not resend verification email.';
        return redirect()->back()->with('Error', $errorMessage);
    }

    public function submitRegister(Request $request)
    {
        $apiUrl = env('API_URL') . '/register';
        $response = HTTP::post($apiUrl, [
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'secret' => env('API_SECRET'),
        ]);
        $resData = $response->json();
        return response()->json([
            'success' => $response->successful() && ($resData['success'] ?? false),
            'message' => $resData['message'] ?? 'An error occurred during registration.',
        ], $response->status());
    }

    public function manualLogin(Request $request)
    {
        $apiUrl = env('API_URL') . '/manualLogin';
        $response = HTTP::post($apiUrl, [
            'usernameOrEmail' => $request->get('usernameOrEmail'),
            'loginPassword' => $request->get('loginPassword')
        ]);

        if ($response->failed()) {
            $errorMessage = $response->json('message') ?? 'Login failed. Please check your credentials.';
            if ($response->status() === 403 && str_contains(strtolower($errorMessage), 'not verified')) {
                return redirect()->route('loginOrRegist')
                    ->with('Error', $errorMessage)
                    ->with('show_resend_verification_option', true)
                    ->with('email_for_resend', $request->get('usernameOrEmail'));
            }
            return redirect()->route('loginOrRegist')->with('Error', $errorMessage);
        }

        $responseData = $response->json();
        if (!isset($responseData['success']) || !$responseData['success'] || !isset($responseData['data'])) {
            return redirect()->route('loginOrRegist')->with('Error', 'Invalid response from API after login.');
        }
        $storedUser = $responseData['data'];
        if (!isset($storedUser['email'], $storedUser['name'], $storedUser['token'], $storedUser['id'])) {
            return redirect()->route('loginOrRegist')->with('Error', 'Incomplete user data from API.');
        }
        session([
            'email' => $storedUser['email'],
            'name' => $storedUser['name'],
            'token' => $storedUser['token'],
            'user_id' => $storedUser['id'],
            'reputation' => $storedUser['reputation'] ?? 0
        ]);

        $url = session('url');
        if ($url) {
            session()->forget('url');
            return redirect()->to($url);
        }
        return redirect()->route('popular');
    }

    public function showVerificationNotice(Request $request)
    {
        $email = $request->query('email');

        if (!$email && session()->has('verification_email_sent_to')) {
            $email = session()->get('verification_email_sent_to');
        }

        if ($email) {
            session()->flash('verification_email_sent_to', $email);
        }

        return view("verify-email", [
            'title' => 'Verify Your Email',
            'email' => $email
        ]);
    }

    public function logout(Request $request)
    {
        $token = session('token');
        if ($token) {
            try {
                Http::withToken($token)->post(env('API_URL') . '/logout');
            } catch (\Exception $e) {
                Log::warning('Failed to invalidate token on API during logout: ' . $e->getMessage());
            }
        }
        $request->session()->flush();
        return redirect()->route('loginOrRegist');
    }
}
