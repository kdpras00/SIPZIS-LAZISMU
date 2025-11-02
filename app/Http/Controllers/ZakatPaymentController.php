<?php

namespace App\Http\Controllers;

use App\Models\ZakatPayment;
use App\Models\Muzakki;
use App\Models\ZakatType;
use App\Models\Campaign;
use App\Models\Program;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

use Midtrans\Notification as MidtransNotification;

class ZakatPaymentController extends Controller
{
    /**
     * Handle incoming Midtrans notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    // Middleware is applied in routes

    public function getTokenCustom(Request $request, $paymentCode)
    {
        try {
            // Validasi input method
            $request->validate([
                'method' => 'required|string'
            ]);

            // Cari payment
            $payment = ZakatPayment::where('payment_code', $paymentCode)
                ->where('is_guest_payment', true)
                ->firstOrFail();

            // Log payment data before update
            Log::info('getTokenCustom called', [
                'payment_code' => $paymentCode,
                'payment_data_before_update' => [
                    'program_category' => $payment->program_category,
                    'program_type_id' => $payment->program_type_id
                ]
            ]);

            $method = $request->input('method');

            // Load muzakki relationship
            $payment->load('muzakki');

            // Pastikan Midtrans Config sudah diset
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Parameter dasar
            // Add timestamp and random string to make order_id unique for retry attempts
            $orderId = $payment->payment_code . '-' . time() . '-' . uniqid();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $payment->paid_amount, // Pastikan integer
                ],
                'customer_details' => [
                    'first_name' => $payment->muzakki->name,
                    'email' => $payment->muzakki->email,
                ],
            ];

            // Mapping metode ke Midtrans channel dan enabled_payments
            // Handle both the old format (midtrans-*) and new format (direct method names)
            switch (strtolower($method)) {
                case 'bca_va':
                case 'midtrans-bank-bca':
                    $params['payment_type'] = 'bank_transfer';
                    $params['bank_transfer'] = ['bank' => 'bca'];
                    $params['enabled_payments'] = ['bca_va'];
                    break;
                case 'bni_va':
                case 'midtrans-bank-bni':
                    $params['payment_type'] = 'bank_transfer';
                    $params['bank_transfer'] = ['bank' => 'bni'];
                    $params['enabled_payments'] = ['bni_va'];
                    break;
                case 'bri_va':
                case 'midtrans-bank-bri':
                    $params['payment_type'] = 'bank_transfer';
                    $params['bank_transfer'] = ['bank' => 'bri'];
                    $params['enabled_payments'] = ['bri_va'];
                    break;
                case 'mandiri_va':
                case 'midtrans-bank-mandiri':
                    $params['payment_type'] = 'echannel';
                    $params['enabled_payments'] = ['mandiri_va'];
                    break;
                case 'permata_va':
                case 'midtrans-bank-permata':
                    $params['payment_type'] = 'bank_transfer';
                    $params['bank_transfer'] = ['bank' => 'permata'];
                    $params['enabled_payments'] = ['permata_va'];
                    break;
                case 'gopay':
                case 'midtrans-gopay':
                    $params['payment_type'] = 'gopay';
                    $params['enabled_payments'] = ['gopay'];
                    break;
                case 'dana':
                case 'midtrans-dana':
                    $params['payment_type'] = 'dana';
                    $params['enabled_payments'] = ['dana'];
                    break;
                case 'shopeepay':
                case 'midtrans-shopeepay':
                    $params['payment_type'] = 'shopeepay';
                    $params['enabled_payments'] = ['shopeepay'];
                    break;
                case 'qris':
                case 'midtrans-qris':
                    $params['payment_type'] = 'qris';
                    $params['enabled_payments'] = ['qris'];
                    break;
                default:
                    // For unrecognized methods, we'll try to map them
                    $enabledPayments = $this->mapPaymentMethodToMidtransType($method);
                    if ($enabledPayments) {
                        $params['enabled_payments'] = $enabledPayments;
                        // Set payment_type based on the first enabled payment
                        $firstPayment = $enabledPayments[0];
                        if (in_array($firstPayment, ['bca_va', 'bni_va', 'bri_va', 'permata_va'])) {
                            $params['payment_type'] = 'bank_transfer';
                            $bankMap = [
                                'bca_va' => 'bca',
                                'bni_va' => 'bni',
                                'bri_va' => 'bri',
                                'permata_va' => 'permata'
                            ];
                            if (isset($bankMap[$firstPayment])) {
                                $params['bank_transfer'] = ['bank' => $bankMap[$firstPayment]];
                            }
                        } elseif ($firstPayment === 'mandiri_va') {
                            $params['payment_type'] = 'echannel';
                        } else {
                            $params['payment_type'] = $firstPayment;
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Metode pembayaran tidak valid.'
                        ], 400);
                    }
            }

            // Log parameters for debugging
            Log::info('=== START getTokenCustom ===', [
                'payment_code' => $paymentCode,
                'method' => $method
            ]);

            Log::info('Base params created', [
                'params' => $params
            ]);

            // Generate Snap Token
            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                Log::info('Snap token generated successfully', [
                    'payment_code' => $paymentCode,
                    'method' => $method,
                    'snap_token' => substr($snapToken ?? '', 0, 20) . '...'
                ]);
            } catch (\Exception $e) {
                Log::error('=== ERROR in getTokenCustom ===', [
                    'payment_code' => $paymentCode,
                    'method' => $method,
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);

                // Re-throw to be caught by the outer catch block
                throw $e;
            }

            // Update the payment with the selected payment method
            // Map the frontend method to the internal format for midtrans_payment_method
            $internalMethod = $this->mapFrontendToInternalMethod($method);

            // Update both payment_method (the user-facing method) and midtrans_payment_method (internal mapping)
            $updateData = [
                'payment_method' => $method, // Store the actual method selected by user
                'midtrans_order_id' => $orderId
            ];

            if ($internalMethod) {
                $updateData['midtrans_payment_method'] = $internalMethod;
            }

            $payment->update($updateData);

            // Log untuk debugging (opsional)
            Log::info('Snap Token Generated', [
                'payment_code' => $paymentCode,
                'method' => $method,
                'internal_method' => $internalMethod
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('General Error in getTokenCustom: ' . $e->getMessage(), [
                'payment_code' => $paymentCode ?? null,
                'method' => $method ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            // Provide more specific error messages based on the exception type
            $errorMessage = 'Terjadi kesalahan pada server.';

            if (strpos($e->getMessage(), 'transaction_details.order_id has already been taken') !== false) {
                $errorMessage = 'Transaksi sudah pernah dibuat. Silakan coba beberapa saat lagi.';
            } elseif (strpos($e->getMessage(), 'Data truncated for column') !== false) {
                $errorMessage = 'Metode pembayaran tidak valid.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get Snap Token for a specific payment via AJAX.
     */
    public function getSnapToken($paymentCode)
    {
        $payment = ZakatPayment::with('muzakki')->where('payment_code', $paymentCode)->firstOrFail();

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // If payment already has a snap_token, return it
        if (!empty($payment->snap_token)) {
            return response()->json(['snap_token' => $payment->snap_token]);
        }

        // Validate payment is still pending
        if ($payment->status !== 'pending') {
            return response()->json([
                'message' => 'Pembayaran tidak dapat diproses. Status: ' . $payment->status
            ], 400);
        }

        // Validate required fields
        if (empty($payment->muzakki->name) || empty($payment->muzakki->email)) {
            return response()->json(['message' => 'Data muzakki tidak lengkap.'], 400);
        }

        // Add timestamp and random string to make order_id unique for retry attempts
        $orderId = $payment->payment_code . '-' . time() . '-' . uniqid();

        // Ensure paid_amount is a valid number
        $paidAmount = is_numeric($payment->paid_amount) ? (int)$payment->paid_amount : 0;

        $midtransParams = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $paidAmount,
            ],
            'customer_details' => [
                'first_name' => $payment->muzakki->name,
                'email' => $payment->muzakki->email,
                'phone' => $payment->muzakki->phone ?? '',
            ],
            'item_details' => [[
                'id' => 'DONATION-' . $payment->id,
                'price' => $paidAmount,
                'quantity' => 1,
                'name' => 'Donasi Program ' . ucfirst($payment->program_category ?? 'Umum'),
            ]],
            // Add callbacks for all states
            'callbacks' => [
                'finish' => route('guest.payment.success', $payment->payment_code),
                'pending' => route('guest.payment.summary', $payment->payment_code),
                'error' => route('guest.payment.summary', $payment->payment_code),
                'cancel' => route('guest.payment.summary', $payment->payment_code),
            ]
        ];

        // Add enabled_payments parameter to pre-select payment method
        if (!empty($payment->midtrans_payment_method)) {
            // Map the stored payment method to Midtrans payment types
            $enabledPayments = $this->mapPaymentMethodToMidtransType($payment->midtrans_payment_method);
            if ($enabledPayments) {
                $midtransParams['enabled_payments'] = $enabledPayments;

                // For bank transfers, we need to set additional parameters
                $firstPayment = $enabledPayments[0];
                if (in_array($firstPayment, ['bca_va', 'bni_va', 'bri_va', 'permata_va'])) {
                    $midtransParams['payment_type'] = 'bank_transfer';
                    $bankMap = [
                        'bca_va' => 'bca',
                        'bni_va' => 'bni',
                        'bri_va' => 'bri',
                        'permata_va' => 'permata'
                    ];
                    if (isset($bankMap[$firstPayment])) {
                        $midtransParams['bank_transfer'] = ['bank' => $bankMap[$firstPayment]];
                    }
                } elseif ($firstPayment === 'mandiri_va') {
                    $midtransParams['payment_type'] = 'echannel';
                } else {
                    $midtransParams['payment_type'] = $firstPayment;
                }
            }
        } else if (!empty($payment->payment_method)) {
            // If we don't have midtrans_payment_method but have payment_method, try to map it
            // First check if it's already a Midtrans method
            $enabledPayments = $this->mapPaymentMethodToMidtransType($payment->payment_method);
            if (!$enabledPayments) {
                // If not, try mapping from frontend to internal
                $internalMethod = $this->mapFrontendToInternalMethod($payment->payment_method);
                if ($internalMethod) {
                    $enabledPayments = $this->mapPaymentMethodToMidtransType($internalMethod);
                }
            }

            if ($enabledPayments) {
                $midtransParams['enabled_payments'] = $enabledPayments;

                // For bank transfers, we need to set additional parameters
                $firstPayment = $enabledPayments[0];
                if (in_array($firstPayment, ['bca_va', 'bni_va', 'bri_va', 'permata_va'])) {
                    $midtransParams['payment_type'] = 'bank_transfer';
                    $bankMap = [
                        'bca_va' => 'bca',
                        'bni_va' => 'bni',
                        'bri_va' => 'bri',
                        'permata_va' => 'permata'
                    ];
                    if (isset($bankMap[$firstPayment])) {
                        $midtransParams['bank_transfer'] = ['bank' => $bankMap[$firstPayment]];
                    }
                } elseif ($firstPayment === 'mandiri_va') {
                    $midtransParams['payment_type'] = 'echannel';
                } else {
                    $midtransParams['payment_type'] = $firstPayment;
                }
            }
        }

        try {
            $snapToken = Snap::getSnapToken($midtransParams);

            // Save the snap_token and midtrans_order_id
            $payment->update([
                'snap_token' => $snapToken,
                'midtrans_order_id' => $orderId
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage(), [
                'payment_code' => $paymentCode,
                'order_id' => $orderId,
                'trace' => $e->getTraceAsString()
            ]);

            // Handle specific Midtrans errors
            if (strpos($e->getMessage(), 'transaction_details.order_id has already been taken') !== false) {
                return response()->json([
                    'message' => 'Transaksi sudah pernah dibuat. Silakan coba beberapa saat lagi.'
                ], 409); // Conflict status code
            }

            return response()->json([
                'message' => 'Gagal memproses pembayaran. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Map internal payment method to Midtrans payment types
     */
    private function mapPaymentMethodToMidtransType($paymentMethod)
    {
        $mapping = [
            'midtrans-gopay' => ['gopay'],
            'midtrans-dana' => ['dana'],
            'midtrans-shopeepay' => ['shopeepay'],
            'midtrans-qris' => ['qris'],
            'midtrans-bank-bni' => ['bni_va'],
            'midtrans-bank-bca' => ['bca_va'],
            'midtrans-bank-mandiri' => ['mandiri_va'],
            'midtrans-bank-bri' => ['bri_va'],
            'midtrans-bank-permata' => ['permata_va'],
            'midtrans-convenience-alfamart' => ['alfamart'],
            'midtrans-convenience-indomaret' => ['indomaret'],
            // Direct method names
            'bca_va' => ['bca_va'],
            'bni_va' => ['bni_va'],
            'bri_va' => ['bri_va'],
            'mandiri_va' => ['mandiri_va'],
            'permata_va' => ['permata_va'],
            'gopay' => ['gopay'],
            'dana' => ['dana'],
            'shopeepay' => ['shopeepay'],
            'qris' => ['qris']
        ];

        // Try direct mapping first
        if (isset($mapping[$paymentMethod])) {
            return $mapping[$paymentMethod];
        }

        // Try with midtrans- prefix
        $withPrefix = 'midtrans-' . $paymentMethod;
        if (isset($mapping[$withPrefix])) {
            return $mapping[$withPrefix];
        }

        return null;
    }

    /**
     * Map frontend payment method to internal format
     */
    private function mapFrontendToInternalMethod($frontendMethod)
    {
        $mapping = [
            'bca_va' => 'midtrans-bank-bca',
            'bni_va' => 'midtrans-bank-bni',
            'bri_va' => 'midtrans-bank-bri',
            'mandiri_va' => 'midtrans-bank-mandiri',
            'permata_va' => 'midtrans-bank-permata',
            'gopay' => 'midtrans-gopay',
            'dana' => 'midtrans-dana',
            'shopeepay' => 'midtrans-shopeepay',
            'qris' => 'midtrans-qris'
        ];

        // If already in internal format, return as is
        if (strpos($frontendMethod, 'midtrans-') === 0) {
            return $frontendMethod;
        }

        return $mapping[$frontendMethod] ?? null;
    }

    /**
     * Map Midtrans payment type to internal format
     */
    private function mapMidtransToInternalMethod($midtransType)
    {
        $mapping = [
            'gopay' => 'midtrans-gopay',
            'dana' => 'midtrans-dana',
            'shopeepay' => 'midtrans-shopeepay',
            'qris' => 'midtrans-qris',
            'bca_va' => 'midtrans-bank-bca',
            'bni_va' => 'midtrans-bank-bni',
            'bri_va' => 'midtrans-bank-bri',
            'mandiri_va' => 'midtrans-bank-mandiri',
            'permata_va' => 'midtrans-bank-permata',
            'bank_transfer' => 'midtrans-bank-bca', // Default to BCA for bank_transfer
            'echannel' => 'midtrans-bank-mandiri'
        ];

        // Special handling for bank_transfer with specific bank details
        if ($midtransType === 'bank_transfer' && isset($_REQUEST['bank'])) {
            $bank = $_REQUEST['bank'];
            switch ($bank) {
                case 'bca':
                    return 'midtrans-bank-bca';
                case 'bni':
                    return 'midtrans-bank-bni';
                case 'bri':
                    return 'midtrans-bank-bri';
                case 'permata':
                    return 'midtrans-bank-permata';
            }
        }

        return $mapping[$midtransType] ?? null;
    }

    /**
     * Map internal method to user-facing method
     */
    private function mapInternalToUserMethod($internalMethod)
    {
        $mapping = [
            'midtrans-bank-bca' => 'bca_va',
            'midtrans-bank-bni' => 'bni_va',
            'midtrans-bank-bri' => 'bri_va',
            'midtrans-bank-mandiri' => 'mandiri_va',
            'midtrans-bank-permata' => 'permata_va',
            'midtrans-gopay' => 'gopay',
            'midtrans-dana' => 'dana',
            'midtrans-shopeepay' => 'shopeepay',
            'midtrans-qris' => 'qris'
        ];

        return $mapping[$internalMethod] ?? null;
    }

    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Log the configuration for debugging
        Log::info('Midtrans Configuration:', [
            'serverKey' => Config::$serverKey,
            'isProduction' => Config::$isProduction,
            'isSanitized' => Config::$isSanitized,
            'is3ds' => Config::$is3ds
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ZakatPayment::with(['muzakki', 'receivedBy']);

        // Filter by user role - muzakki can only see their own payments
        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            if (!$muzakki) {
                abort(404, 'Profil muzakki tidak ditemukan.');
            }
            $query->where('muzakki_id', $muzakki->id);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                    ->orWhere('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas('muzakki', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by zakat type
        if ($request->has('zakat_type') && $request->zakat_type != '') {
            $query->where('zakat_type_id', $request->zakat_type);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(15)->withQueryString();

        // Get filter options and stats based on user role
        $zakatTypes = ZakatType::active()->get();
        if (Auth::check() && Auth::user()->role === 'muzakki') {
            $muzakki = Auth::user()->muzakki;
            $stats = [
                'total_amount' => $muzakki->zakatPayments()->completed()->sum('paid_amount'),
                'total_count' => $muzakki->zakatPayments()->completed()->count(),
                'this_month' => $muzakki->zakatPayments()->completed()->whereMonth('payment_date', date('m'))->sum('paid_amount'),
                'pending' => $muzakki->zakatPayments()->where('status', 'pending')->count(),
            ];
        } else {
            $stats = [
                'total_amount' => ZakatPayment::completed()->sum('paid_amount'),
                'total_count' => ZakatPayment::completed()->count(),
                'this_month' => ZakatPayment::completed()->whereMonth('payment_date', date('m'))->sum('paid_amount'),
                'pending' => ZakatPayment::where('status', 'pending')->count(),
            ];
        }

        return view('payments.index', compact('payments', 'zakatTypes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $zakatTypes = ZakatType::active()->get();

        if (Auth::check() && Auth::user()->role === 'muzakki') {
            // For muzakki users, automatically set their own muzakki profile
            $muzakki = Auth::user()->muzakki;
            if (!$muzakki) {
                abort(404, 'Profil muzakki tidak ditemukan.');
            }
            $allMuzakki = collect([$muzakki]); // Only show current user
        } else {
            // For admin, allow selecting any muzakki
            $muzakkiId = $request->get('muzakki_id');
            $muzakki = $muzakkiId ? Muzakki::findOrFail($muzakkiId) : null;
            $allMuzakki = Muzakki::active()->orderBy('name')->get();
        }

        return view('payments.create', compact('muzakki', 'zakatTypes', 'allMuzakki'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'muzakki_id' => 'required|exists:muzakki,id',
            'wealth_amount' => 'nullable|numeric|min:0',
            'zakat_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,check,online,bca_va,bri_va,bni_va,mandiri_va,permata_va,cimb_va,other_va,gopay,dana,shopeepay,qris,credit_card,bca_klikpay,cimb_clicks,danamon_online,bri_epay,indomaret,alfamart,akulaku',
            'payment_reference' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'hijri_year' => 'nullable|integer|min:1400|max:1500',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $paymentCode = ZakatPayment::generatePaymentCode();
            $receiptNumber = ZakatPayment::generateReceiptNumber();

            // Check if the authenticated user can set received_by
            $receivedBy = null;
            if (Auth::check()) {
                // Only admin can set received_by
                $user = Auth::user();
                if ($user->role === 'admin') {
                    $receivedBy = $user->id;
                }
                // For muzakki users, received_by remains null
            } else {
                // Default to admin user if not authenticated (guest payments)
                $receivedBy = 1;
            }

            $payment = ZakatPayment::create([
                'payment_code' => $paymentCode,
                'muzakki_id' => $request->muzakki_id,
                'wealth_amount' => $request->wealth_amount,
                'zakat_amount' => $request->zakat_amount,
                'paid_amount' => $request->paid_amount,
                'payment_method' => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'payment_date' => $request->payment_date,
                'hijri_year' => $request->hijri_year,
                'notes' => $request->notes,
                'status' => 'completed',
                'receipt_number' => $receiptNumber,
                'received_by' => $receivedBy,
            ]);

            DB::commit();

            $redirectRoute = Auth::check() && Auth::user()->role === 'muzakki' ? 'muzakki.dashboard' : 'payments.index';
            return redirect()->route($redirectRoute)->with('success', 'Pembayaran zakat berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan dalam memproses pembayaran.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ZakatPayment $payment)
    {
        $payment->load(['muzakki', 'receivedBy']);

        // Check permission for muzakki role
        if (Auth::check() && Auth::user()->role === 'muzakki') {
            if ($payment->muzakki->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
            }
        }

        return view('payments.show', compact('payment'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ZakatPayment $payment)
    {
        // Only allow deletion if payment is pending or cancelled
        if ($payment->status === 'completed') {
            return redirect()->route('payments.index')->with('error', 'Pembayaran yang sudah selesai tidak dapat dihapus.');
        }

        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil dihapus.');
    }

    /**
     * Generate receipt PDF
     */
    public function receipt(ZakatPayment $payment)
    {
        $payment->load(['muzakki', 'receivedBy']);

        // Check permission for muzakki role
        if (Auth::check() && Auth::user()->role === 'muzakki') {
            if ($payment->muzakki->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke kwitansi ini.');
            }
        }

        return view('payments.receipt', compact('payment'));
    }

    /**
     * Show guest payment form (no login required)
     */
    public function guestCreate(Request $request)
    {
        $zakatTypes = ZakatType::active()->get();
        $programCategory = $request->query('category', 'umum');
        $programId = $request->query('program_id'); // Added program_id parameter
        $campaignId = $request->query('campaign'); // Changed from 'campaign_id' to 'campaign'
        $amount = $request->query('amount'); // Get amount from query parameter

        // If program ID is provided, get the specific program
        $program = null;
        if ($programId) {
            $program = Program::find($programId);
            if ($program) {
                $programCategory = $program->category;
            }
        }

        // If campaign ID is provided, get the specific campaign
        $campaign = null;
        if ($campaignId) {
            $campaign = Campaign::find($campaignId);
        }

        // Check if user is logged in and has a muzakki profile
        $loggedInMuzakki = null;
        if (Auth::check()) {
            $loggedInMuzakki = Auth::user()->muzakki;
        }

        // Create display variables with appropriate defaults
        $displayTitle = 'Donasi Umum';
        $displaySubtitle = 'Bersama Kita Wujudkan Kebaikan';
        $textColor = 'text-emerald-800';

        // If we have a program, set the display variables based on it
        if ($program) {
            $displayTitle = $program->name;
            $displaySubtitle = $program->description ?? 'Bersama Kita Wujudkan Kebaikan';
            // Set text color based on category
            $textColor = match ($program->category) {
                'zakat-mal', 'zakat-fitrah', 'zakat-profesi', 'zakat-perdagangan', 'zakat-pertanian', 'zakat-ternak' => 'text-orange-800',
                'infaq-masjid', 'infaq-pendidikan', 'infaq-kemanusiaan', 'infaq-bencana', 'infaq-sosial' => 'text-blue-800',
                'shadaqah-rutin', 'shadaqah-jariyah', 'shadaqah-tetangga', 'shadaqah-pakaian', 'fidyah' => 'text-green-800',
                'pendidikan' => 'text-blue-800',
                'kesehatan' => 'text-red-800',
                'ekonomi' => 'text-amber-800',
                'sosial-dakwah' => 'text-green-800',
                'kemanusiaan' => 'text-purple-800',
                'lingkungan' => 'text-cyan-800',
                default => 'text-emerald-800',
            };
        }
        // If we have a campaign, set the display variables based on it
        else if ($campaign) {
            // Mapping kategori → subtitle default
            $categoryMap = [
                'pendidikan'    => 'Mencerahkan Masa Depan dalam Membangun Negeri',
                'kesehatan'     => 'Mewujudkan Kehidupan yang Lebih Sehat untuk Semua',
                'ekonomi'       => 'Memberdayakan Masyarakat secara Ekonomi',
                'sosial-dakwah' => 'Membangun Masyarakat yang Berkualitas',
                'kemanusiaan'   => 'Menyejahterakan Umat Manusia Tanpa Diskriminasi',
                'lingkungan'    => 'Menjaga Lingkungan untuk Generasi Mendatang',
                'zakat'         => 'Menyalurkan Zakat dengan Amanah dan Transparan',
                'infaq'         => 'Bersedekah untuk Keberkahan Bersama',
                'shadaqah'      => 'Membuka Pintu Rezeki dengan Shadaqah',
                'umum'          => 'Bersama Kita Wujudkan Kebaikan',
            ];

            // Tentukan subtitle dan warna berdasarkan kategori
            $displaySubtitle = $categoryMap[$campaign->program_category] ?? 'Bersama Kita Wujudkan Kebaikan';
            $textColor = match ($campaign->program_category) {
                'pendidikan' => 'text-blue-800',
                'kesehatan' => 'text-red-800',
                'ekonomi' => 'text-amber-800',
                'sosial-dakwah' => 'text-green-800',
                'kemanusiaan' => 'text-purple-800',
                'lingkungan' => 'text-cyan-800',
                'zakat' => 'text-orange-800',
                'infaq' => 'text-blue-800',
                'shadaqah' => 'text-green-800',
                default => 'text-emerald-800',
            };

            $displayTitle = $campaign->title;
        }
        // If no campaign but category is provided, set display variables based on category
        else if ($programCategory && $programCategory !== 'umum') {
            // Mapping kategori → title, subtitle, and color
            $categoryTitleMap = [
                'zakat-mal' => 'Zakat Mal',
                'zakat-fitrah' => 'Zakat Fitrah',
                'zakat-profesi' => 'Zakat Profesi',
                'zakat-perdagangan' => 'Zakat Perdagangan',
                'zakat-pertanian' => 'Zakat Pertanian',
                'zakat-ternak' => 'Zakat Ternak',
                'infaq-masjid' => 'Infaq Masjid',
                'infaq-pendidikan' => 'Infaq Pendidikan',
                'infaq-kemanusiaan' => 'Infaq Kemanusiaan',
                'shadaqah-rutin' => 'Shadaqah Rutin',
                'shadaqah-jariyah' => 'Shadaqah Jariyah',
                'fidyah' => 'Fidyah',
                'pendidikan' => 'Donasi Pendidikan',
                'kesehatan' => 'Donasi Kesehatan',
                'ekonomi' => 'Donasi Ekonomi',
                'sosial-dakwah' => 'Donasi Sosial Dakwah',
                'kemanusiaan' => 'Donasi Kemanusiaan',
                'lingkungan' => 'Donasi Lingkungan',
            ];

            $categorySubtitleMap = [
                'zakat-mal' => 'Zakat harta yang telah mencapai nisab dan haul selama satu tahun',
                'zakat-fitrah' => 'Zakat wajib yang dikeluarkan menjelang Hari Raya Idul Fitri',
                'zakat-profesi' => 'Zakat atas penghasilan atau pendapatan yang diperoleh',
                'zakat-perdagangan' => 'Zakat atas harta dagangan yang mencapai nisab',
                'zakat-pertanian' => 'Zakat atas hasil pertanian dan perkebunan',
                'zakat-ternak' => 'Zakat atas hewan ternak yang mencapai syarat',
                'infaq-masjid' => 'Infaq untuk pemeliharaan dan pengembangan masjid',
                'infaq-pendidikan' => 'Infaq untuk pengembangan pendidikan dan beasiswa',
                'infaq-kemanusiaan' => 'Infaq untuk membantu sesama yang membutuhkan',
                'shadaqah-rutin' => 'Shadaqah rutin untuk keberkahan dan kesejahteraan',
                'shadaqah-jariyah' => 'Shadaqah jariyah yang manfaatnya berkelanjutan',
                'fidyah' => 'Fidyah untuk mengganti kewajiban ibadah yang tidak dilaksanakan',
                'pendidikan' => 'Mencerahkan Masa Depan dalam Membangun Negeri',
                'kesehatan' => 'Mewujudkan Kehidupan yang Lebih Sehat untuk Semua',
                'ekonomi' => 'Memberdayakan Masyarakat secara Ekonomi',
                'sosial-dakwah' => 'Membangun Masyarakat yang Berkualitas',
                'kemanusiaan' => 'Menyejahterakan Umat Manusia Tanpa Diskriminasi',
                'lingkungan' => 'Menjaga Lingkungan untuk Generasi Mendatang',
            ];

            $categoryColorMap = [
                'zakat-mal' => 'text-orange-800',
                'zakat-fitrah' => 'text-orange-800',
                'zakat-profesi' => 'text-orange-800',
                'zakat-perdagangan' => 'text-orange-800',
                'zakat-pertanian' => 'text-orange-800',
                'zakat-ternak' => 'text-orange-800',
                'infaq-masjid' => 'text-blue-800',
                'infaq-pendidikan' => 'text-blue-800',
                'infaq-kemanusiaan' => 'text-blue-800',
                'shadaqah-rutin' => 'text-green-800',
                'shadaqah-jariyah' => 'text-green-800',
                'fidyah' => 'text-green-800',
                'pendidikan' => 'text-blue-800',
                'kesehatan' => 'text-red-800',
                'ekonomi' => 'text-amber-800',
                'sosial-dakwah' => 'text-green-800',
                'kemanusiaan' => 'text-purple-800',
                'lingkungan' => 'text-cyan-800',
            ];

            // Set display variables based on category
            $displayTitle = $categoryTitleMap[$programCategory] ?? 'Donasi Umum';
            $displaySubtitle = $categorySubtitleMap[$programCategory] ?? 'Bersama Kita Wujudkan Kebaikan';
            $textColor = $categoryColorMap[$programCategory] ?? 'text-emerald-800';
        }

        return view('payments.guest-create', compact('zakatTypes', 'programCategory', 'program', 'campaign', 'amount', 'loggedInMuzakki', 'displayTitle', 'displaySubtitle', 'textColor'));
    }

    public function guestStore(Request $request)
    {
        // Add detailed logging
        Log::info('=== GUEST STORE START ===', [
            'all_request_data' => $request->all(),
            'program_category' => $request->program_category,
            'program_type_id' => $request->program_type_id,
            'url_params' => $request->query(),
            'has_program_type_id' => $request->has('program_type_id'),
            'filled_program_type_id' => $request->filled('program_type_id')
        ]);

        // VALIDASI YANG SUDAH DIPERBAIKI DAN DISESUAIKAN DENGAN FORM
        $validatedData = $request->validate([
            'program_category' => 'nullable|string|max:255',
            'program_type_id'  => 'nullable|exists:program_types,id',
            'paid_amount'      => 'required|numeric|min:1000',
            'payment_method'   => 'nullable|string|in:cash,transfer,check,online,bca_va,bri_va,bni_va,mandiri_va,permata_va,cimb_va,other_va,gopay,dana,shopeepay,qris,credit_card,bca_klikpay,cimb_clicks,danamon_online,bri_epay,indomaret,alfamart,akulaku', // Sesuaikan dengan metode pembayaran yang tersedia (removed 'midtrans')
            'donor_name'       => 'nullable|string|max:255',
            'donor_phone'      => 'nullable|string|max:20',
            'donor_email'      => 'nullable|email|max:255',
            'notes'            => 'nullable|string',
            'zakat_type_id'    => 'nullable|exists:zakat_types,id', // Add validation for zakat_type_id
        ]);

        DB::beginTransaction();
        try {
            // Check if user is logged in
            $loggedInMuzakki = null;
            if (Auth::check()) {
                $loggedInMuzakki = Auth::user()->muzakki;
            }

            // Use logged-in user data if available, otherwise use form data
            $donorName = $loggedInMuzakki ? $loggedInMuzakki->name : ($request->donor_name ?: 'Hamba Allah');
            $donorEmail = $loggedInMuzakki ? $loggedInMuzakki->email : ($request->donor_email ?: ('guest_' . time() . '@anonymous.com'));

            // Format phone number for WhatsApp (62xxx format)
            $donorPhone = null;
            if ($loggedInMuzakki && $loggedInMuzakki->phone) {
                $donorPhone = $loggedInMuzakki->phone;
            } elseif ($request->donor_phone) {
                // Format phone number: combine country code with phone number
                $countryCode = $request->country_code ?? '+62';
                $countryCode = str_replace('+', '', $countryCode); // Remove + sign
                $phone = $request->donor_phone;

                // Remove any non-numeric characters
                $phone = preg_replace('/[^0-9]/', '', $phone);

                // Remove leading zero if present (08xxx → 8xxx)
                if (substr($phone, 0, 1) === '0') {
                    $phone = substr($phone, 1);
                }

                // Remove country code if user typed it
                if (substr($phone, 0, 2) === '62') {
                    $phone = substr($phone, 2);
                }

                // Combine country code with phone number
                $donorPhone = $countryCode . $phone;

                Log::info('Phone number formatted', [
                    'original' => $request->donor_phone,
                    'country_code' => $countryCode,
                    'formatted' => $donorPhone
                ]);
            }

            // Find or create muzakki - also update phone if provided
            $muzakki = Muzakki::where('email', $donorEmail)->first();

            if ($muzakki) {
                // Update existing muzakki - update phone if new one is provided
                $updateData = ['name' => $donorName];
                if ($donorPhone && !$muzakki->phone) {
                    // Only update phone if muzakki doesn't have one yet
                    $updateData['phone'] = $donorPhone;
                }
                $muzakki->update($updateData);
            } else {
                // Create new muzakki
                $muzakki = Muzakki::create([
                    'name'  => $donorName,
                    'email' => $donorEmail,
                    'phone' => $donorPhone,
                    'is_active' => true,
                ]);
            }

            // Ensure paid_amount is a valid number
            $paidAmount = is_numeric($request->paid_amount) ? (float)$request->paid_amount : 0;

            // Prepare payment data - REMOVED zakat_type_id since it's no longer in the table
            // Generate a unique payment code with retry mechanism to handle race conditions
            $maxRetries = 5;
            $retryCount = 0;
            $paymentCode = null;

            do {
                $paymentCode = ZakatPayment::generatePaymentCode();
                // Add a small random component to reduce collision probability
                if ($retryCount > 0) {
                    $paymentCode = rtrim($paymentCode, '0') . rand(0, 9);
                }
                $retryCount++;
            } while (ZakatPayment::where('payment_code', $paymentCode)->exists() && $retryCount < $maxRetries);

            if (ZakatPayment::where('payment_code', $paymentCode)->exists()) {
                throw new \Exception('Unable to generate unique payment code after ' . $maxRetries . ' attempts');
            }

            $paymentData = [
                'payment_code'     => $paymentCode, // Use the unique payment code
                'muzakki_id'       => $muzakki->id,
                'zakat_amount'     => null, // Make it nullable as per migration
                'paid_amount'      => $paidAmount,
                'payment_date'     => now(),
                'status'           => 'pending',
                'program_category' => 'umum', // Default to 'umum'
                'notes'            => $request->notes,
                'is_guest_payment' => true,
                'receipt_number'   => ZakatPayment::generateReceiptNumber(), // Use the existing method
            ];

            // If program_id is provided, set it in the payment data
            if ($request->filled('program_id')) {
                $paymentData['program_id'] = $request->program_id;
                // Also set the program_category from the program
                $program = Program::find($request->program_id);
                if ($program) {
                    $paymentData['program_category'] = $program->category;
                }
            }

            // Only set program_type_id if it's provided and valid
            if ($request->filled('program_type_id')) {
                $paymentData['program_type_id'] = $request->program_type_id;
            }

            // Only set payment_method if it's provided
            if ($request->filled('payment_method')) {
                $paymentData['payment_method'] = $request->payment_method;
            }

            // Add zakat_type_id if provided
            if ($request->filled('zakat_type_id')) {
                $paymentData['zakat_type_id'] = $request->zakat_type_id;
            }

            // If program_type_id is provided, also set program_category from the program type
            if ($request->filled('program_type_id')) {
                $programType = \App\Models\ProgramType::find($request->program_type_id);
                if ($programType) {
                    // Map program type category to program category
                    $categoryMap = [
                        'zakat' => ['zakat-fitrah', 'zakat-mal', 'zakat-profesi'],
                        'infaq' => ['infaq-masjid', 'infaq-pendidikan', 'infaq-kemanusiaan'],
                        'shadaqah' => ['shadaqah-rutin', 'shadaqah-jariyah', 'fidyah'],
                        'program_pilar' => ['pendidikan', 'kesehatan', 'ekonomi', 'sosial-dakwah', 'kemanusiaan', 'lingkungan']
                    ];

                    // For program_pilar category, use the specific program type name as the program category
                    if ($programType->category === 'program_pilar') {
                        $paymentData['program_category'] = $programType->name; // Use the program type name as category
                    } else {
                        // For other categories, get the first category from the mapping array
                        $categories = $categoryMap[$programType->category] ?? ['umum'];
                        $paymentData['program_category'] = $categories[0];
                    }

                    Log::info('Program category set from program type:', [
                        'program_type_id' => $request->program_type_id,
                        'program_type_category' => $programType->category,
                        'program_type_name' => $programType->name,
                        'mapped_category' => $paymentData['program_category']
                    ]);
                }
            } elseif ($request->filled('program_category')) {
                $paymentData['program_category'] = $request->program_category;
            } else {
                $paymentData['program_category'] = 'umum'; // Default fallback
            }

            $payment = ZakatPayment::create($paymentData);

            // Log the created payment data
            Log::info('Payment created successfully', [
                'payment_id' => $payment->id,
                'program_category' => $payment->program_category,
                'program_type_id' => $payment->program_type_id,
                'payment_data' => $paymentData
            ]);

            DB::commit();

            return response()->json([
                'success'      => true,
                'redirect_url' => route('guest.payment.summary', $payment->payment_code)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Guest Store Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function guestSummary($paymentCode)
    {
        // Remove status filter - allow any status to view summary
        $payment = ZakatPayment::where('payment_code', $paymentCode)
            ->with(['muzakki', 'programType'])
            ->firstOrFail();

        // If payment is already completed, redirect to success page
        if ($payment->status === 'completed') {
            return redirect()->route('guest.payment.success', $payment->payment_code);
        }

        return view('payments.guest-summary', compact('payment'));
    }

    /**
     * Handle Midtrans Notification
     */
    public function handleNotification(Request $request)
    {
        Log::info('=== MIDTRANS NOTIFICATION START ===');
        Log::info('Body:', ['body' => $request->all()]);

        try {
            // Konfigurasi Midtrans
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Ambil notifikasi dari Midtrans
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $transactionId = $notification->transaction_id;
            $fraudStatus = $notification->fraud_status ?? null;
            $paymentType = $notification->payment_type ?? null;

            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType
            ]);

            // Cari pembayaran berdasarkan midtrans_order_id
            $payment = ZakatPayment::where('midtrans_order_id', $orderId)->first();

            if (!$payment) {
                Log::warning("Payment not found for order_id: {$orderId}");
                return response()->json(['status' => 'ok', 'message' => 'Payment not found'], 200);
            }

            // Tentukan status baru berdasarkan aturan Midtrans
            $newStatus = $payment->status; // Default to current status

            // Handle status updates according to Midtrans documentation
            switch ($transactionStatus) {
                case 'capture':
                    // For credit card transactions
                    if ($fraudStatus === 'accept') {
                        $newStatus = 'completed';
                    } else if ($fraudStatus === 'challenge') {
                        $newStatus = 'pending';
                    }
                    break;

                case 'settlement':
                    // For non-credit card transactions (bank transfers, e-wallets, etc.)
                    $newStatus = 'completed';
                    break;

                case 'pending':
                    $newStatus = 'pending';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $newStatus = 'cancelled';
                    break;
            }

            // Update hanya jika status berubah
            if ($payment->status !== $newStatus) {
                $updateData = [
                    'status' => $newStatus,
                    'payment_reference' => $transactionId,
                ];

                // Update payment method if available
                if ($paymentType) {
                    $updateData['midtrans_payment_method'] = $paymentType;
                }

                $payment->update($updateData);

                Log::info('Payment updated', [
                    'id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'old_status' => $payment->status,
                    'new_status' => $newStatus,
                    'transaction_id' => $transactionId
                ]);
            } else {
                Log::info('Payment status unchanged', [
                    'id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'status' => $payment->status
                ]);
            }

            Log::info('=== MIDTRANS NOTIFICATION END ===');
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Handle Midtrans callback
     */
    public function midtransCallback(Request $request)
    {
        try {
            // Log semua request masuk
            Log::info('Midtrans Callback Request:', $request->all());

            // Buat object notification
            if ($request->has('order_id') && $request->has('transaction_status')) {
                // Manual test dari Postman
                $notification = (object) $request->all();
            } else {
                try {
                    $notification = new \Midtrans\Notification();
                } catch (\Exception $e) {
                    Log::error('Midtrans Notification Error: ' . $e->getMessage());
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid notification data'
                    ], 400);
                }
            }

            // Ambil detail transaksi
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $transactionId = $notification->transaction_id ?? null;
            $fraudStatus = $notification->fraud_status ?? null;
            $paymentType = $notification->payment_type ?? null;

            // Extract payment code from order_id (order_id format: PAYMENT_CODE-TIMESTAMP)
            // The order_id is in format: DNS-YEAR-NUMBER-TIMESTAMP
            // We need to extract everything except the last part (timestamp)
            $parts = explode('-', $orderId);
            if (count($parts) > 1) {
                // Remove the last part (timestamp) and rejoin the rest
                array_pop($parts);
                $paymentCode = implode('-', $parts);
            } else {
                $paymentCode = $orderId;
            }

            // Cari pembayaran berdasarkan midtrans_order_id terlebih dahulu, kemudian payment_code
            $payment = ZakatPayment::where('midtrans_order_id', $orderId)->first();

            if (!$payment) {
                $payment = ZakatPayment::where('payment_code', $paymentCode)->first();
            }

            if (!$payment) {
                Log::warning("Midtrans notification: Payment not found for order ID {$orderId}, payment code {$paymentCode}");
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            $newStatus = $payment->status; // Default, kalau tidak berubah

            // Handle semua status
            switch ($transactionStatus) {
                case 'capture':
                    if ($paymentType === 'credit_card') {
                        if ($fraudStatus === 'challenge') {
                            $newStatus = 'pending';
                        } elseif ($fraudStatus === 'accept') {
                            $newStatus = 'completed';
                        }
                    }
                    break;

                case 'settlement':
                    $newStatus = 'completed';
                    break;

                case 'pending':
                    $newStatus = 'pending';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $newStatus = 'cancelled';
                    break;
            }

            // Update database hanya jika status berubah DAN diperbolehkan
            if ($this->canUpdateStatus($payment->status, $newStatus)) {
                if ($payment->status !== $newStatus || $payment->payment_reference !== $transactionId) {
                    $updateData = [
                        'status' => $newStatus,
                        'payment_reference' => $transactionId,
                        'midtrans_order_id' => $orderId
                    ];

                    // Save the specific Midtrans payment method if available
                    if ($paymentType) {
                        // Map Midtrans payment type to our internal format
                        $internalMethod = $this->mapMidtransToInternalMethod($paymentType);
                        if ($internalMethod) {
                            $updateData['midtrans_payment_method'] = $internalMethod;
                            // Also update the user-facing payment_method
                            $userMethod = $this->mapInternalToUserMethod($internalMethod);
                            if ($userMethod) {
                                $updateData['payment_method'] = $userMethod;
                            }
                        }
                    }

                    $payment->update($updateData);
                    Log::info("Payment updated for {$orderId}: status={$newStatus}, reference={$transactionId}");
                } else {
                    Log::info("Payment already up-to-date for {$orderId}");
                }
            } else {
                Log::info("Status update blocked: {$payment->status} -> {$newStatus} for Payment ID {$payment->id}");
            }

            // Response JSON untuk Midtrans
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show guest payment success page
     */
    public function guestSuccess($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)
            ->where('is_guest_payment', true)
            ->with(['muzakki', 'programType'])
            ->firstOrFail();

        // Pastikan konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            // Ambil status dari Midtrans menggunakan midtrans_order_id if available, otherwise payment_code
            $orderId = $payment->midtrans_order_id ?? $payment->payment_code;

            if ($orderId) {
                $status = \Midtrans\Transaction::status($orderId);
                $status = is_array($status) ? json_decode(json_encode($status)) : $status;

                if (isset($status->transaction_status)) {
                    $newStatus = match ($status->transaction_status) {
                        'capture', 'settlement' => 'completed',
                        'pending' => 'pending',
                        'deny', 'cancel', 'expire' => 'cancelled',
                        default => $payment->status,
                    };

                    // Only update if status has changed
                    if ($payment->status !== $newStatus) {
                        $payment->update(['status' => $newStatus]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to verify payment status: ' . $e->getMessage());
        }

        // Reload setelah update
        $payment->refresh();

        // Redirect jika status belum completed
        if ($payment->status !== 'completed') {
            // For pending status, show a message that payment is being processed
            if ($payment->status === 'pending') {
                return redirect()->route('guest.payment.summary', $payment->payment_code)
                    ->with('info', 'Pembayaran sedang diproses. Mohon tunggu konfirmasi.');
            }

            // For other non-completed statuses, show appropriate message
            $statusMessage = match ($payment->status) {
                'cancelled' => 'Pembayaran dibatalkan.',
                'failed' => 'Pembayaran gagal.',
                default => 'Pembayaran sedang diproses.'
            };

            return redirect()->route('guest.payment.summary', $payment->payment_code)
                ->with('warning', $statusMessage);
        }

        // Kalau sudah selesai, tampilkan halaman sukses
        return view('payments.guest-success', [
            'payment' => $payment,
            'status' => $payment->status,
        ]);
    }


    /**
     * Show guest payment failure page
     */
    public function guestFailure($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)
            ->where('is_guest_payment', true)
            ->with(['muzakki'])
            ->firstOrFail();

        return view('payments.guest-failure', compact('payment'));
    }

    /**
     * Display guest receipt (no login required)
     */
    public function guestReceipt(ZakatPayment $payment)
    {
        // Only allow access to guest payments
        if (!$payment->is_guest_payment) {
            abort(404);
        }

        $payment->load(['muzakki', 'programType']);
        return view('payments.guest-receipt', compact('payment'));
    }

    /**
     * Generate guest receipt by payment code (no login required)
     */
    public function guestReceiptByCode($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)
            ->where('is_guest_payment', true)
            ->firstOrFail();

        $payment->load(['muzakki', 'programType']);
        return view('payments.guest-receipt', compact('payment'));
    }

    /**
     * Download guest receipt as PDF
     */
    public function downloadGuestReceipt($paymentCode)
    {
        $payment = ZakatPayment::where('payment_code', $paymentCode)
            ->where('is_guest_payment', true)
            ->firstOrFail();

        $payment->load(['muzakki', 'programType']);

        // Load the PDF view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.guest-receipt-pdf', compact('payment'));

        // Set PDF options
        $pdf->setPaper('A4');

        // Download the PDF
        return $pdf->download('kwitansi-' . $paymentCode . '.pdf');
    }

    /**
     * Handle guest leave page request
     */
    public function guestLeavePage(Request $request, $paymentCode)
    {
        try {
            $payment = ZakatPayment::where('payment_code', $paymentCode)
                ->where('is_guest_payment', true)
                ->firstOrFail();

            // Update payment status to pending when user leaves the page
            if ($payment->status === 'pending') {
                $payment->update(['status' => 'pending']);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Guest Leave Page Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update payment status'], 500);
        }
    }

    /**
     * Handle guest check status request
     */
    public function guestCheckStatus($paymentCode)
    {
        try {
            $payment = ZakatPayment::where('payment_code', $paymentCode)
                ->where('is_guest_payment', true)
                ->firstOrFail();

            // Load relationships
            $payment->load(['muzakki']);

            // Call Midtrans API to get real-time status
            try {
                // Configure Midtrans
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');

                // Get transaction status from Midtrans using midtrans_order_id if available
                $orderId = $payment->midtrans_order_id ?? $payment->payment_code;
                $status = \Midtrans\Transaction::status($orderId);

                // Convert to object if it's an array
                if (is_array($status)) {
                    $status = json_decode(json_encode($status));
                }

                // Update payment status based on Midtrans response
                $newStatus = null;

                // Check if status has the required properties
                if (is_object($status) && isset($status->transaction_status)) {
                    switch ($status->transaction_status) {
                        case 'capture':
                            $newStatus = (isset($status->fraud_status) && $status->fraud_status == 'challenge') ? 'pending' : 'completed';
                            break;
                        case 'settlement':
                            $newStatus = 'completed';
                            break;
                        case 'pending':
                            $newStatus = 'pending';
                            break;
                        case 'deny':
                        case 'expire':
                        case 'cancel':
                            $newStatus = 'cancelled';
                            break;
                    }
                }

                // Update payment status if it has changed
                if ($newStatus && $payment->status !== $newStatus) {
                    $payment->update(['status' => $newStatus]);
                }

                return response()->json([
                    'success' => true,
                    'status' => $newStatus ?? $payment->status,
                    'message' => 'Status pembayaran: ' . ucfirst($newStatus ?? $payment->status)
                ]);
            } catch (\Exception $e) {
                // If Midtrans API call fails, return current database status
                Log::warning('Failed to get Midtrans status: ' . $e->getMessage());
                return response()->json([
                    'success' => true,
                    'status' => $payment->status,
                    'message' => 'Status pembayaran (dari database): ' . ucfirst($payment->status)
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Guest Check Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Pembayaran tidak ditemukan.'], 404);
        }
    }

    /**
     * Get status priority (higher number = more final status)
     */
    private function getStatusPriority($status)
    {
        $priorities = [
            'pending' => 1,
            'cancelled' => 2,
            'completed' => 3,
        ];

        return $priorities[$status] ?? 0;
    }

    /**
     * Show payment finish page
     */
    public function finish(Request $request)
    {
        return view('payments.finish', ['order_id' => $request->order_id]);
    }

    /**
     * Show payment unfinish page
     */
    public function unfinish(Request $request)
    {
        return view('payments.unfinish', ['order_id' => $request->order_id]);
    }

    /**
     * Show payment error page
     */
    public function error(Request $request)
    {
        return view('payments.error', ['order_id' => $request->order_id]);
    }

    /**
     * Check if status update is allowed (prevent downgrade)
     */
    private function canUpdateStatus($currentStatus, $newStatus)
    {
        $currentPriority = $this->getStatusPriority($currentStatus);
        $newPriority = $this->getStatusPriority($newStatus);

        // Allow update only if new status has equal or higher priority
        return $newPriority >= $currentPriority;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ZakatPayment $payment)
    {
        $zakatTypes = ZakatType::active()->get();
        $allMuzakki = Muzakki::active()->orderBy('name')->get();

        return view('payments.edit', compact('payment', 'zakatTypes', 'allMuzakki'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ZakatPayment $payment)
    {
        $request->validate([
            'muzakki_id' => 'required|exists:muzakki,id',
            'wealth_amount' => 'nullable|numeric|min:0',
            'zakat_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,check,online,bca_va,bri_va,bni_va,mandiri_va,permata_va,cimb_va,other_va,gopay,dana,shopeepay,qris,credit_card,bca_klikpay,cimb_clicks,danamon_online,bri_epay,indomaret,alfamart,akulaku',
            'payment_reference' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'hijri_year' => 'nullable|integer|min:1400|max:1500',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // Check if the authenticated user can set received_by
        $updateData = $request->except('zakat_type_id');

        if (Auth::check()) {
            $user = Auth::user();
            // Allow admin to update received_by if provided
            if ($user->role === 'admin' && $request->has('received_by')) {
                $updateData['received_by'] = $request->received_by;
            }
        } else {
            // Remove received_by from update data for non-authenticated users
            unset($updateData['received_by']);
        }

        $payment->update($updateData);

        return redirect()->route('payments.index')->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function notifications(Request $request)
    {
        // Pastikan user adalah muzakki
        if (!Auth::check() || Auth::user()->role !== 'muzakki') {
            abort(403, 'Unauthorized access');
        }

        $muzakki = Auth::user()->muzakki;
        if (!$muzakki) {
            abort(404, 'Muzakki profile not found');
        }

        $filter = $request->get('filter', 'all');

        // 🟢 1️⃣ Tandai dulu semua notifikasi belum dibaca jadi dibaca
        $muzakki->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // 🟢 2️⃣ Setelah itu baru ambil notifikasi untuk ditampilkan
        $query = $muzakki->notifications()->latest('created_at');

        if ($filter !== 'all') {
            $query->byType($filter);
        }

        $notifications = $query->paginate(10)->appends(['filter' => $filter]);

        // 🟢 3️⃣ Hitung jumlah notifikasi per tipe
        $notificationTypes = Notification::getTypesWithCounts(null, $muzakki->id);

        return view('muzakki.notifications', compact('notifications', 'notificationTypes', 'filter'));
    }


    public function markNotificationsAsRead()
    {
        $muzakki = Auth::user()->muzakki;

        if ($muzakki) {
            $muzakki->notifications()
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }




    /**
     * AJAX endpoint for muzakki notifications popup
     */
    public function ajaxNotifications(Request $request)
    {
        try {
            // Log the request for debugging
            Log::info('AJAX Notifications Request', [
                'user_id' => Auth::check() ? Auth::id() : null,
                'user_role' => Auth::check() ? Auth::user()->role : null,
                'is_authenticated' => Auth::check()
            ]);

            // Ensure user is authenticated and has muzakki role
            if (!Auth::check() || Auth::user()->role !== 'muzakki') {
                Log::warning('Unauthorized access to notifications', [
                    'user_id' => Auth::check() ? Auth::id() : null,
                    'user_role' => Auth::check() ? Auth::user()->role : null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }

            // Get the authenticated user's muzakki profile
            $muzakki = Auth::user()->muzakki;
            if (!$muzakki) {
                Log::warning('Muzakki profile not found', [
                    'user_id' => Auth::id(),
                    'user_role' => Auth::user()->role
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Muzakki profile not found'
                ]);
            }

            // Log muzakki info
            Log::info('Muzakki info', [
                'muzakki_id' => $muzakki->id,
                'user_id' => $muzakki->user_id
            ]);

            // Get latest 3 notifications for this muzakki (reduced from 8 to 3)
            $notifications = $muzakki->notifications()
                ->latest()
                ->limit(3)
                ->get();

            // Log notifications count
            Log::info('Notifications count', [
                'count' => $notifications->count()
            ]);

            // Group notifications by type for better organization
            $groupedNotifications = Notification::groupByType($notifications);

            // Render the notifications HTML
            $html = view('muzakki.partials.notifications-popup', compact('notifications', 'groupedNotifications'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading notifications: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error loading notifications: ' . $e->getMessage()
            ]);
        }
    }

    public function search(Request $request)
    {
        $query = ZakatPayment::with(['muzakki', 'zakatType']);

        if ($request->search) {
            $query->where('payment_code', 'like', '%' . $request->search . '%')
                ->orWhere('receipt_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('muzakki', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
        }

        // filter tambahan
        if ($request->zakat_type) $query->where('zakat_type_id', $request->zakat_type);
        if ($request->payment_method) $query->where('payment_method', $request->payment_method);
        if ($request->status) $query->where('status', $request->status);
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('payment_date', [$request->date_from, $request->date_to]);
        }

        $payments = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'payments' => $payments->items(),
                'pagination' => [
                    'current_page' => $payments->currentPage(),
                    'last_page' => $payments->lastPage(),
                    'from' => $payments->firstItem(),
                    'to' => $payments->lastItem(),
                    'total' => $payments->total(),
                ],
                'statistics' => [
                    'total_amount' => ZakatPayment::sum('paid_amount'),
                    'total_count' => ZakatPayment::count(),
                    'this_month' => ZakatPayment::whereMonth('payment_date', now()->month)->sum('paid_amount'),
                    'pending' => ZakatPayment::where('status', 'pending')->count(),
                ],
            ]
        ]);
    }
}
