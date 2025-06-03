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
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }



    public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // 🔍 البحث عن المستخدم بالبريد
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // ✨ إنشاء مستخدم جديد بحساب Google
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'email_verified_at' => now(),
                'is_google_account' => true,
            ]);
        } else {
            // ✅ لو موجود، تأكد أنه حساب Google
            if ($user->is_google_account !== true) {
return redirect()->away("http://localhost:3000/google-error?reason=not_google_account");

            }
        }

        // 🔑 إصدار Access Token
        $accessToken = $user->createToken('auth_Token')->plainTextToken;

        // 🔄 إصدار Refresh Token
        $plainRefreshToken = Str::random(64);
        RefreshToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => hash('sha256', $plainRefreshToken),
                'expires_at' => now()->addDays(7),
            ]
        );

        // 🔁 توجيه إلى الواجهة الأمامية مع التوكنات
        return redirect()->away("http://localhost:3000/google-success?access_token=$accessToken&refresh_token=$plainRefreshToken");

    } catch (\Exception $e) {
        return redirect()->away("http://localhost:3000/google-error?reason=googleCallBack_failled");
    }
}




}
