<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ZakatPaymentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ZakatCalculatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuzakkiController;
use App\Http\Controllers\MustahikController;
use App\Http\Controllers\ZakatDistributionController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\RegionController; // Add this
use App\Http\Controllers\OTPController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\RecurringDonationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this import

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');


// Chatbot route
Route::post('/chatbot', [ChatbotController::class, 'ask'])->name('chatbot.ask');

// Redirect /admin to admin login page
Route::get('/admin', function () {
    return redirect()->route('admin.login');
});

Route::get('/payments/search', [ZakatPaymentController::class, 'search'])->name('api.payments.search');




Route::get('/payment/{paymentCode}/failed', [ZakatPaymentController::class, 'guestFailure'])
    ->name('guest.payment.failed');

// Uncommented to enable individual program display
Route::get('/program/{slug}', [ProgramController::class, 'show'])->name('program.show');

Route::get('/program', [HomeController::class, 'program'])->name('program');

// Tab-specific routes for program categories
Route::get('/program/zakat', function () {
    return app(HomeController::class)->programByCategory('zakat');
})->name('program.zakat');

Route::get('/program/infaq', function () {
    return app(HomeController::class)->programByCategory('infaq');
})->name('program.infaq');

Route::get('/program/shadaqah', function () {
    return app(HomeController::class)->programByCategory('shadaqah');
})->name('program.shadaqah');

Route::get('/program/pilar', function () {
    return app(HomeController::class)->programByCategory('pilar');
})->name('program.pilar');

// Campaign routes
Route::get('/campaigns/all', [CampaignController::class, 'all'])->name('campaigns.all');
Route::get('/campaigns/{category}', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{category}/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');

// Route::get('/tentang', function () {
//     return view('pages.tentang');
// })->name('tentang');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');


Route::get('/berita', [HomeController::class, 'berita'])->name('berita');
Route::get('/news', [NewsController::class, 'all'])->name('news.all');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Artikel routes
Route::get('/artikel', [HomeController::class, 'artikel'])->name('artikel.index');
Route::get('/artikel/all', [HomeController::class, 'artikelAll'])->name('artikel.all');
Route::get('/artikel/{slug}', [HomeController::class, 'artikelShow'])->name('artikel.show');

// Image serving route to avoid 403 errors
Route::get('/images/{path}', function ($path) {
    // Security check to prevent directory traversal
    if (strpos($path, '..') !== false) {
        abort(404);
    }

    // Define the storage path
    $storagePath = storage_path('app/public/' . $path);

    // Check if file exists
    if (!file_exists($storagePath)) {
        abort(404);
    }

    // Get file mime type
    $mimeType = mime_content_type($storagePath);

    // Return the file with proper headers
    return response()->file($storagePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000' // Cache for 1 year
    ]);
})->where('path', '.*')->name('image.serve');

// Authentication routes
// Admin login route
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Muzakki login route
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Password Reset Routes
Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendPasswordResetEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Two Factor Authentication verification (public route for login)
Route::get('/two-factor/verify', [App\Http\Controllers\TwoFactorController::class, 'showVerify'])->name('two-factor.verify');
Route::post('/two-factor/verify', [App\Http\Controllers\TwoFactorController::class, 'verify'])->name('two-factor.verify.post');

// Public zakat calculator
Route::get('/calculator', [ZakatCalculatorController::class, 'index'])->name('calculator.index');
Route::post('/calculator/calculate', [ZakatCalculatorController::class, 'calculate'])->name('calculator.calculate');
Route::get('/calculator/guide', [ZakatCalculatorController::class, 'guide'])->name('calculator.guide');
Route::get('/calculator/gold-price', [ZakatCalculatorController::class, 'getGoldPrice'])->name('calculator.gold-price');

// Guest payment routes (no login required)
Route::prefix('donasi')->name('guest.payment.')->group(function () {
    // Redirect /donasi to program page so users can choose a program first
    Route::get('/', function () {
        return redirect()->route('program');
    });

    // Route for creating payment with program_id (accessed from program detail page)
    Route::get('/create', [ZakatPaymentController::class, 'guestCreate'])->name('create');
    Route::post('/store', [ZakatPaymentController::class, 'guestStore'])->name('store');
    Route::get('/summary/{paymentCode}', [ZakatPaymentController::class, 'guestSummary'])->name('summary');
    Route::get('/success/{paymentCode}', [ZakatPaymentController::class, 'guestSuccess'])->name('success');
    Route::get('/check-status/{paymentCode}', [ZakatPaymentController::class, 'guestCheckStatus'])->name('checkStatus');
    Route::post('/leave-page/{paymentCode}', [ZakatPaymentController::class, 'guestLeavePage'])->name('leavePage');
    Route::post('/get-token/{paymentCode}', [ZakatPaymentController::class, 'getSnapToken'])->name('getToken');
    Route::post('/{paymentCode}/get-token-custom', [ZakatPaymentController::class, 'getTokenCustom'])->name('getTokenCustom');
    Route::get('/{paymentCode}/receipt', [ZakatPaymentController::class, 'guestReceiptByCode'])->name('receipt');
    Route::get('/{paymentCode}/receipt/download', [ZakatPaymentController::class, 'downloadGuestReceipt'])->name('receipt.download');

    // Route for campaign donation by slug (must be last to avoid conflict with specific routes)
    Route::get('/{slug}', [DonationController::class, 'show'])->name('show');
});




// Protected routes
Route::middleware('auth')->group(function () {

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Muzakki dashboard sections (without muzakki prefix)
    Route::middleware('role:muzakki')->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions');
        Route::get('/recurring', [DashboardController::class, 'recurringDonations'])->name('recurring');
        Route::get('/bank-accounts', [DashboardController::class, 'bankAccounts'])->name('bank-accounts');
        Route::get('/bank-accounts/add', [BankAccountController::class, 'create'])->name('bank-accounts.create');
        Route::get('/management', [DashboardController::class, 'accountManagement'])->name('management');
        Route::get('/management/account/transfer-account', [DashboardController::class, 'transferAccount'])->name('management.transfer-account');
        Route::get('/management/account/delete-account', [DashboardController::class, 'deleteAccount'])->name('management.delete-account');
        Route::get('/recurring/create', [RecurringDonationController::class, 'create'])->name('recurring.create');
        
        // Two Factor Authentication routes
        Route::get('/two-factor/setup', [App\Http\Controllers\TwoFactorController::class, 'showSetup'])->name('two-factor.setup');
        Route::post('/two-factor/enable', [App\Http\Controllers\TwoFactorController::class, 'enable'])->name('two-factor.enable');
        Route::post('/two-factor/disable', [App\Http\Controllers\TwoFactorController::class, 'disable'])->name('two-factor.disable');

        Route::post('/bank-accounts', [BankAccountController::class, 'store'])->name('bank-accounts.store');
        Route::delete('/bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');
        Route::post('/bank-accounts/{bankAccount}/set-primary', [BankAccountController::class, 'setPrimary'])->name('bank-accounts.set-primary');

        Route::post('/recurring-donations', [RecurringDonationController::class, 'store'])->name('recurring-donations.store');
        Route::patch('/recurring-donations/{recurringDonation}/toggle', [RecurringDonationController::class, 'toggle'])->name('recurring-donations.toggle');
        Route::delete('/recurring-donations/{recurringDonation}', [RecurringDonationController::class, 'destroy'])->name('recurring-donations.destroy');
    });

    // Muzakki-specific routes (without muzakki prefix)
    Route::middleware('role:muzakki')->group(function () {
        // Muzakki pages
        Route::get('/donation', [DashboardController::class, 'donation'])->name('donation');
        Route::get('/fundraising', [DashboardController::class, 'fundraising'])->name('fundraising');
        Route::get('/amalanku', [DashboardController::class, 'amalanku'])->name('amalanku');

        // Muzakki payments
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [ZakatPaymentController::class, 'index'])->name('index');
            Route::get('/create', [ZakatPaymentController::class, 'create'])->name('create');
            Route::post('/', [ZakatPaymentController::class, 'store'])->name('store');
            Route::get('/{payment}', [ZakatPaymentController::class, 'show'])->name('show');
            Route::get('/{payment}/receipt', [ZakatPaymentController::class, 'receipt'])->name('receipt');
        });

        // Muzakki notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [ZakatPaymentController::class, 'notifications'])->name('index');
            Route::get('/ajax', [ZakatPaymentController::class, 'ajaxNotifications'])->name('ajax');
            Route::post('/mark-as-read', [ZakatPaymentController::class, 'markNotificationsAsRead'])->name('markAsRead');
        });

        // Muzakki profile management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [MuzakkiController::class, 'edit'])->name('edit');
            Route::put('/', [MuzakkiController::class, 'update'])->name('update');
        });

        // Muzakki calculator (same as public calculator but with muzakki context)
        // Note: Using public calculator route, no need for separate route
    });

    // Admin only routes
    Route::middleware('role:admin')->group(function () {

        // Muzakki management (exclude routes that conflict with muzakki-specific routes)
        Route::resource('muzakki', MuzakkiController::class)->except(['show']);
        Route::get('/muzakki/{muzakki}', [MuzakkiController::class, 'show'])->name('muzakki.show')->where('muzakki', '[0-9]+');
        Route::patch('/muzakki/{muzakki}/toggle-status', [MuzakkiController::class, 'toggleStatus'])->name('muzakki.toggle-status')->where('muzakki', '[0-9]+');
        Route::get('/api/muzakki/search', [MuzakkiController::class, 'search'])->name('api.muzakki.search');

        // Mustahik management
        Route::resource('mustahik', MustahikController::class);
        Route::patch('/mustahik/{mustahik}/verify', [MustahikController::class, 'verify'])->name('mustahik.verify');
        Route::patch('/mustahik/{mustahik}/toggle-status', [MustahikController::class, 'toggleStatus'])->name('mustahik.toggle-status');
        Route::get('/api/mustahik/by-category', [MustahikController::class, 'getByCategory'])->name('api.mustahik.by-category');
        Route::get('/api/mustahik/search', [MustahikController::class, 'search'])->name('api.mustahik.search');

        // Zakat payments management (Admin can manage all)
        Route::resource('payments', ZakatPaymentController::class)->except(['create', 'store']);
        Route::get('/api/payments/search', [ZakatPaymentController::class, 'search'])->name('api.payments.search');

        // Zakat distributions management
        Route::resource('distributions', ZakatDistributionController::class);
        Route::patch('/distributions/{distribution}/mark-received', [ZakatDistributionController::class, 'markAsReceived'])->name('distributions.mark-received');
        Route::get('/distributions-report/category', [ZakatDistributionController::class, 'reportByCategory'])->name('distributions.report.category');
        Route::get('/api/distributions/mustahik-by-category', [ZakatDistributionController::class, 'getMustahikByCategory'])->name('api.distributions.mustahik-by-category');
        Route::get('/api/distributions/search', [ZakatDistributionController::class, 'search'])->name('api.distributions.search');

        // Receipt generation
        Route::get('/payments/{payment}/receipt', [ZakatPaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('/distributions/{distribution}/receipt', [ZakatDistributionController::class, 'receipt'])->name('distributions.receipt');

        // Reports routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/incoming', [ReportsController::class, 'incoming'])->name('incoming');

            Route::get('/outgoing', [ReportsController::class, 'outgoing'])->name('outgoing');
        });

        // News management
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/news', [NewsController::class, 'adminIndex'])->name('news.index');
            Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
            Route::post('/news', [NewsController::class, 'store'])->name('news.store');
            Route::get('/news/{news}', [NewsController::class, 'adminShow'])->name('news.show');
            Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
            Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
            Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
            Route::patch('/news/{news}/toggle-publish', [NewsController::class, 'togglePublish'])->name('news.toggle-publish');

            // Artikel management
            Route::get('/artikel', [ArtikelController::class, 'index'])->name('artikel.index');
            Route::get('/artikel/create', [ArtikelController::class, 'create'])->name('artikel.create');
            Route::post('/artikel', [ArtikelController::class, 'store'])->name('artikel.store');
            Route::get('/artikel/{artikel}', [ArtikelController::class, 'show'])->name('artikel.show');
            Route::get('/artikel/{artikel}/edit', [ArtikelController::class, 'edit'])->name('artikel.edit');
            Route::put('/artikel/{artikel}', [ArtikelController::class, 'update'])->name('artikel.update');
            Route::delete('/artikel/{artikel}', [ArtikelController::class, 'destroy'])->name('artikel.destroy');
            Route::patch('/artikel/{artikel}/toggle-publish', [ArtikelController::class, 'togglePublish'])->name('artikel.toggle-publish');

            // Campaign management
            Route::get('/campaigns', [CampaignController::class, 'adminIndex'])->name('campaigns.index');
            Route::get('/campaigns/create', [CampaignController::class, 'adminCreate'])->name('campaigns.create');
            Route::post('/campaigns', [CampaignController::class, 'adminStore'])->name('campaigns.store');
            Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'adminEdit'])->name('campaigns.edit');
            Route::put('/campaigns/{campaign}', [CampaignController::class, 'adminUpdate'])->name('campaigns.update');
            Route::delete('/campaigns/{campaign}', [CampaignController::class, 'adminDestroy'])->name('campaigns.destroy');
        });
    });

    // Routes accessible by all authenticated users (including muzakki)
    Route::group([], function () {

        // Zakat payments (Muzakki can create their own payments)
        Route::get('/payments/create', [ZakatPaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [ZakatPaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [ZakatPaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/receipt', [ZakatPaymentController::class, 'receipt'])->name('payments.receipt');

        // Zakat calculator for authenticated users
        Route::get('/my-calculator', [ZakatCalculatorController::class, 'index'])->name('my-calculator');

        // Dedicated profile routes for all authenticated users
        Route::get('/profile', [MuzakkiController::class, 'edit'])->name('profile.show');
        Route::put('/profile', [MuzakkiController::class, 'update'])->name('profile.update');
    });

    // Admin only routes
    Route::middleware('role:admin')->group(function () {

        // User management routes would go here
        // Settings and configuration routes would go here

        // Program management routes
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/programs', [ProgramController::class, 'adminIndex'])->name('programs.index');
            Route::get('/programs/create', [ProgramController::class, 'adminCreate'])->name('programs.create');
            Route::get('/programs/bulk-create', [ProgramController::class, 'adminBulkCreate'])->name('programs.bulk-create');
            Route::post('/programs', [ProgramController::class, 'adminStore'])->name('programs.store');
            Route::post('/programs/bulk', [ProgramController::class, 'adminStoreBulk'])->name('programs.store.bulk');
            Route::get('/programs/{program}/edit', [ProgramController::class, 'adminEdit'])->name('programs.edit');
            Route::put('/programs/{program}', [ProgramController::class, 'adminUpdate'])->name('programs.update');
            Route::delete('/programs/{program}', [ProgramController::class, 'adminDestroy'])->name('programs.destroy');
        });
    });
});



// routes/web.php
Route::get('/payment/finish', [ZakatPaymentController::class, 'finish']);
Route::get('/payment/unfinish', [ZakatPaymentController::class, 'unfinish']);
Route::get('/payment/error', [ZakatPaymentController::class, 'error']);

// Midtrans Notification Route
Route::post('/midtrans/notification', [ZakatPaymentController::class, 'handleNotification']);
// Add this route for Firebase login
Route::post('/firebase-login', function (Request $request) {
    // Verify reCAPTCHA v3
    $recaptchaToken = $request->input('g-recaptcha-response');
    if (!$recaptchaToken) {
        return response()->json(['success' => false, 'message' => 'Validasi reCAPTCHA diperlukan.'], 422);
    }
    try {
        $verification = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ])->json();
        if (!($verification['success'] ?? false)) {
            return response()->json(['success' => false, 'message' => 'Verifikasi reCAPTCHA gagal.'], 422);
        }
        $score = (float) ($verification['score'] ?? 0);
        $action = $verification['action'] ?? null;
        $threshold = (float) config('services.recaptcha.threshold', 0.5);
        if ($score < $threshold || ($action && $action !== 'login')) {
            return response()->json(['success' => false, 'message' => 'Aktivitas mencurigakan terdeteksi.'], 422);
        }
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'message' => 'Layanan reCAPTCHA tidak tersedia.'], 503);
    }
    $request->validate([
        'email' => 'required|email',
        'name' => 'required|string',
        'firebase_uid' => 'required|string',
    ]);

    try {
        // Check if user already exists
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            // Create new user if doesn't exist
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt(uniqid()), // Generate a random password for Firebase users
                'role' => 'muzakki',
                'is_active' => true,
            ]);
        } else {
            // Update existing user's name if needed
            $user->update(['name' => $request->name]);
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

        // Use findOrCreate method to handle muzakki profile
        $muzakkiData = [
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => $user->id,
            'is_active' => true,
        ];

        \App\Models\Muzakki::findOrCreate($muzakkiData);

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
})->name('firebase.login');



// New region routes for cascading dropdowns
Route::prefix('regions')->name('regions.')->group(function () {
    Route::get('/countries', [RegionController::class, 'countries'])->name('countries');
    Route::get('/provinces/{country}', [RegionController::class, 'provinces'])->name('provinces');
    Route::get('/cities/{provinceId}', [RegionController::class, 'cities'])->name('cities');
    Route::get('/districts/{cityId}', [RegionController::class, 'districts'])->name('districts');
    Route::get('/villages/{districtId}', [RegionController::class, 'villages'])->name('villages');
    // New postal code validation routes
    Route::post('/validate-postal-code', [RegionController::class, 'validatePostalCode'])->name('validate.postal.code');
    Route::post('/get-postal-code', [RegionController::class, 'getPostalCodeByVillage'])->name('get.postal.code');
});


// Routes for sending and verifying OTP

Route::post('/send-otp', [OTPController::class, 'sendOTP'])->name('otp.send');
Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('otp.verify');
Route::post('/resend-otp', [OTPController::class, 'resendOTP'])->name('otp.resend');

// Personal campaign URL based on email
Route::get('/campaigner/{email}', [CampaignController::class, 'showPersonalCampaign'])
    ->name('campaigner.personal');


use Illuminate\Support\Facades\Mail;

// Route::get('/test-email', function () {
//     Mail::raw('Ini percobaan kirim email via Gmail SMTP.', function ($message) {
//         $message->to('kdpras00@gmail.com')
//             ->subject('Test Email Laravel Gmail');
//     });

//     return 'Email percobaan sudah dikirim! Cek inbox/spam.';
// });
