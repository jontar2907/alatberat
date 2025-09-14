@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="mb-4 text-center fw-bold text-primary">Upload Bukti Pembayaran</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Tampilkan pesan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tampilkan pesan sukses --}}
            @if (session('success'))
                <div class="alert alert-success rounded-3 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tampilkan pesan error --}}
            @if (session('error'))
                <div class="alert alert-danger rounded-3 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-warning text-dark text-center rounded-top-4">
                    <h5 class="mb-0 fw-bold">💰 Rincian Tagihan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-2">
                        <div class="col-6"><strong>Nama Alat:</strong></div>
                        <div class="col-6 text-end">{{ $rentalRequest->heavyEquipment->name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><strong>Deskripsi:</strong></div>
                        <div class="col-6 text-end">{{ $rentalRequest->heavyEquipment->description }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><strong>Harga per Hari:</strong></div>
                        <div class="col-6 text-end">Rp{{ number_format($rentalRequest->heavyEquipment->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><strong>Jumlah Hari:</strong></div>
                        <div class="col-6 text-end">{{ $rentalRequest->jumlah_hari }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6"><strong>Biaya Transportasi:</strong></div>
                        <div class="col-6 text-end">Rp{{ number_format($rentalRequest->transportation_cost, 0, ',', '.') }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6"><strong>Total Biaya:</strong></div>
                        <div class="col-6 text-end fw-bold text-primary">Rp{{ number_format($rentalRequest->total_cost, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-header bg-info text-white text-center rounded-top-4">
                    <h5 class="mb-0 fw-bold">📋 Cara Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <p>Silakan lakukan pembayaran ke rekening berikut:</p>
                    <ul>
                        <li>Bank Sumut :</li>
                        
                    </ul>
                    <p>Setelah melakukan pembayaran, unggah bukti pembayaran di bawah ini untuk verifikasi.</p>
                </div>
            </div>

            {{-- Form Upload Bukti Pembayaran --}}
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h5 class="mb-0">Upload Bukti Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('payment.process', $rentalRequest->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="payment_proof" class="form-label fw-bold">Pilih File Bukti Pembayaran</label>
                            <input type="file" class="form-control @error('payment_proof') is-invalid @enderror"
                                   name="payment_proof" id="payment_proof" accept="image/*" required>
                            <div class="form-text">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.</div>
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success px-5 py-2 rounded-3 fw-bold">
                                📤 Upload Bukti Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
