@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Alat Berat Baru</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.equipments.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Alat Berat</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" step="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis_sewa" class="form-label">Jenis Sewa</label>
                            <select class="form-select" id="jenis_sewa" name="jenis_sewa" required>
                                <option value="Perhari">Perhari</option>
                                <option value="Perjam">Perjam</option>
                                <option value="Pertrip">Pertrip</option>
                                <option value="PerTitik">PerTitik</option>
                                <option value="PerBuah/Test">PerBuah/Test</option>
                                <option value="PerSampel">PerSampel</option>
                                <option value="Per20Km">Per20Km</option>
                                <option value="Test">Test</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="availability" class="form-label">Ketersediaan</label>
                            <select class="form-select" id="availability" name="availability" required>
                                <option value="1">Tersedia</option>
                                <option value="0">Tidak Tersedia</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('admin.equipments') }}" class="btn btn-secondary mt-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection