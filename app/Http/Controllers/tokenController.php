<?php

namespace App\Http\Controllers;

    use App\Models\RefreshToken;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Auth;

class tokenController extends Controller
{
    

        public function refreshToken(Request $request)
        {
            $request->validate([
                'refresh_token' => 'required|string',
            ]);
    
            $refreshToken = RefreshToken::where('token', $request->refresh_token)->first();
    
            if (!$refreshToken) {
                return response()->json(['message' => 'Invalid refresh token'], 401);
            }
    
            // تحقق من انتهاء صلاحية التوكن
            if (Carbon::now()->greaterThan(Carbon::parse($refreshToken->expires_at))) {
                $refreshToken->delete();  // امسح التوكن من القاعدة
                return response()->json(['message' => 'Refresh token expired'], 401);
            }
    
            $user = $refreshToken->user;
    
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            // انشئ توكن وصول جديد
            $newAccessToken = $user->createToken('auth_Token')->plainTextToken;
    
            return response()->json([
                'access_token' => $newAccessToken,
                'token_type' => 'Bearer',
            ]);
        }
    }
    
