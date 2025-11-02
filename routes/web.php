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




Route::get('/donasi/{slug}', [DonationController::class, 'show'])->name('donasi.show');


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

Route::get('/program/pendidikan', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $program = \App\Models\Program::byCategory('pendidikan')->first();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('pendidikan')->sum('paid_amount');
    $totalTarget = $program ? $program->total_target : 0;
    return view('programs.pendidikan', compact('zakatTypes', 'collectedAmount', 'totalTarget'));
})->name('program.pendidikan');

Route::get('/program/kesehatan', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $program = \App\Models\Program::byCategory('kesehatan')->first();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('kesehatan')->sum('paid_amount');
    $totalTarget = $program ? $program->total_target : 0;
    return view('programs.kesehatan', compact('zakatTypes', 'collectedAmount', 'totalTarget'));
})->name('program.kesehatan');

Route::get('/program/ekonomi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $program = \App\Models\Program::byCategory('ekonomi')->first();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('ekonomi')->sum('paid_amount');
    $totalTarget = $program ? $program->total_target : 0;
    return view('programs.ekonomi', compact('zakatTypes', 'collectedAmount', 'totalTarget'));
})->name('program.ekonomi');

Route::get('/program/sosial-dakwah', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $program = \App\Models\Program::byCategory('sosial-dakwah')->first();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('sosial-dakwah')->sum('paid_amount');
    $totalTarget = $program ? $program->total_target : 0;
    return view('programs.sosial-dakwah', compact('zakatTypes', 'collectedAmount', 'totalTarget'));
})->name('program.sosial-dakwah');

Route::get('/program/kemanusiaan', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $program = \App\Models\Program::byCategory('kemanusiaan')->first();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('kemanusiaan')->sum('paid_amount');
    $totalTarget = $program ? $program->total_target : 0;
    return view('programs.kemanusiaan', compact('zakatTypes', 'collectedAmount', 'totalTarget'));
})->name('program.kemanusiaan');

Route::get('/program/lingkungan', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $program = \App\Models\Program::byCategory('lingkungan')->first();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('lingkungan')->sum('paid_amount');
    $totalTarget = $program ? $program->total_target : 0;
    return view('programs.lingkungan', compact('zakatTypes', 'collectedAmount', 'totalTarget'));
})->name('program.lingkungan');

// Program donation routes
Route::get('/program/pendidikan/donasi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('pendidikan')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('pendidikan')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'pendidikan';
    return view('programs.donasi-pendidikan', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.pendidikan.donasi');

Route::get('/program/kesehatan/donasi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('kesehatan')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('kesehatan')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'kesehatan';
    return view('programs.donasi-kesehatan', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.kesehatan.donasi');

Route::get('/program/ekonomi/donasi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('ekonomi')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('ekonomi')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'ekonomi';
    return view('programs.donasi-ekonomi', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.ekonomi.donasi');

Route::get('/program/sosial-dakwah/donasi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('sosial-dakwah')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('sosial-dakwah')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'sosial-dakwah';
    return view('programs.donasi-sosial-dakwah', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.sosial-dakwah.donasi');

Route::get('/program/kemanusiaan/donasi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('kemanusiaan')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('kemanusiaan')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'kemanusiaan';
    return view('programs.donasi-kemanusiaan', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.kemanusiaan.donasi');

Route::get('/program/lingkungan/donasi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('lingkungan')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('lingkungan')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'lingkungan';
    return view('programs.donasi-lingkungan', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.lingkungan.donasi');

// Zakat program routes
Route::get('/program/zakat-mal', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('zakat-mal')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('zakat-mal')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'zakat-mal';
    return view('programs.zakat-mal', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.zakat-mal');

Route::get('/program/zakat-fitrah', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('zakat-fitrah')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('zakat-fitrah')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'zakat-fitrah';
    return view('programs.zakat-fitrah', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.zakat-fitrah');

Route::get('/program/zakat-profesi', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('zakat-profesi')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('zakat-profesi')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'zakat-profesi';
    return view('programs.zakat-profesi', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.zakat-profesi');

Route::get('/program/zakat-pertanian', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('zakat-pertanian')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('zakat-pertanian')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'zakat-pertanian';
    return view('programs.zakat-pertanian', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.zakat-pertanian');

Route::get('/program/zakat-perdagangan', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('zakat-perdagangan')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('zakat-perdagangan')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'zakat-perdagangan';
    return view('programs.zakat-perdagangan', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.zakat-perdagangan');

// Infaq program routes
Route::get('/program/infaq-masjid', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('infaq-masjid')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('infaq-masjid')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'infaq-masjid';
    return view('programs.infaq-masjid', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.infaq-masjid');

Route::get('/program/infaq-pendidikan', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('infaq-pendidikan')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('infaq-pendidikan')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'infaq-pendidikan';
    return view('programs.infaq-pendidikan', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.infaq-pendidikan');

Route::get('/program/infaq-kemanusiaan', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('infaq-kemanusiaan')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('infaq-kemanusiaan')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'infaq-kemanusiaan';
    return view('programs.infaq-kemanusiaan', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.infaq-kemanusiaan');

Route::get('/program/infaq-bencana', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('infaq-bencana')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('infaq-bencana')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'infaq-bencana';
    return view('programs.infaq-bencana', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.infaq-bencana');

Route::get('/program/infaq-sosial', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('infaq-sosial')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('infaq-sosial')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'infaq-sosial';
    return view('programs.infaq-sosial', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.infaq-sosial');

// Shadaqah program routes
Route::get('/program/shadaqah-rutin', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('shadaqah-rutin')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('shadaqah-rutin')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'shadaqah-rutin';
    return view('programs.shadaqah-rutin', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.shadaqah-rutin');

Route::get('/program/shadaqah-jariyah', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('shadaqah-jariyah')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('shadaqah-jariyah')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'shadaqah-jariyah';
    return view('programs.shadaqah-jariyah', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.shadaqah-jariyah');

Route::get('/program/fidyah', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('fidyah')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('fidyah')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'fidyah';
    return view('programs.fidyah', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.fidyah');

Route::get('/program/shadaqah-tetangga', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('shadaqah-tetangga')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('shadaqah-tetangga')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'shadaqah-tetangga';
    return view('programs.shadaqah-tetangga', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.shadaqah-tetangga');

Route::get('/program/shadaqah-pakaian', function () {
    $zakatTypes = \App\Models\ZakatType::active()->get();
    $collectedAmount = \App\Models\ZakatPayment::completed()->byProgramCategory('shadaqah-pakaian')->sum('paid_amount');
    $program = \App\Models\Program::byCategory('shadaqah-pakaian')->first();
    $totalTarget = $program ? $program->total_target : 0;
    $category = 'shadaqah-pakaian';
    return view('programs.shadaqah-pakaian', compact('zakatTypes', 'collectedAmount', 'totalTarget', 'category'));
})->name('program.shadaqah-pakaian');

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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public zakat calculator
Route::get('/calculator', [ZakatCalculatorController::class, 'index'])->name('calculator.index');
Route::post('/calculator/calculate', [ZakatCalculatorController::class, 'calculate'])->name('calculator.calculate');
Route::get('/calculator/guide', [ZakatCalculatorController::class, 'guide'])->name('calculator.guide');
Route::get('/calculator/gold-price', [ZakatCalculatorController::class, 'getGoldPrice'])->name('calculator.gold-price');

// Guest payment routes (no login required)
Route::prefix('donasi')->name('guest.payment.')->group(function () {
    // Redirect /donasi to guest create page instead of program page
    Route::get('/', [ZakatPaymentController::class, 'guestCreate'])->name('create');
    Route::post('/store', [ZakatPaymentController::class, 'guestStore'])->name('store');
    Route::get('/summary/{paymentCode}', [ZakatPaymentController::class, 'guestSummary'])->name('summary');
    Route::get('/success/{paymentCode}', [ZakatPaymentController::class, 'guestSuccess'])->name('success');
    Route::get('/check-status/{paymentCode}', [ZakatPaymentController::class, 'guestCheckStatus'])->name('checkStatus');
    Route::post('/leave-page/{paymentCode}', [ZakatPaymentController::class, 'guestLeavePage'])->name('leavePage');
    Route::post('/get-token/{paymentCode}', [ZakatPaymentController::class, 'getSnapToken'])->name('getToken');
    Route::post('/{paymentCode}/get-token-custom', [ZakatPaymentController::class, 'getTokenCustom'])->name('getTokenCustom');
    Route::get('/{paymentCode}/receipt', [ZakatPaymentController::class, 'guestReceiptByCode'])->name('receipt');
    Route::get('/{paymentCode}/receipt/download', [ZakatPaymentController::class, 'downloadGuestReceipt'])->name('receipt.download');
});




// Protected routes
Route::middleware('auth')->group(function () {

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Muzakki dashboard (for muzakki role)
    Route::get('/muzakki/dashboard', [DashboardController::class, 'index'])->name('muzakki.dashboard');

    // Muzakki dashboard sections
    Route::middleware('role:muzakki')->prefix('muzakki/dashboard')->name('muzakki.dashboard.')->group(function () {
        Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions');
        Route::get('/recurring', [DashboardController::class, 'recurringDonations'])->name('recurring');
        Route::get('/bank-accounts', [DashboardController::class, 'bankAccounts'])->name('bank-accounts');
        Route::get('/management', [DashboardController::class, 'accountManagement'])->name('management');
    });

    // Admin and Staff only routes
    Route::middleware('role:admin,staff')->group(function () {

        // Muzakki management
        Route::resource('muzakki', MuzakkiController::class);
        Route::patch('/muzakki/{muzakki}/toggle-status', [MuzakkiController::class, 'toggleStatus'])->name('muzakki.toggle-status');
        Route::get('/api/muzakki/search', [MuzakkiController::class, 'search'])->name('api.muzakki.search');

        // Mustahik management
        Route::resource('mustahik', MustahikController::class);
        Route::patch('/mustahik/{mustahik}/verify', [MustahikController::class, 'verify'])->name('mustahik.verify');
        Route::patch('/mustahik/{mustahik}/toggle-status', [MustahikController::class, 'toggleStatus'])->name('mustahik.toggle-status');
        Route::get('/api/mustahik/by-category', [MustahikController::class, 'getByCategory'])->name('api.mustahik.by-category');
        Route::get('/api/mustahik/search', [MustahikController::class, 'search'])->name('api.mustahik.search');

        // Zakat payments management (Admin/Staff can manage all)
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

    // Muzakki-specific routes (for muzakki role users)
    Route::middleware('role:muzakki')->prefix('muzakki')->name('muzakki.')->group(function () {
        // Muzakki payments
        Route::get('/payments', [ZakatPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/create', [ZakatPaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments', [ZakatPaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/{payment}', [ZakatPaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/receipt', [ZakatPaymentController::class, 'receipt'])->name('payments.receipt');

        // Muzakki notifications
        Route::get('/notifications', [ZakatPaymentController::class, 'notifications'])->name('notifications.index');
        Route::get('/notifications/ajax', [ZakatPaymentController::class, 'ajaxNotifications'])->name('notifications.ajax');
        Route::post('/notifications/mark-as-read', [ZakatPaymentController::class, 'markNotificationsAsRead'])
            ->name('notifications.markAsRead');


        // Muzakki profile management
        Route::get('/profile/edit', [MuzakkiController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [MuzakkiController::class, 'update'])->name('profile.update');

        // Muzakki calculator (same as public calculator but with muzakki context)
        Route::get('/calculator', [ZakatCalculatorController::class, 'index'])->name('calculator');
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

        // Use findOrCreate method to handle muzakki profile
        $muzakkiData = [
            'name' => $request->name,
            'email' => $request->email,
            'user_id' => $user->id,
            'is_active' => true,
        ];

        \App\Models\Muzakki::findOrCreate($muzakkiData);

        // Log in the user
        Auth::login($user);

        return response()->json([
            'success' => true,
            'redirect' => '/',
            'message' => 'Login successful'
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
