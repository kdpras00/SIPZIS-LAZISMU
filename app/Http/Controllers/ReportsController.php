<?php

namespace App\Http\Controllers;

use App\Models\ZakatPayment;
use App\Models\ZakatDistribution;
use App\Models\Mustahik;
use App\Models\ZakatType;
use App\Models\ProgramType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function incoming(Request $request)
    {
        // Build query for zakat payments
        $query = ZakatPayment::with(['muzakki', 'programType', 'receivedBy']);

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

        // Filter by program type
        if ($request->has('program_type') && $request->program_type != '') {
            $query->where('program_type_id', $request->program_type);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Check if export is requested
        if ($request->has('export')) {
            $exportFormat = $request->get('export');
            Log::info('Export requested', ['format' => $exportFormat]);

            $payments = $query->latest('payment_date')->get();
            return $this->exportIncomingReport($payments, $exportFormat);
        }

        // Get paginated results
        $payments = $query->latest('payment_date')->paginate(15)->withQueryString();

        // Get summary statistics
        $stats = [
            'total_amount' => ZakatPayment::completed()->sum('paid_amount'),
            'total_count' => ZakatPayment::completed()->count(),
            'this_month' => ZakatPayment::completed()->whereMonth('payment_date', date('m'))->sum('paid_amount'),
            'pending' => ZakatPayment::where('status', 'pending')->count(),
        ];

        // Get program types for filter dropdown
        $programTypes = ProgramType::all();

        return view('reports.incoming', compact(
            'payments',
            'stats',
            'programTypes'
        ));
    }

    public function outgoing(Request $request)
    {
        // Log the request for debugging
        Log::info('Outgoing report request', [
            'query_params' => $request->all(),
            'has_export' => $request->has('export'),
            'export_value' => $request->get('export')
        ]);

        // Build query for zakat distributions
        $query = ZakatDistribution::with(['mustahik', 'distributedBy']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('distribution_code', 'like', "%{$search}%")
                    ->orWhere('program_name', 'like', "%{$search}%")
                    ->orWhereHas('mustahik', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by mustahik category
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('mustahik', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Filter by distribution type
        if ($request->has('distribution_type') && $request->distribution_type != '') {
            $query->where('distribution_type', $request->distribution_type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('distribution_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('distribution_date', '<=', $request->date_to);
        }

        // Check if export is requested
        if ($request->has('export')) {
            $exportFormat = $request->get('export');
            Log::info('Export requested', ['format' => $exportFormat]);

            $distributions = $query->latest('distribution_date')->get();
            return $this->exportOutgoingReport($distributions, $exportFormat);
        }

        // Get paginated results
        $distributions = $query->latest('distribution_date')->paginate(15)->withQueryString();

        // Get filter options and statistics
        $categories = array_keys(Mustahik::CATEGORIES);

        $stats = [
            'total_amount' => ZakatDistribution::sum('amount'),
            'total_count' => ZakatDistribution::count(),
            'this_month' => ZakatDistribution::whereMonth('distribution_date', date('m'))->sum('amount'),
            'pending_receipt' => ZakatDistribution::where('is_received', false)->count(),
            'available_balance' => ZakatPayment::completed()->sum('paid_amount') - ZakatDistribution::sum('amount'),
        ];

        return view('reports.outgoing', compact(
            'distributions',
            'stats',
            'categories'
        ));
    }

    private function exportIncomingReport($payments, $format)
    {
        Log::info('Exporting incoming report', ['format' => $format, 'payment_count' => $payments->count()]);

        $data = [
            'title' => 'Laporan Zakat Masuk',
            'date' => date('d/m/Y'),
            'payments' => $payments,
            'total_amount' => $payments->sum('paid_amount'),
            'total_count' => $payments->count()
        ];

        if ($format === 'pdf') {
            Log::info('Generating PDF for incoming report');
            try {
                $pdf = Pdf::loadView('reports.exports.incoming-pdf', $data);
                return $pdf->download('laporan-zakat-masuk.pdf');
            } catch (\Exception $e) {
                Log::error('PDF generation error', ['error' => $e->getMessage()]);
                throw $e;
            }
        } elseif ($format === 'excel') {
            Log::info('Generating Excel for incoming report');
            return $this->exportIncomingToExcel($payments);
        }

        return redirect()->back();
    }

    private function exportOutgoingReport($distributions, $format)
    {
        Log::info('Exporting outgoing report', ['format' => $format, 'distribution_count' => $distributions->count()]);

        $data = [
            'title' => 'Laporan Zakat Keluar',
            'date' => date('d/m/Y'),
            'distributions' => $distributions,
            'total_amount' => $distributions->sum('amount'),
            'total_count' => $distributions->count()
        ];

        if ($format === 'pdf') {
            Log::info('Generating PDF for outgoing report');
            try {
                $pdf = Pdf::loadView('reports.exports.outgoing-pdf', $data);
                return $pdf->download('laporan-zakat-keluar.pdf');
            } catch (\Exception $e) {
                Log::error('PDF generation error', ['error' => $e->getMessage()]);
                throw $e;
            }
        } elseif ($format === 'excel') {
            Log::info('Generating Excel for outgoing report');
            return $this->exportOutgoingToExcel($distributions);
        }

        return redirect()->back();
    }

    private function exportIncomingToExcel($payments)
    {
        // Create a simple CSV export as an alternative to Excel
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-zakat-masuk.csv"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Kode Pembayaran', 'Nama Muzakki', 'Jenis Zakat', 'Metode Pembayaran', 'Nominal', 'Tanggal', 'Status']);

            foreach ($payments as $index => $payment) {
                fputcsv($file, [
                    $index + 1,
                    $payment->payment_code,
                    $payment->muzakki->name,
                    '-',
                    $this->getPaymentMethodLabel($payment->payment_method),
                    'Rp ' . number_format($payment->paid_amount, 0, ',', '.'),
                    $payment->payment_date->format('d M Y'),
                    $this->getPaymentStatusLabel($payment->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportOutgoingToExcel($distributions)
    {
        // Create a simple CSV export as an alternative to Excel
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-zakat-keluar.csv"',
        ];

        $callback = function () use ($distributions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Kode Distribusi', 'Nama Mustahik', 'Kategori', 'Jenis Distribusi', 'Nominal', 'Tanggal', 'Status']);

            foreach ($distributions as $index => $distribution) {
                fputcsv($file, [
                    $index + 1,
                    $distribution->distribution_code,
                    $distribution->mustahik->name,
                    Mustahik::CATEGORIES[$distribution->mustahik->category] ?? $distribution->mustahik->category,
                    $this->getDistributionTypeLabel($distribution->distribution_type),
                    'Rp ' . number_format($distribution->amount, 0, ',', '.'),
                    $distribution->distribution_date->format('d M Y'),
                    $distribution->is_received ? 'Sudah Diterima' : 'Belum Diterima'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getPaymentMethodLabel($method)
    {
        $labels = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            'check' => 'Cek',
            'online' => 'Online'
        ];
        return $labels[$method] ?? $method;
    }

    private function getPaymentStatusLabel($status)
    {
        $labels = [
            'completed' => 'Selesai',
            'pending' => 'Pending',
            'cancelled' => 'Batal'
        ];
        return $labels[$status] ?? $status;
    }

    private function getDistributionTypeLabel($type)
    {
        $labels = [
            'cash' => 'Tunai',
            'goods' => 'Barang',
            'voucher' => 'Voucher',
            'service' => 'Layanan'
        ];
        return $labels[$type] ?? $type;
    }
}
