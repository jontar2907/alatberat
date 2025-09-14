@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pembayaran Sewa Alat Berat</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.payments') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar Pembayaran</a>
                        <button onclick="window.print()" class="btn btn-primary btn-sm">Cetak Detail</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="invoice">
                        <div class="row">
                            <div class="col-6">
                                <h4>Pemohon:</h4>
                                <p><strong>{{ $payment->rentalRequest->full_name }}</strong></p>
                                <p>Email: {{ $payment->rentalRequest->email }}</p>
                                <p>Kode permintaan: <strong>#{{ $payment->rentalRequest->id }}</strong></p>
                            </div>
                            <div class="col-6 text-end">
                                <h4>Status Pembayaran:</h4>
                                @if($payment->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($payment->status == 'verified')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($payment->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($payment->status == 'processing')
                                    <span class="badge bg-info text-dark">Sedang Diproses</span>
                                @else
                                    <span class="badge bg-secondary">Status Tidak Diketahui</span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <h4>Detail Penyewaan</h4>
                        <table class="table table-bordered">
                            <tr>
                                <th>Alat Berat</th>
                                <td>{{ $payment->rentalRequest->heavyEquipment->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Harga per Hari</th>
                                <td>Rp {{ number_format($payment->rentalRequest->heavyEquipment->price ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Hari</th>
                                <td>{{ $payment->rentalRequest->jumlah_pemakaian }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai</th>
                                <td>{{ $payment->rentalRequest->start_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Selesai</th>
                                <td>{{ $payment->rentalRequest->end_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Transportasi</th>
                                <td>{{ $payment->rentalRequest->transportasi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Biaya Transportasi</th>
                                <td>Rp {{ number_format($payment->rentalRequest->transportation_cost ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Biaya Administrasi</th>
                                <td>Rp {{ number_format($payment->rentalRequest->administration_fee ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-primary">
                                <th><strong>Total Biaya</strong></th>
                                <td><strong>Rp {{ number_format($payment->rentalRequest->total_cost, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>

                        <h4>Bukti Pembayaran</h4>
                        @if($payment->payment_proof)
                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid" style="max-width: 300px;">
                            </a>
                        @else
                            <p>Tidak ada bukti pembayaran.</p>
                        @endif

                        @if($payment->keterangan)
                            <div class="alert alert-info mt-3">
                                <strong>Keterangan:</strong> {{ $payment->keterangan }}
                            </div>
                        @endif
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
