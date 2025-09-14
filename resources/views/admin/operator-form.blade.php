@extends('layouts.admin')

@section('content')
@php
    $isAssignmentPage = request()->routeIs('kepala-dinas.assignments.index');
@endphp
<div class="container">
    <h1>{{ isset($operator) ? 'Edit Operator' : 'Tambah Operator Baru' }}</h1>

    <form action="{{ isset($operator) ? route('admin.operators.update', $operator->id) : route('admin.operators.store') }}" method="POST">
        @csrf
        @if(isset($operator))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $operator->name ?? '') }}" required {{ $isAssignmentPage ? '' : 'readonly' }}>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telepon</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $operator->phone ?? '') }}" {{ $isAssignmentPage ? '' : 'readonly' }}>
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $operator->email ?? '') }}" {{ $isAssignmentPage ? '' : 'readonly' }}>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" {{ $isAssignmentPage ? '' : 'readonly' }}>{{ old('address', $operator->address ?? '') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if($isAssignmentPage)
            <button type="submit" class="btn btn-primary">{{ isset($operator) ? 'Update' : 'Simpan' }}</button>
        @else
            <button type="submit" class="btn btn-primary" disabled>{{ isset($operator) ? 'Update' : 'Simpan' }}</button>
        @endif
        <a href="{{ route('admin.operators') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
