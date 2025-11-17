<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Muzakki;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    // Di method login() setelah Auth::login($user)
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'muzakki') {
            $muzakki = Muzakki::where('user_id', $user->id)->first();

            if ($muzakki && (empty($muzakki->campaign_url) || !$muzakki->campaign_url)) {
                $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
                $muzakki->save();
            }
        }
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Verify reCAPTCHA v3 token
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return back()->withErrors(['email' => 'Validasi reCAPTCHA diperlukan.'])->withInput();
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);
            $verification = $response->json();

            if (!($verification['success'] ?? false)) {
                return back()->withErrors(['email' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
            }

            // Optional checks for v3
            $score = (float) ($verification['score'] ?? 0);
            $action = $verification['action'] ?? null;
            $threshold = (float) config('services.recaptcha.threshold', 0.5);
            if ($score < $threshold || ($action && $action !== 'login')) {
                return back()->withErrors(['email' => 'Aktivitas mencurigakan terdeteksi. Coba lagi.'])->withInput();
            }
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Layanan reCAPTCHA tidak tersedia. Coba lagi.'])->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }

            // Check if user has muzakki role
            if ($user->role !== 'muzakki') {
                Auth::logout();
                return back()->withErrors(['email' => 'Halaman ini hanya untuk muzakki. Silakan gunakan halaman login admin.']);
            }

            // Check if user has 2FA enabled
            if ($user->hasTwoFactorEnabled()) {
                // Store user ID in session and logout
                $request->session()->put('login.id', $user->id);
                Auth::logout();
                
                // Redirect to 2FA verification page
                return redirect()->route('two-factor.verify');
            }

            // Generate campaign URL if not exists
            if ($user->role === 'muzakki') {
                $muzakki = Muzakki::where('user_id', $user->id)->first();

                if ($muzakki && (empty($muzakki->campaign_url) || !$muzakki->campaign_url)) {
                    $muzakki->campaign_url = url('/campaigner/' . $muzakki->email);
                    $muzakki->save();
                }

                // Cek apakah ini login pertama kali (belum ada notifikasi account)
                if ($muzakki) {
                    $hasAccountNotification = \App\Models\Notification::where('muzakki_id', $muzakki->id)
                        ->where('type', 'account')
                        ->exists();

                    // Jika belum ada notifikasi account, berarti ini login pertama kali
                    if (!$hasAccountNotification) {
                        try {
                            \App\Models\Notification::createAccountNotification($user, 'profile', $muzakki);
                            Log::info('Welcome notification created for first login', [
                                'user_id' => $user->id,
                                'muzakki_id' => $muzakki->id
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to create welcome notification on first login: ' . $e->getMessage());
                        }
                    }
                }
            }

            $request->session()->regenerate();

            // Redirect muzakki users to home page
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }

            // Check if user has admin role
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses ke halaman admin.']);
            }

            $request->session()->regenerate();

            // Redirect to appropriate dashboard based on role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function showRegister()
    {
        // Get email from session if available (for pre-filling)
        $prefillEmail = session('registered_email', '');

        return view('auth.register', compact('prefillEmail'));
    }

    public function register(Request $request)
    {
        // Verify reCAPTCHA v3 token
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return back()->withErrors(['email' => 'Validasi reCAPTCHA diperlukan.'])->withInput();
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);
            $verification = $response->json();

            if (!($verification['success'] ?? false)) {
                return back()->withErrors(['email' => 'Verifikasi reCAPTCHA gagal.'])->withInput();
            }

            // Optional checks for v3
            $score = (float) ($verification['score'] ?? 0);
            $action = $verification['action'] ?? null;
            $threshold = (float) config('services.recaptcha.threshold', 0.5);
            if ($score < $threshold || ($action && $action !== 'register')) {
                return back()->withErrors(['email' => 'Aktivitas mencurigakan terdeteksi. Coba lagi.'])->withInput();
            }
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Layanan reCAPTCHA tidak tersedia. Coba lagi.'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users', // Tambahkan unique validation
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.', // Custom error message
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Gabungkan kode negara dengan nomor telepon
            $fullPhone = null;
            if ($request->phone) {
                $countryCode = $request->country_code ?? '+62';
                // Hapus karakter + dari country code
                $countryCode = str_replace('+', '', $countryCode);
                // Hapus leading zero jika ada
                $phone = ltrim($request->phone, '0');
                $fullPhone = $countryCode . $phone;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'muzakki',
                'is_active' => true,
                'phone' => $fullPhone,
            ]);

            // Generate campaign URL
            $campaignUrl = url('/campaigner/' . $request->email);

            $muzakki = Muzakki::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone ?? null,
                    'nik' => $request->nik ?? null,
                    'gender' => $request->gender ?? null,
                    'address' => $request->address ?? null,
                    'city' => $request->city ?? null,
                    'province' => $request->province ?? null,
                    'occupation' => $request->occupation ?? null,
                    'monthly_income' => $request->monthly_income ?? null,
                    'date_of_birth' => $request->date_of_birth ?? null,
                    'user_id' => $user->id,
                    'is_active' => true,
                    'campaign_url' => $campaignUrl, // Add campaign URL
                ]
            );

            // Refresh user relationship untuk memastikan muzakki ter-link
            $user->refresh();

            // Send welcome email to new muzakki
            try {
                Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
            } catch (\Exception $e) {
                // Log error but don't stop registration process
                Log::error('Failed to send welcome email: ' . $e->getMessage());
            }

            // Buat notifikasi "Selamat Datang" untuk user baru
            try {
                // Pastikan muzakki sudah ter-link dengan user
                if ($muzakki && $muzakki->user_id === $user->id) {
                    // Pass muzakki langsung untuk memastikan notifikasi menggunakan muzakki yang benar
                    \App\Models\Notification::createAccountNotification($user, 'profile', $muzakki);
                    Log::info('Welcome notification created for new user', [
                        'user_id' => $user->id,
                        'muzakki_id' => $muzakki->id
                    ]);
                } else {
                    Log::warning('Muzakki not properly linked to user during registration', [
                        'user_id' => $user->id,
                        'muzakki_id' => $muzakki->id ?? null,
                        'muzakki_user_id' => $muzakki->user_id ?? null
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to create welcome notification: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'muzakki_id' => $muzakki->id ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            session(['registered_email' => $request->email]);

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk konfirmasi.');
        } catch (\Exception $e) {
            if (isset($user)) {
                $user->delete();
            }

            return back()->withErrors([
                'email' => 'Registrasi gagal: ' . $e->getMessage(),
            ])->withInput();
        }
    }



    public function logout(Request $request)
    {
        // Ambil data user sebelum logout
        $user = Auth::user();
        $role = $user ? $user->role : null;

        // Logout pengguna
        Auth::logout();

        // Invalidate session dan regenerate token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect berdasarkan role
        if ($role === 'admin') {
            return redirect('/admin')->with('success', 'Anda telah berhasil logout.');
        }

        // Redirect dengan pesan sukses
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    // Handle Firebase authentication
    public function firebaseLogin(Request $request)
    {
        $request->validate([
            'uid' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        try {
            // Check if user exists or create new one
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => Hash::make(uniqid()), // Generate random password for Firebase users
                    'role' => 'muzakki',
                    'is_active' => true,
                    'phone' => $request->phone ?? null,
                ]
            );

            // Check if this is a new user (just created)
            $isNewUser = $user->wasRecentlyCreated;

            // Update or create muzakki profile
            // Generate campaign URL
            $campaignUrl = url('/campaigner/' . $request->email);

            $muzakki = Muzakki::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'phone' => $request->phone ?? null,
                    'user_id' => $user->id,
                    'is_active' => true,
                    'campaign_url' => $campaignUrl, // Add campaign URL
                ]
            );

            // Refresh user relationship untuk memastikan muzakki ter-link
            $user->refresh();

            // Send welcome email to new users registered via Firebase
            if ($isNewUser) {
                try {
                    Mail::to($user->email)->send(new \App\Mail\WelcomeMail($user));
                    Log::info('Welcome email sent to Firebase user: ' . $user->email);
                } catch (\Exception $e) {
                    // Log error but don't stop login process
                    Log::error('Failed to send welcome email to Firebase user: ' . $e->getMessage());
                }

                // Buat notifikasi "Selamat Datang" untuk user baru
                try {
                    // Pastikan muzakki sudah ter-link dengan user
                    if ($muzakki && $muzakki->user_id === $user->id) {
                        // Pass muzakki langsung untuk memastikan notifikasi menggunakan muzakki yang benar
                        \App\Models\Notification::createAccountNotification($user, 'profile', $muzakki);
                        Log::info('Welcome notification created for Firebase user', [
                            'user_id' => $user->id,
                            'muzakki_id' => $muzakki->id
                        ]);
                    } else {
                        Log::warning('Muzakki not properly linked to user', [
                            'user_id' => $user->id,
                            'muzakki_id' => $muzakki->id ?? null,
                            'muzakki_user_id' => $muzakki->user_id ?? null
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to create welcome notification for Firebase user: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'muzakki_id' => $muzakki->id ?? null,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            if (!$user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ], 403);
            }

            if ($user->role !== 'muzakki') {
                return response()->json([
                    'success' => false,
                    'message' => 'Login Google hanya tersedia untuk akun muzakki.'
                ], 403);
            }

            // Handle 2FA requirement
            if ($user->hasTwoFactorEnabled()) {
                $request->session()->put('login.id', $user->id);
                Auth::logout();

                return response()->json([
                    'success' => true,
                    'two_factor_required' => true,
                    'redirect' => route('two-factor.verify'),
                    'message' => 'Autentikasi dua faktor diperlukan.'
                ]);
            }

            // Log in the user
            Auth::login($user);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'redirect' => '/',
                'message' => 'Login berhasil.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Verify reCAPTCHA v3 token (optional - don't block if it fails)
        $recaptchaToken = $request->input('g-recaptcha-response');
        if ($recaptchaToken && config('services.recaptcha.secret_key')) {
            try {
                $verification = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $recaptchaToken,
                    'remoteip' => $request->ip(),
                ])->json();

                if (!($verification['success'] ?? false)) {
                    Log::warning('reCAPTCHA verification failed for password reset', [
                        'email' => $request->email,
                        'verification' => $verification
                    ]);
                    // Continue anyway - don't block user
                } else {
                    $score = (float) ($verification['score'] ?? 0);
                    $action = $verification['action'] ?? null;
                    $threshold = (float) config('services.recaptcha.threshold', 0.5);

                    if ($score < $threshold) {
                        Log::warning('reCAPTCHA score too low for password reset', [
                            'email' => $request->email,
                            'score' => $score,
                            'threshold' => $threshold
                        ]);
                        // Continue anyway - don't block user
                    }
                }
            } catch (\Throwable $e) {
                Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
                // Continue without blocking if reCAPTCHA fails
            }
        }

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Don't reveal if email exists or not for security
            return back()->with('status', 'Jika email terdaftar, link reset password telah dikirim ke email Anda.');
        }

        // Generate password reset token
        $token = Str::random(64);
        
        // Store token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send password reset email
        try {
            Mail::to($user->email)->send(new \App\Mail\PasswordReset($user, $token));
            Log::info('Password reset email sent to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Try using log driver as fallback for development
            if (config('mail.default') !== 'log') {
                try {
                    // Temporarily switch to log driver
                    $originalMailer = config('mail.default');
                    config(['mail.default' => 'log']);
                    
                    Mail::to($user->email)->send(new \App\Mail\PasswordReset($user, $token));
                    Log::info('Password reset email sent via log driver to: ' . $user->email);
                    Log::info('Password reset link: ' . url('password/reset', $token));
                    
                    // Reset config
                    config(['mail.default' => $originalMailer]);
                    
                    // Show success message with reset link (for development)
                    $resetLink = url('password/reset', $token);
                    return back()->with('status', 'Link reset password telah dibuat. Karena SMTP Gmail gagal, email disimpan di log. Link reset password: <a href="' . $resetLink . '" class="text-green-600 underline">' . $resetLink . '</a>');
                } catch (\Exception $logException) {
                    Log::error('Failed to send email even with log driver: ' . $logException->getMessage());
                    // Still show success message for security (don't reveal email issues)
                    return back()->with('status', 'Jika email terdaftar, link reset password telah dikirim ke email Anda.');
                }
            }
            
            // For development: show more detailed error if APP_DEBUG is true
            if (config('app.debug')) {
                return back()->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage() . '. Silakan cek konfigurasi MAIL di .env atau gunakan MAIL_MAILER=log untuk development.'])->withInput();
            }
            
            // For production: show generic message for security
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi nanti atau hubungi admin.'])->withInput();
        }

        return back()->with('status', 'Jika email terdaftar, link reset password telah dikirim ke email Anda.');
    }

    /**
     * Show the reset password form
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Find the password reset record
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.'])->withInput();
        }

        // Check if token is valid (not expired - 60 minutes)
        $createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password sudah kedaluwarsa. Silakan request reset password baru.'])->withInput();
        }

        // Verify token
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.'])->withInput();
        }

        // Find user and update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.'])->withInput();
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the password reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Log::info('Password reset successful for user: ' . $user->email);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
