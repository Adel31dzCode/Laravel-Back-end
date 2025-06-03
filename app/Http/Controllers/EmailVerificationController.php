<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\VerificationCodeMail; // ✅ استدعاء الـ Mailable الجديد

class EmailVerificationController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $code = rand(100000, 999999); // توليد كود من 6 أرقام
        $email = $request->email;

        // حذف أي كود قديم لنفس الإيميل
        DB::table('email_verifications')->where('email', $email)->delete();

        // حفظ الكود الجديد
        DB::table('email_verifications')->insert([
            'email' => $email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ إرسال الكود عبر بريد منسق باستخدام Mailable
        Mail::to($email)->send(new VerificationCodeMail($code));

        return response()->json(['message' => 'Verification code sent successfully.'], 200);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);

        $record = DB::table('email_verifications')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Invalid code.'], 400);
        }

        if (Carbon::now()->greaterThan(Carbon::parse($record->expires_at))) {
            return response()->json(['message' => 'Code expired.'], 401);
        }

        // حذف الكود بعد التحقق
        DB::table('email_verifications')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }
}
