@extends('layouts.admin')

@section('content')
@php
    $isAssignmentPage = request()->routeIs('admin.transportations.create') || request()->routeIs('admin.transportations.edit');
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ isset($transportation) ? 'Edit Jenis Transportasi' : 'Tambah Jenis Transportasi Baru' }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($transportation) ? route('admin.transportations.update', $transportation->id) : route('admin.transportations.store') }}">
                        @csrf
                        @if(isset($transportation))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $transportation->name ?? '') }}" required {{ $isAssignmentPage ? '' : 'readonly' }}>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" {{ $isAssignmentPage ? '' : 'readonly' }}>{{ old('description', $transportation->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cost" class="form-label">Biaya</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" value="{{ old('cost', $transportation->cost ?? 0) }}" required {{ $isAssignmentPage ? '' : 'readonly' }}>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.transportations.index') }}" class="btn btn-secondary">Kembali</a>
                            @if($isAssignmentPage)
                                <button type="submit" class="btn btn-primary">{{ isset($transportation) ? 'Update' : 'Simpan' }}</button>
                            @else
                                <button type="submit" class="btn btn-primary" disabled>{{ isset($transportation) ? 'Update' : 'Simpan' }}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
