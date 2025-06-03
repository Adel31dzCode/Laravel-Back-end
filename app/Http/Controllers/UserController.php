<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\RefreshToken;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(UserRequest $request)
    {
        try {
            $validated = $request->validated();
    
            $user = User::create([
                "name" => $validated['name'],
                "email" => $validated['email'],
                "password" => Hash::make($validated['password']),
            ]);
    
            // Access Token
            $accessToken = $user->createToken("auth_Token")->plainTextToken;
    
            // Refresh Token
            $plainRefreshToken = Str::random(64);
            RefreshToken::create([
                'user_id' => $user->id,
                'token' => hash('sha256', $plainRefreshToken),
                'expires_at' => now()->addDays(7),
            ]);
    
            return response()->json([
                'message' => 'User Created Successfully!',
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $plainRefreshToken,
            ], 201);
    
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email|min:5|max:28',
            'password' => 'required|string|min:8|max:16',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $user = User::where('email', $request->email)->first();
    
        // ⛔️ إذا كان هذا الحساب مرتبط بـ Google
        if ($user->is_google_account) {
            return response()->json([
                'message' => 'هذا البريد مرتبط بحساب Google، يُرجى تسجيل الدخول باستخدام زر Google.',
            ], 403);
        }
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
            ], 401);
        }
    
        // Access Token
        $accessToken = $user->createToken("auth_Token")->plainTextToken;
    
        // Refresh Token
        $refreshToken = Str::random(64);
        $expiresAt = now()->addDays(7);
    
        RefreshToken::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $refreshToken),
            'expires_at' => $expiresAt,
        ]);
    
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);
    }
    

    

}
