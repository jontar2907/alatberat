@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Manajemen Pembayaran</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="statusFilter" class="form-label">Filter Status:</label>
            <select id="statusFilter" class="form-select">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-md-6 text-end align-self-end">
            <a href="{{ route('admin.payments.export') }}" class="btn btn-primary">Export CSV</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table id="paymentsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Permintaan Sewa</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Bukti Pembayaran</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                {{ $payment->rentalRequest->heavyEquipment->name ?? 'N/A' }} - {{ $payment->rentalRequest->full_name }}
                            </td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($payment->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($payment->status == 'verified')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($payment->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($payment->status == 'processing')
                                    <span class="badge bg-info text-dark">Sedang Diproses</span>
                                @else
                                    <span class="badge bg-secondary">Status Tidak Diketahui</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->payment_proof)
                                    <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran" width="100">
                                    </a>
                                @else
                                    Tidak ada
                                @endif
                            </td>
                            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-primary btn-sm mb-1">View</a>
                                @if($payment->status == 'pending')
                                    <!-- Verifikasi Button trigger modal -->
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $payment->id }}">
                                        Verifikasi
                                    </button>

                                    <!-- Verifikasi Modal -->
                                    <div class="modal fade" id="verifyModal{{ $payment->id }}" tabindex="-1" aria-labelledby="verifyModalLabel{{ $payment->id }}" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <form method="POST" action="{{ route('admin.verify.payment', $payment->id) }}">
                                            @csrf
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="verifyModalLabel{{ $payment->id }}">Verifikasi Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="keteranganVerify{{ $payment->id }}" class="form-label">Keterangan (opsional)</label>
                                                    <textarea class="form-control" id="keteranganVerify{{ $payment->id }}" name="keterangan" rows="3"></textarea>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Verifikasi</button>
                                              </div>
                                            </div>
                                        </form>
                                      </div>
                                    </div>

                                    <!-- Tolak Button trigger modal -->
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $payment->id }}">
                                        Tolak
                                    </button>

                                    <!-- Tolak Modal -->
                                    <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $payment->id }}" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <form method="POST" action="{{ route('admin.reject.payment', $payment->id) }}">
                                            @csrf
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="rejectModalLabel{{ $payment->id }}">Tolak Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="keteranganReject{{ $payment->id }}" class="form-label">Keterangan (opsional)</label>
                                                    <textarea class="form-control" id="keteranganReject{{ $payment->id }}" name="keterangan" rows="3"></textarea>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                              </div>
                                            </div>
                                        </form>
                                      </div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5,10,25,50],
            "order": [[5, "desc"]]
        });
    });
</script>
@endsection
