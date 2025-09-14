@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white py-5" style="background: linear-gradient(135deg, #f5c104ff 0%, #A8D0FF 100%);">
    <link rel="stylesheet" href="{{ asset('css/hero-center.css') }}">
    <div class="container">
        <div class="row align-items-center">
<div class="col-lg-6 hero-text-container text-start">
<h1 class="hero-title">Pelayanan Sewa Online Alat Humbang Hasundutan</h1>
<p class="hero-subtitle">
    Temukan berbagai jenis alat berat untuk kebutuhan proyek Anda, sewa dengan mudah dan harga terjangkau.
</p>
                <a href="#equipments" class="btn hero-btn">
                    <i class="fas fa-arrow-down me-2"></i>Lihat Alat Berat
                </a>
            <div class="col-lg-6 text-center" style="display:none;">
<img src="" alt="" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Equipment Section -->
<section id="equipments" class="py-5" style="background-color: #3855faff;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-5 fw-bold" style="color: #2B3A67;">Daftar Alat Berat Tersedia</h2>
            </div>
        </div>

        <div class="row">
                        @forelse($equipments as $equipment)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card shadow-sm h-100 border-0 equipment-card" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(74, 111, 165, 0.15);">
                                    @if($equipment->image)
@php
    $imagePath = $equipment->image;
    if (!str_starts_with($imagePath, 'equipments/')) {
        $imagePath = 'equipments/' . basename($imagePath);
    }
@endphp
<div class="image-wrapper" style="height: 200px; width: 100%; overflow: hidden; border-top-left-radius: 12px; border-top-right-radius: 12px;">
    <img src="{{ asset('storage/' . $imagePath) }}"
         class="card-img-top"
         alt="{{ $equipment->name }}"
         style="width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 0;">
</div>
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-primary" style="height: 200px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                            <i class="fas fa-tools fa-3x"></i>
                                        </div>
                                    @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold" style="color: #4A6FA5;">{{ $equipment->name }}</h5>
                            <p class="card-text flex-grow-1" style="color: #6B7A99;">
                                {{ $equipment->description ?? 'Deskripsi tidak tersedia' }}
                            </p>
                            <div class="mt-3">
<p class="mb-1" style="color: #7BB661; font-size: 1.25rem; font-weight: 700;">
    <strong>Rp{{ number_format($equipment->price, 0, ',', '.') }}</strong>
    {{-- Removed the "/ hari" text --}}
</p>
<p class="mb-2 jenis-sewa-text" style="font-size: 1.1rem; font-weight: 700;">
    <small><em>Jenis Sewa: {{ $equipment->jenis_sewa }}</em></small>
</p>
                                <a href="{{ route('rental.request', $equipment->id) }}" class="btn" style="background-color: #A8D0FF; color: #2B3A67; font-weight: 600; width: 100%; border-radius: 6px; box-shadow: 0 4px 8px rgba(168, 208, 255, 0.5); transition: background-color 0.3s ease;">
                                    <i class="fas fa-handshake me-2"></i>Ajukan Sewa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5" style="color: #A3B1C2;">
                        <i class="fas fa-tools fa-4x mb-3"></i>
                        <h4>Belum ada alat berat yang tersedia</h4>
                        <p>Silakan kembali lagi nanti untuk melihat daftar alat berat terbaru.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Footer -->
<footer style="background-color: #A8D0FF; color: #2B3A67;" class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>Dasboard</h5>
                <p>Layanan sewa alat berat terpercaya untuk semua kebutuhan proyek Anda.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>&copy; 2024 Sewa Alat Berat. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<style>
.equipment-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(74, 111, 165, 0.15);
}

.equipment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(74, 111, 165, 0.3) !important;
}

.hero-section {
    min-height: 15vh;
    display: flex;
    align-items: center;
    font-family: 'Poppins', sans-serif;
    color: #2B3A67;
}

.hero-text-container {
    max-width: 450px;
    margin: 0;
}

.hero-title {
    font-weight: 700;
    font-size: 2.5rem;
    white-space: nowrap;
    margin-bottom: 1rem;
    color: #2B3A67;
}

.hero-subtitle {
    font-weight: 400;
    font-size: 1.1rem;
    color: #4A6FA5;
    margin-bottom: 2rem;
    white-space: nowrap;
}

.hero-btn {
    background-color: #f5ba0a;
    color: #2B3A67;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 8px;
    box-shadow: 0 6px 12px rgba(245, 186, 10, 0.5);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.hero-btn:hover {
    background-color: #ffd24d;
    box-shadow: 0 8px 16px rgba(255, 210, 77, 0.7);
    color: #2B3A67;
}

@media (max-width: 767.98px) {
    .hero-title {
        font-size: 1.8rem;
        white-space: normal;
    }
    .hero-subtitle {
        font-size: 0.95rem;
    }
    .hero-btn {
        padding: 10px 25px;
        font-size: 1rem;
    }
    .equipment-card {
        margin-bottom: 1.5rem;
    }
}
.image-wrapper {
    height: 200px;
    width: 100%;
    overflow: hidden;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}
</style>
@endsection
