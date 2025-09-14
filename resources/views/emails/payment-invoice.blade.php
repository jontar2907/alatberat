<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tagihan Pembayaran Sewa Alat Berat</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .bill-table { border-collapse: collapse; width: 100%; }
        .bill-table th, .bill-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .bill-table th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 18px; }
    </style>
</head>
<body>
    <h2>Tagihan Pembayaran Sewa Alat Berat</h2>
    <p>Pemohon: <strong>{{ $rentalRequest->full_name }}</strong></p>
    <p>Email: <strong>{{ $rentalRequest->email }}</strong></p>
    <p>Kode permintaan: <strong>#{{ $rentalRequest->id }}</strong></p>

    <h3>Detail Penyewaan</h3>
    <table class="bill-table">
        <tr>
            <th>Alat Berat</th>
            <td>{{ $rentalRequest->heavyEquipment->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Harga per Hari</th>
            <td>Rp {{ number_format($rentalRequest->heavyEquipment->price_per_day ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Jumlah Hari</th>
            <td>{{ $rentalRequest->jumlah_pemakaian }}</td>
        </tr>
        <tr>
            <th>Tanggal Mulai</th>
            <td>{{ $rentalRequest->start_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Tanggal Selesai</th>
            <td>{{ $rentalRequest->end_date->format('d M Y') }}</td>
        </tr>
        <tr class="total">
            <th>Total Biaya</th>
            <td>Rp {{ number_format($rentalRequest->total_cost, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p>Silakan lakukan pembayaran sesuai dengan total biaya di atas.</p>
    <p>Untuk melanjutkan pembayaran, klik link berikut:</p>
    <p><a href="{{ route('payment.form', $rentalRequest->id) }}">Lanjutkan Pembayaran</a></p>

    <p>Terima kasih telah menggunakan layanan kami.</p>
</body>
</html>
