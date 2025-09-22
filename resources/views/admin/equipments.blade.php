@extends('layouts.app')

@section('content')
@php
    $isAdminEquipmentsPage = request()->routeIs('admin.equipments') || request()->routeIs('admin.equipments.*');
@endphp
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Kelola Alat Berat</h2>
        </div>
        <div class="col-md-6 text-end">
            @if($isAdminEquipmentsPage)
                <a href="{{ route('admin.equipments.export') }}" class="btn btn-success me-2">
                    <i class="fas fa-download"></i> Export
                </a>
                <button class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-upload"></i> Import
                </button>
                <a href="{{ route('admin.equipments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Alat Berat
                </a>
            @else
                <button class="btn btn-success me-2" disabled>
                    <i class="fas fa-download"></i> Export
                </button>
                <button class="btn btn-info me-2" disabled>
                    <i class="fas fa-upload"></i> Import
                </button>
                <button class="btn btn-primary" disabled>
                    <i class="fas fa-plus"></i> Tambah Alat Berat
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jenis Sewa</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipments as $equipment)
                        <tr>
                            <td>
                                @if($equipment->image)
                                    <img src="{{ asset('storage/' . $equipment->image) }}"
                                         alt="{{ $equipment->name }}"
                                         width="80" height="60"
                                         style="object-fit: cover;"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <span class="text-muted" style="display:none;">No image</span>
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>{{ $equipment->name }}</td>
                            <td>Rp {{ number_format($equipment->price, 0, ',', '.') }}</td>
                            <td>{{ $equipment->jenis_sewa ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $equipment->availability ? 'success' : 'danger' }}">
                                    {{ $equipment->availability ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </td>
                            <td>
                                @if($isAdminEquipmentsPage)
                                    <a href="{{ route('admin.equipments.edit', $equipment->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.equipments.destroy', $equipment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus alat berat ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-warning" disabled>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" disabled>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
@if($isAdminEquipmentsPage)
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Alat Berat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.equipments.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control @error('excel_file') is-invalid @enderror" 
                               id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                        @error('excel_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Format Excel: name, description, price, availability, image, jenis_sewa 
                            (1 untuk tersedia, 0 untuk tidak, kolom image dan jenis_sewa opsional)
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
