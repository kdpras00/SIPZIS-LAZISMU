<?php

namespace App\Http\Controllers;

use App\Models\ZakatDistribution;
use App\Models\Mustahik;
use App\Models\ZakatPayment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ZakatDistributionController extends Controller
{
    /**
     * Hitung saldo zakat yang tersedia (uang).
     * Mengembalikan 0 jika hasil negatif.
     */
    public static function availableBalance(): float
    {
        $paid = ZakatPayment::completed()->sum('paid_amount');
        $distributed = ZakatDistribution::where('distribution_type', 'cash')->sum('amount');
        $balance = $paid - $distributed;
        return $balance > 0 ? $balance : 0;
    }

    /**
     * Tampilkan daftar distribusi zakat dengan filter & statistik.
     */
    public function index(Request $request)
    {
        $query = ZakatDistribution::with(['mustahik', 'distributedBy']);

        // Filter pencarian umum
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                    ->orWhere('program_name', 'like', "%{$search}%")
                    ->orWhereHas('mustahik', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter kategori mustahik
        if ($category = $request->get('category')) {
            $query->whereHas('mustahik', fn($q) => $q->where('category', $category));
        }

        // Filter jenis distribusi
        if ($type = $request->get('distribution_type')) {
            $query->where('distribution_type', $type);
        }

        // Filter program zakat
        if ($program = $request->get('program')) {
            $query->where('program_name', 'like', "%{$program}%");
        }

        // Filter status penerimaan
        if ($request->filled('received_status')) {
            $query->where('is_received', $request->received_status === 'received');
        }

        // Filter tanggal distribusi
        if ($from = $request->get('date_from')) {
            $query->whereDate('distribution_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('distribution_date', '<=', $to);
        }

        $distributions = $query->latest('distribution_date')->paginate(15)->withQueryString();

        // Data tambahan
        $categories = array_keys(Mustahik::CATEGORIES);
        $programs = ZakatDistribution::select('program_name')
            ->distinct()
            ->whereNotNull('program_name')
            ->pluck('program_name');

        // Statistik utama
        $stats = [
            'total_amount' => ZakatDistribution::sum('amount'),
            'total_count' => ZakatDistribution::count(),
            'this_month' => ZakatDistribution::whereMonth('distribution_date', date('m'))->sum('amount'),
            'pending_receipt' => ZakatDistribution::where('is_received', false)->count(),
            'available_balance' => self::availableBalance(),
        ];

        return view('distributions.index', compact('distributions', 'categories', 'programs', 'stats'));
    }

    /**
     * Form tambah distribusi baru.
     */
    public function create(Request $request)
    {
        $mustahikId = $request->get('mustahik_id');
        $mustahik = $mustahikId ? Mustahik::verified()->findOrFail($mustahikId) : null;

        $allMustahik = Mustahik::verified()->active()->orderBy('name')->get();
        $categories = array_keys(Mustahik::CATEGORIES);
        $availableBalance = self::availableBalance();

        return view('distributions.create', compact('mustahik', 'allMustahik', 'categories', 'availableBalance'));
    }

    /**
     * Simpan distribusi zakat baru.
     * Menghindari saldo minus.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mustahik_id' => 'required|exists:mustahik,id',
            'amount' => 'required|numeric|min:0',
            'distribution_type' => 'required|in:cash,goods,voucher,service',
            'goods_description' => 'required_if:distribution_type,goods,service|nullable|string',
            'distribution_date' => 'required|date',
            'notes' => 'nullable|string',
            'program_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        // Get amount and ensure it's numeric (remove any formatting)
        $amount = $request->input('amount');
        if ($amount && $amount !== '') {
            $amount = str_replace(['.', ','], '', $amount);
            $amount = is_numeric($amount) ? (float)$amount : 0;
        } else {
            $amount = 0;
        }

        $mustahik = Mustahik::verified()->active()->findOrFail($request->mustahik_id);

        return DB::transaction(function () use ($request, $mustahik, $amount) {

            // Lock database agar saldo akurat saat concurrent
            $paid = ZakatPayment::completed()->lockForUpdate()->sum('paid_amount');
            $distributed = ZakatDistribution::where('distribution_type', 'cash')
                ->lockForUpdate()
                ->sum('amount');
            $available = max(0, $paid - $distributed);

            if ($request->distribution_type === 'cash' && $amount > $available) {
                return back()->withInput()->with('error', 'Saldo zakat tidak mencukupi.');
            }

            $distributionCode = ZakatDistribution::generateDistributionCode();

            // Determine program_id from request
            $programId = null;
            if ($request->filled('program_id')) {
                $programId = $request->program_id;
            } elseif ($request->filled('program_slug')) {
                $program = \App\Models\Program::where('slug', $request->program_slug)->first();
                if ($program) {
                    $programId = $program->id;
                }
            }

            $distribution = ZakatDistribution::create([
                'distribution_code' => $distributionCode,
                'mustahik_id' => $mustahik->id,
                'amount' => $amount,
                'distribution_type' => $request->distribution_type,
                'goods_description' => $request->goods_description,
                'distribution_date' => $request->distribution_date,
                'notes' => $request->notes,
                'program_id' => $programId,
                'program_name' => $request->program_name,
                'distributed_by' => Auth::id(),
                'location' => $request->location,
                'is_received' => false,
            ]);

            // Kirim notifikasi penyaluran kepada semua muzakki yang memiliki akun pengguna
            $registeredMuzakki = \App\Models\Muzakki::whereNotNull('user_id')->get();

            foreach ($registeredMuzakki as $muzakki) {
                Notification::createDistributionNotification($muzakki, $distribution);
            }

            return redirect()->route('distributions.index')->with('success', 'Distribusi zakat berhasil dicatat.');
        });
    }

    /**
     * Tampilkan detail distribusi zakat.
     */
    public function show(ZakatDistribution $distribution)
    {
        $distribution->load(['mustahik', 'distributedBy', 'program']);

        return view('distributions.show', compact('distribution'));
    }

    /**
     * Form edit distribusi zakat.
     */
    public function edit(ZakatDistribution $distribution)
    {
        $allMustahik = Mustahik::verified()->active()->orderBy('name')->get();
        $categories = array_keys(Mustahik::CATEGORIES);
        $availableBalance = self::availableBalance();

        return view('distributions.edit', compact('distribution', 'allMustahik', 'categories', 'availableBalance'));
    }

    /**
     * Update distribusi zakat yang ada.
     */
    public function update(Request $request, ZakatDistribution $distribution)
    {
        $request->validate([
            'mustahik_id' => 'required|exists:mustahik,id',
            'amount' => 'required|numeric|min:0',
            'distribution_type' => 'required|in:cash,goods,voucher,service',
            'goods_description' => 'required_if:distribution_type,goods,service|nullable|string',
            'distribution_date' => 'required|date',
            'notes' => 'nullable|string',
            'program_id' => 'nullable|exists:programs,id',
            'program_slug' => 'nullable|string|exists:programs,slug',
            'program_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        // Get amount and ensure it's numeric (remove any formatting)
        $amount = $request->input('amount');
        if ($amount && $amount !== '') {
            $amount = str_replace(['.', ','], '', $amount);
            $amount = is_numeric($amount) ? (float)$amount : 0;
        } else {
            $amount = 0;
        }

        return DB::transaction(function () use ($request, $distribution, $amount) {

            // Hitung ulang saldo tanpa menghitung distribusi yang sedang diubah
            $paid = ZakatPayment::completed()->lockForUpdate()->sum('paid_amount');
            $distributed = ZakatDistribution::where('id', '!=', $distribution->id)
                ->where('distribution_type', 'cash')
                ->lockForUpdate()
                ->sum('amount');

            $available = max(0, $paid - $distributed);

            if ($request->distribution_type === 'cash' && $amount > $available) {
                return back()->withInput()->with('error', 'Saldo zakat tidak mencukupi.');
            }

            // Determine program_id from request
            $programId = null;
            if ($request->filled('program_id')) {
                $programId = $request->program_id;
            } elseif ($request->filled('program_slug')) {
                $program = \App\Models\Program::where('slug', $request->program_slug)->first();
                if ($program) {
                    $programId = $program->id;
                }
            }

            $data = $request->all();
            $data['amount'] = $amount;
            $data['program_id'] = $programId;
            $distribution->update($data);
            return redirect()->route('distributions.index')->with('success', 'Distribusi zakat berhasil diperbarui.');
        });
    }

    /**
     * Tandai distribusi sebagai sudah diterima.
     */
    public function markAsReceived(Request $request, ZakatDistribution $distribution)
    {
        $request->validate([
            'received_by_name' => 'nullable|string|max:255',
            'received_notes' => 'nullable|string',
        ]);

        if ($distribution->is_received) {
            return back()->with('error', 'Distribusi ini sudah ditandai sebagai diterima.');
        }

        $distribution->update([
            'is_received' => true,
            'received_date' => now(),
            'received_by_name' => $request->received_by_name,
            'received_notes' => $request->received_notes,
        ]);

        return redirect()->route('distributions.show', $distribution)
            ->with('success', 'Distribusi berhasil ditandai sebagai sudah diterima.');
    }

    /**
     * Hapus distribusi zakat.
     * Tidak bisa dihapus jika sudah diterima.
     */
    public function destroy(ZakatDistribution $distribution)
    {
        if ($distribution->is_received) {
            return back()->with('error', 'Distribusi yang sudah diterima tidak dapat dihapus.');
        }

        $distribution->delete();
        return redirect()->route('distributions.index')->with('success', 'Data distribusi berhasil dihapus.');
    }

    /**
     * Tampilkan kwitansi distribusi zakat.
     */
    public function receipt(ZakatDistribution $distribution)
    {
        $distribution->load(['mustahik', 'distributedBy']);

        return view('distributions.receipt', compact('distribution'));
    }

    /**
     * Search API untuk distribusi zakat dengan filter dan statistik.
     */
    public function search(Request $request)
    {
        $query = ZakatDistribution::with(['mustahik', 'distributedBy']);

        // Filter pencarian umum
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                    ->orWhere('program_name', 'like', "%{$search}%")
                    ->orWhereHas('mustahik', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter kategori mustahik
        if ($category = $request->get('category')) {
            $query->whereHas('mustahik', fn($q) => $q->where('category', $category));
        }

        // Filter jenis distribusi
        if ($type = $request->get('distribution_type')) {
            $query->where('distribution_type', $type);
        }

        // Filter program zakat
        if ($program = $request->get('program')) {
            $query->where('program_name', 'like', "%{$program}%");
        }

        // Filter status penerimaan
        if ($request->has('received_status') && $request->received_status !== '') {
            $query->where('is_received', $request->received_status === 'received');
        }

        // Filter tanggal distribusi
        if ($from = $request->get('date_from')) {
            $query->whereDate('distribution_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('distribution_date', '<=', $to);
        }

        $distributions = $query->latest('distribution_date')->paginate(15);

        // Statistik berdasarkan filter yang sama
        $statsQuery = ZakatDistribution::query();
        
        // Apply same filters for statistics
        if ($search = $request->get('search')) {
            $statsQuery->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                    ->orWhere('program_name', 'like', "%{$search}%")
                    ->orWhereHas('mustahik', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }
        if ($category = $request->get('category')) {
            $statsQuery->whereHas('mustahik', fn($q) => $q->where('category', $category));
        }
        if ($type = $request->get('distribution_type')) {
            $statsQuery->where('distribution_type', $type);
        }
        if ($program = $request->get('program')) {
            $statsQuery->where('program_name', 'like', "%{$program}%");
        }
        if ($request->has('received_status') && $request->received_status !== '') {
            $statsQuery->where('is_received', $request->received_status === 'received');
        }
        if ($from = $request->get('date_from')) {
            $statsQuery->whereDate('distribution_date', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $statsQuery->whereDate('distribution_date', '<=', $to);
        }

        $stats = [
            'total_amount' => (clone $statsQuery)->sum('amount'),
            'total_count' => (clone $statsQuery)->count(),
            'this_month' => (clone $statsQuery)->whereMonth('distribution_date', date('m'))->sum('amount'),
            'pending_receipt' => (clone $statsQuery)->where('is_received', false)->count(),
            'available_balance' => self::availableBalance(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'distributions' => $distributions->items(),
                'pagination' => [
                    'current_page' => $distributions->currentPage(),
                    'last_page' => $distributions->lastPage(),
                    'from' => $distributions->firstItem(),
                    'to' => $distributions->lastItem(),
                    'total' => $distributions->total(),
                ],
                'statistics' => $stats,
            ]
        ]);
    }
}
