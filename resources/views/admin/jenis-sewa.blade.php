@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Update Jenis Sewa Alat Berat</h2>
    <p>Halaman ini akan digunakan untuk mengelola jenis sewa alat berat.</p>

    <a href="{{ route('admin.jenis-sewa.create') }}" class="btn btn-success mb-3">Tambah Jenis Sewa Alat Berat</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Alat Berat</th>
                <th>Jenis Sewa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentalRequests as $request)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $request->heavyEquipment->name ?? 'N/A' }}</td>
                <td>
                    <form action="{{ route('admin.rental-requests.update', $request->id) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        @method('PUT')
                        <input type="text" name="jenis_sewa" class="form-control form-control-sm me-2" value="{{ $request->jenis_sewa }}" required>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('admin.rental-requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis sewa ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
