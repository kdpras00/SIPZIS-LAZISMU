<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show the TOTP setup page
     */
    public function showSetup()
    {
        $user = Auth::user();
        
        // Generate secret if not exists
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $this->google2fa->generateSecretKey();
            $user->save();
        }

        // Generate QR Code URL
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name', 'SIPZIS'),
            $user->email,
            $user->two_factor_secret
        );

        // Generate QR Code using BaconQrCode
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);
        
        // Convert to data URI
        $qrCodeDataUri = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        return view('muzakki.dashboard.two-factor-setup', [
            'user' => $user,
            'qrCode' => $qrCodeDataUri,
            'secret' => $user->two_factor_secret
        ]);
    }

    /**
     * Enable TOTP after verification
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (!$user->two_factor_secret) {
            return back()->withErrors(['code' => 'Secret key tidak ditemukan. Silakan refresh halaman.']);
        }

        // Verify the code
        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        // Enable 2FA
        $user->two_factor_enabled = true;
        $user->two_factor_confirmed_at = now();
        $user->save();

        return redirect()->route('dashboard.management')
            ->with('success', 'Autentikasi dua faktor berhasil diaktifkan!');
    }

    /**
     * Disable TOTP
     */
    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if (!$user->two_factor_enabled) {
            return back()->withErrors(['code' => 'Autentikasi dua faktor belum diaktifkan.']);
        }

        // Verify the code before disabling
        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        // Disable 2FA
        $user->two_factor_enabled = false;
        $user->two_factor_secret = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return redirect()->route('dashboard.management')
            ->with('success', 'Autentikasi dua faktor berhasil dinonaktifkan!');
    }

    /**
     * Show TOTP verification page (for login)
     */
    public function showVerify(Request $request)
    {
        $userId = $request->session()->get('login.id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['message' => 'Sesi login tidak ditemukan.']);
        }

        return view('auth.two-factor-verify');
    }

    /**
     * Verify TOTP code (for login)
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $userId = $request->session()->get('login.id');
        $user = User::find($userId);

        if (!$user || !$user->two_factor_secret) {
            return back()->withErrors(['code' => 'User tidak ditemukan atau 2FA tidak diaktifkan.']);
        }

        $valid = $this->google2fa->verifyKey(
            $user->two_factor_secret,
            $request->code
        );

        if (!$valid) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        // Clear the login session
        $request->session()->forget('login.id');

        // Log in the user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }
}
