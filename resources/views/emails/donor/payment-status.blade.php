<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran Donasi Anda</title>
</head>

<body
    style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div
            style="
            @if ($status == 'completed') background: #0d8a00;
            @elseif($status == 'pending')
                background: #ff9800;
            @else
                background: #f44336; @endif
            color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">SIPZIS</h1>
            <p style="margin: 5px 0 0; font-size: 16px;">Sistem Informasi Pengelolaan Zakat</p>
        </div>

        <!-- Content -->
        <div style="padding: 30px;">
            <h2
                style="
                @if ($status == 'completed') color: #0d8a00;
                @elseif($status == 'pending')
                    color: #ff9800;
                @else
                    color: #f44336; @endif
                margin-top: 0;">
                {{ $statusMessage }}</h2>

            <p>Assalamu'alaikum wr. wb.</p>

            <p>Kami ingin menginformasikan status pembayaran donasi Anda. Berikut detail pembayaran Anda:</p>

            <div
                style="background: #f9f9f9; 
                @if ($status == 'completed') border-left: 4px solid #0d8a00;
                @elseif($status == 'pending')
                    border-left: 4px solid #ff9800;
                @else
                    border-left: 4px solid #f44336; @endif
                padding: 15px; margin: 20px 0;">
                <p><strong>Kode Pembayaran:</strong> {{ $payment->payment_code }}</p>
                <p><strong>Jumlah Donasi:</strong> Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</p>
                <p><strong>Tujuan Program:</strong> {{ ucfirst(str_replace('-', ' ', $payment->program_category)) }}</p>
                <p><strong>Waktu Transaksi:</strong> {{ $payment->payment_date->format('d F Y H:i') }}</p>
                <p><strong>Status:</strong>
                    @if ($status == 'completed')
                        <span style="color: #0d8a00; font-weight: bold;">Berhasil</span>
                    @elseif($status == 'pending')
                        <span style="color: #ff9800; font-weight: bold;">Menunggu Konfirmasi</span>
                    @else
                        <span style="color: #f44336; font-weight: bold;">Gagal</span>
                    @endif
                </p>
            </div>

            @if ($status == 'completed')
                <p>Alhamdulillah, pembayaran donasi Anda telah berhasil diverifikasi. Donasi Anda akan segera kami
                    salurkan kepada para mustahik sesuai dengan ketentuan syariat Islam.</p>

                <div
                    style="background: #e8f5e9; border: 2px solid #0d8a00; border-radius: 5px; padding: 20px; margin: 20px 0; text-align: center;">
                    <p style="margin: 0 0 15px 0; font-weight: bold; color: #0d8a00;">📄 Kwitansi Pembayaran</p>
                    <p style="margin: 0; font-size: 14px;">Kwitansi digital dalam format PDF telah dilampirkan pada
                        email ini.
                        Silakan buka attachment PDF untuk melihat kwitansi pembayaran Anda.</p>
                    <p style="margin: 15px 0 0 0; font-size: 12px; color: #666;">
                        Kwitansi juga akan dikirim melalui WhatsApp Anda.
                    </p>
                </div>
            @elseif($status == 'pending')
                <p>Pembayaran donasi Anda sedang dalam proses verifikasi. Mohon menunggu konfirmasi selanjutnya. Jika
                    Anda memiliki bukti transfer, silakan kirimkan ke admin@sipzis.id</p>
            @else
                <p>Mohon maaf, pembayaran donasi Anda gagal diproses. Silakan coba kembali atau hubungi admin@sipzis.id
                    untuk bantuan lebih lanjut.</p>
            @endif

            <p style="margin-top: 30px;">Wallahu a'lam bishawab.</p>

            <p>Wassalamu'alaikum wr. wb.</p>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <p style="margin: 0;"><strong>Yayasan SIPZIS</strong></p>
                <p style="margin: 5px 0 0;">Jl. Contoh Alamat No. 123</p>
                <p style="margin: 0;">Jakarta, Indonesia</p>
                <p style="margin: 5px 0 0;">Email: info@sipzis.id</p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #666;">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} SIPZIS. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
