<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
        }

        .summary {
            margin: 20px 0;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }

        .summary-table th {
            background-color: #f2f2f2;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }

        .data-table th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Dihasilkan pada: {{ $date }}</p>
    </div>

    <div class="summary">
        <table class="summary-table">
            <tr>
                <th>Total Pembayaran</th>
                <td>{{ number_format($total_count, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Nominal</th>
                <td>Rp {{ number_format($total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pembayaran</th>
                <th>Nama Muzakki</th>
                <th>Jenis Zakat</th>
                <th>Metode Pembayaran</th>
                <th>Nominal</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $index => $payment)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $payment->payment_code }}</td>
                <td>{{ $payment->muzakki?->name ?? '-' }}</td>
                <td>{{ $payment->zakatType?->name ?? '-' }}</td>
                <td>{{ $payment->programType ? $payment->programType->name : 'Donasi Umum' }}</td>
                <td>
                    @switch($payment->payment_method)
                    @case('cash')
                    Tunai
                    @break
                    @case('transfer')
                    Transfer
                    @break
                    @case('check')
                    Cek
                    @break
                    @case('online')
                    Online
                    @break
                    @endswitch
                </td>
                <td class="text-right">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</td>
                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                <td>
                    @if($payment->status == 'completed')
                    Selesai
                    @elseif($payment->status == 'pending')
                    Pending
                    @else
                    Batal
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Laporan ini dihasilkan oleh Sistem Manajemen Zakat
    </div>
</body>

</html>