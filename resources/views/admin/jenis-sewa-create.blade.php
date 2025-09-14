@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Tambah Jenis Sewa Alat Berat</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.jenis-sewa.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="heavy_equipment_id" class="form-label">Pilih Alat Berat</label>
            <select name="heavy_equipment_id" id="heavy_equipment_id" class="form-select" required>
                <option value="" disabled selected>-- Pilih Alat Berat --</option>
                @foreach($equipments as $equipment)
                    <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jenis_sewa" class="form-label">Jenis Sewa</label>
            <input type="text" name="jenis_sewa" id="jenis_sewa" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
        <a href="{{ route('admin.jenis-sewa.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
