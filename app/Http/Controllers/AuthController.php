<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.auth');
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_hp' => 'required|string|min:10',
            ]);
            $noHp = $validated['no_hp'];
            $cleanNoHp = Str::startsWith($noHp, '0') ? '62' . substr($noHp, 1) : $noHp;

            if (!str_ends_with($cleanNoHp, '@c.us')) {
                $cleanNoHp .= '@c.us';
            }

            if ($request->type == 'update') {
                $cekNoHp = User::where('no_hp', $cleanNoHp)->first();
            } else {
                $cekNoHp = User::firstOrCreate(['no_hp' => $cleanNoHp]);
            }

            $otpCode = rand(100000, 999999);
            $cekNoHp->update([
                'otp' => $otpCode,
                'expired' => now()->addMinutes(1)
            ]);
            session(['no_hp' => $cekNoHp->no_hp]);

            $message = <<<MSG
            🔐 Kode OTP kamu:

            *{$cekNoHp->otp}*

            ⚠️ *PENTING:* Berlaku selama 1 menit

            ⚠️ Jangan bagikan kode ini kepada siapapun, termasuk pihak yang mengaku dari layanan kami.
            MSG;

            Helper::balasPesanUser($cekNoHp->no_hp, $message);

            return response()->json([
                'message' => 'OTP sent successfully',
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function otpForm()
    {
        $noHp = session('no_hp');
        if (!$noHp) {
            return view('auth.auth')->with('error', 'No HP tidak ditemukan');
        }
        return view('auth.otp', compact('noHp'));
    }

    public function otp(Request $request)
    {
        try {
            $validated = $request->validate([
                'otp' => 'required|numeric|digits:6',
                'no_hp' => 'required|string|min:10',
            ]);
            $noHp = $validated['no_hp'];
            $otp = $validated['otp'];

            if (!str_ends_with($noHp, '@c.us')) {
                $noHp .= '@c.us';
            }

            $cekOtp = User::where('no_hp', $noHp)->where('otp', $otp)->where('expired', '>', now())->first();
            if (!$cekOtp) {
                return response()->json([
                    'message' => 'OTP tidak valid atau telah kedaluwarsa.',
                    'status' => false
                ]);
            }

            Auth::login($cekOtp);

            $cekOtp->update([ 'otp' => null, 'expired' => null ]);

            return response()->json([
                'message' => 'OTP valid',
                'status' => true
            ]);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.loginForm');
    }
}
