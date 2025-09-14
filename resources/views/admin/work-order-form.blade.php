@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Buat Surat Perintah Tugas Operator</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.store.work.order', $rentalRequest->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="operator_name" class="form-label">Nama Operator</label>
            <select class="form-select" id="operator_name" name="operator_name" required>
                <option value="" disabled selected>Pilih Operator</option>
                @foreach($operators as $operator)
                    <option value="{{ $operator->name }}" {{ old('operator_name') == $operator->name ? 'selected' : '' }}>
                        {{ $operator->name }}
                    </option>
                @endforeach
            </select>
            @error('operator_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="assignment_letter" class="form-label">Surat Perintah Tugas</label>
            <textarea class="form-control" id="assignment_letter" name="assignment_letter" rows="5" required>{{ old('assignment_letter') }}</textarea>
            @error('assignment_letter')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Buat Surat</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
