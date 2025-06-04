<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RefreshToken;

class GoogleAuthController extends Controller
{
    // ✅ متغير ثابت يمكن تغييره بسهولة
    private $url_page = "https://react-front-end-six.vercel.app";

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'is_google_account' => true,
                ]);
            } else {
                if ($user->is_google_account !== true) {
                    return redirect()->away("{$this->url_page}/google-error?reason=not_google_account");
                }
            }

            $accessToken = $user->createToken('auth_Token')->plainTextToken;

            $plainRefreshToken = Str::random(64);
            RefreshToken::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'token' => hash('sha256', $plainRefreshToken),
                    'expires_at' => now()->addDays(7),
                ]
            );

            return redirect()->away("{$this->url_page}/google-success?access_token=$accessToken&refresh_token=$plainRefreshToken");

        } catch (\Exception $e) {
            return redirect()->away("{$this->url_page}/google-error?reason=Google_CallBack_Failed");
        }
    }
}
