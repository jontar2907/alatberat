@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tagihan Pembayaran Sewa Alat Berat</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Kembali ke Dashboard</a>
                        <button onclick="window.print()" class="btn btn-primary btn-sm">Cetak Invoice</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="invoice">
                        <div class="row">
                            <div class="col-6">
                                <h4>Pemohon:</h4>
                                <p><strong>{{ $rentalRequest->full_name }}</strong></p>
                                <p>Email: {{ $rentalRequest->email }}</p>
                                <p>Kode permintaan: <strong>#{{ $rentalRequest->id }}</strong></p>
                            </div>
                            <div class="col-6 text-end">
                                <h4>Status:</h4>
                                <span class="badge bg-info">Menunggu Pembayaran</span>
                            </div>
                        </div>

                        <hr>

                        <h4>Detail Penyewaan</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Alat Berat</th>
                                <td>{{ $rentalRequest->heavyEquipment->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Harga per Hari</th>
                                <td>Rp {{ number_format($rentalRequest->heavyEquipment->price ?? 0, 0, ',', '.') }}</td>
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
                            <tr>
                                <th>Transportasi</th>
                                <td>{{ $rentalRequest->transportasi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Biaya Transportasi</th>
                                <td>Rp {{ number_format($rentalRequest->transportation_cost ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Biaya Administrasi</th>
                                <td>Rp {{ number_format($rentalRequest->administration_fee ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-primary">
                                <th><strong>Total Biaya</strong></th>
                                <td><strong>Rp {{ number_format($rentalRequest->total_cost, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>

                        <div class="alert alert-info">
                            <strong>Informasi:</strong> Invoice ini telah dikirim ke email pemohon. Silakan lakukan pembayaran sesuai dengan total biaya di atas.
                        </div>

                        <div class="text-center">
                            <a href="{{ route('payment.form', $rentalRequest->id) }}" class="btn btn-success" target="_blank">
                                Lihat Form Pembayaran (untuk Pemohon)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
@media print {
    .card-tools, .btn, .alert {
        display: none !important;
    }
    .container {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection
