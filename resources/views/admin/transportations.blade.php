@extends('layouts.admin')

@section('content')
@php
    $isAssignmentPage = request()->routeIs('admin.transportations.index');
@endphp
<div class="container">
    <h1>Manajemen Jenis Transportasi</h1>

    @if($isAssignmentPage)
        <a href="{{ route('admin.transportations.create') }}" class="btn btn-primary mb-3">Tambah Jenis Transportasi Baru</a>
    @else
        <button class="btn btn-primary mb-3" disabled>Tambah Jenis Transportasi Baru</button>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Biaya</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transportations as $transportation)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transportation->name }}</td>
                <td>{{ $transportation->description }}</td>
                <td>{{ number_format($transportation->cost, 2, ',', '.') }}</td>
                <td>{{ $transportation->created_at->format('d M Y H:i') }}</td>
                <td>
                    @if($isAssignmentPage)
                        <a href="{{ route('admin.transportations.edit', $transportation->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.transportations.destroy', $transportation->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus jenis transportasi ini?')">Hapus</button>
                        </form>
                    @else
                        <button class="btn btn-warning btn-sm" disabled>Edit</button>
                        <button class="btn btn-danger btn-sm" disabled>Hapus</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
