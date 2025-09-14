@extends('layouts.admin')

@section('content')
@php
    $isAssignmentPage = request()->routeIs('kepala-dinas.assignments.index');
@endphp
<div class="container">
    <h1>Daftar Operator Alat Berat</h1>
    @if($isAssignmentPage)
        <a href="{{ route('admin.operators.create') }}" class="btn btn-primary mb-3">Tambah Operator Baru</a>
    @else
        <button class="btn btn-primary mb-3" disabled>Tambah Operator Baru</button>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($operators->isEmpty())
        <p>Tidak ada data operator.</p>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($operators as $operator)
            <tr>
                <td>{{ $operator->name }}</td>
                <td>{{ $operator->phone }}</td>
                <td>{{ $operator->email }}</td>
                <td>{{ $operator->address }}</td>
                <td>
                    @if($isAssignmentPage)
                        <a href="{{ route('admin.operators.edit', $operator->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.operators.delete', $operator->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus operator ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-warning" disabled>Edit</button>
                        <button class="btn btn-sm btn-danger" disabled>Hapus</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
