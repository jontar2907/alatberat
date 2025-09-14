@extends('layouts.admin')

@section('content')
@php
    $isAssignmentPage = request()->routeIs('kepala-dinas.assignments.index');
@endphp
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen User</h1>
        @if(Auth::user()->role === 'super_admin')
            @if($isAssignmentPage)
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah User Baru</a>
            @else
                <button class="btn btn-primary" disabled>Tambah User Baru</button>
            @endif
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                            <td>
                                @if($isAssignmentPage)
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-warning" disabled>Edit</button>
                                    <button class="btn btn-sm btn-danger" disabled>Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada user ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
