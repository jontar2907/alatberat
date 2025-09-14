@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Alat Berat: {{ $equipment->name }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.equipments.update', $equipment->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Alat Berat</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $equipment->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ $equipment->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ $equipment->price }}" min="0" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_sewa" class="form-label">Jenis Sewa</label>
                            <select class="form-select" id="jenis_sewa" name="jenis_sewa" required>
                                <option value="Perhari" {{ $equipment->jenis_sewa == 'Perhari' ? 'selected' : '' }}>Perhari</option>
                                <option value="Perjam" {{ $equipment->jenis_sewa == 'Perjam' ? 'selected' : '' }}>Perjam</option>
                                <option value="Pertrip" {{ $equipment->jenis_sewa == 'Pertrip' ? 'selected' : '' }}>Pertrip</option>
                                <option value="PerTitik" {{ $equipment->jenis_sewa == 'PerTitik' ? 'selected' : '' }}>PerTitik</option>
                                <option value="PerBuah/Test" {{ $equipment->jenis_sewa == 'PerBuah/Test' ? 'selected' : '' }}>PerBuah/Test</option>
                                <option value="PerSampel" {{ $equipment->jenis_sewa == 'PerSampel' ? 'selected' : '' }}>PerSampel</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            @if($equipment->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->name }}" width="150" class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="availability" class="form-label">Ketersediaan</label>
                            <select class="form-select" id="availability" name="availability" required>
                                <option value="1" {{ $equipment->availability ? 'selected' : '' }}>Tersedia</option>
                                <option value="0" {{ !$equipment->availability ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('admin.equipments') }}" class="btn btn-secondary mt-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection