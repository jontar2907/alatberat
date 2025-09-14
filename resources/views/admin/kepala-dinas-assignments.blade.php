@extends('layouts.admin')

@section('title', 'Penugasan Operator - Kepala Dinas')

@section('content')
<div class="container">
    <h1 class="mb-4">Penugasan Operator untuk Permohonan Terverifikasi</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($rentalRequests->isEmpty())
        <p>Tidak ada permohonan dengan pembayaran terverifikasi.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pemohon</th>
                    <th>Alat Berat</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentalRequests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->full_name }}</td>
                    <td>{{ $request->heavyEquipment->name ?? '-' }}</td>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>
                        <a href="{{ route('kepala-dinas.assignments.create', $request->id) }}" class="btn btn-primary btn-sm">Tugaskan Operator</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
