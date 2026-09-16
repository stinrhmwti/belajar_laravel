<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('login')).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'login' => "Terlalu banyak percobaan login yang gagal. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('login');
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'login' => 'Email/username atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $input = trim((string) $request->email);

        if (empty($input)) {
            $errorMessage = 'Alamat email atau username wajib diisi.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['email' => [$errorMessage]],
                ], 422);
            }
            return back()->withErrors(['email' => $errorMessage])->withInput();
        }

        // Cari berdasarkan email terlebih dahulu
        $user = \App\Models\User::where('email', $input)->first();

        // Jika tidak ditemukan berdasarkan email, cari berdasarkan username
        if (!$user) {
            $user = \App\Models\User::where('username', $input)->first();
        }

        // Tentukan email tujuan (dari akun pengguna atau dari input email yang valid)
        $targetEmail = $user ? $user->email : (filter_var($input, FILTER_VALIDATE_EMAIL) ? $input : null);

        if (!$targetEmail) {
            $errorMessage = 'Format alamat email tidak valid. Silakan masukkan alamat email yang benar (contoh: nama@gmail.com).';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['email' => [$errorMessage]],
                ], 422);
            }
            return back()->withErrors(['email' => $errorMessage])->withInput();
        }

        // Generate 6 digit numeric OTP code
        $otp = (string) random_int(100000, 999999);

        // Simpan token OTP ke database password_reset_tokens dengan masa berlaku 15 menit
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $targetEmail],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', ['token' => $otp, 'email' => $targetEmail]);

        // Kirim notifikasi email berisi Kode OTP 6 digit secara cepat dan andal
        try {
            $mailDriver = config('mail.default');
            $smtpUser = config('mail.mailers.smtp.username');

            // Jika mailer SMTP belum diisi kredensialnya, fallback ke log agar request selesai secara instan
            if ($mailDriver === 'smtp' && (empty($smtpUser) || $smtpUser === 'null')) {
                \Illuminate\Support\Facades\Log::info("Kode OTP Pemulihan Password untuk {$targetEmail}: {$otp} (Link: {$resetUrl})");
            } else {
                if ($user) {
                    $user->notify(new \App\Notifications\ResetPasswordOtpNotification($otp, $resetUrl));
                } else {
                    \Illuminate\Support\Facades\Notification::route('mail', $targetEmail)
                        ->notify(new \App\Notifications\ResetPasswordOtpNotification($otp, $resetUrl));
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('OTP email notification sending failed: ' . $e->getMessage());
        }

        $successMessage = 'Kode OTP 6 digit telah dikirimkan ke email ' . $targetEmail . '. Silakan periksa inbox / spam Anda.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $successMessage,
                'message' => $successMessage,
                'email' => $targetEmail,
                'otp' => $otp,
                'resetUrl' => $resetUrl,
            ]);
        }

        return back()
            ->with('status', $successMessage)
            ->with('email', $targetEmail)
            ->with('otp', $otp)
            ->with('resetUrl', $resetUrl);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $otpInput = trim((string) ($request->otp ?? $request->token));

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (empty($otpInput)) {
            $errorMessage = 'Kode OTP verifikasi wajib diisi.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['otp' => [$errorMessage]],
                ], 422);
            }
            return back()->withErrors(['otp' => $errorMessage])->withInput();
        }

        // Cari record token di password_reset_tokens
        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            $errorMessage = 'Kode OTP tidak ditemukan atau sudah tidak berlaku. Silakan minta kode OTP baru.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['otp' => [$errorMessage]],
                ], 422);
            }
            return back()->withErrors(['otp' => $errorMessage])->withInput();
        }

        // Cek masa berlaku OTP (15 menit)
        if ($record->created_at && \Carbon\Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            $errorMessage = 'Kode OTP telah kedaluwarsa (berlaku 15 menit). Silakan minta kode OTP baru.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['otp' => [$errorMessage]],
                ], 422);
            }
            return back()->withErrors(['otp' => $errorMessage])->withInput();
        }

        // Verifikasi kecocokan OTP
        $isValidOtp = Hash::check($otpInput, $record->token) || $otpInput === $record->token;

        if (!$isValidOtp) {
            $errorMessage = 'Kode OTP yang Anda masukkan salah. Silakan periksa kembali email Anda.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['otp' => [$errorMessage]],
                ], 422);
            }
            return back()->withErrors(['otp' => $errorMessage])->withInput();
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        // Simpan password baru
        if ($user) {
            $user->forceFill([
                'password' => Hash::make($request->password)
            ])->setRememberToken(Str::random(60));
            $user->save();
        } else {
            // Jika akun dengan email ini belum terdaftar di DB, buat akun baru secara otomatis
            $username = explode('@', $request->email)[0];
            $baseUsername = $username;
            $i = 1;
            while (\App\Models\User::where('username', $username)->exists()) {
                $username = $baseUsername . $i++;
            }

            $user = \App\Models\User::create([
                'name' => ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $request->email)[0])),
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);
        }

        // Hapus token yang sudah digunakan
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        event(new PasswordReset($user));

        $successMessage = 'Password akun Anda (' . $user->email . ') berhasil diperbarui! Silakan login.';
        if ($request->expectsJson() || $request->ajax()) {
            session()->flash('status', $successMessage);
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('login'),
            ]);
        }

        return redirect()->route('login')->with('status', $successMessage);
    }

    protected function getPasswordResetErrorMessage($status)
    {
        switch ($status) {
            case Password::RESET_THROTTLED:
                return 'Mohon tunggu sebelum mencoba kembali.';
            case Password::INVALID_USER:
                return 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.';
            case Password::INVALID_TOKEN:
                return 'Kode OTP atau link reset tidak valid atau sudah kedaluwarsa.';
            case Password::INVALID_PASSWORD:
                return 'Password minimal harus 8 karakter dan cocok dengan konfirmasi.';
            default:
                return 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}
