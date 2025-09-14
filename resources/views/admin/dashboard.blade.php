@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard Admin</h1>

    {{-- Statistik Pengajuan --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Disetujui</h5>
                    <p class="card-text">{{ $pengajuanDisetujui }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Menunggu Pembayaran</h5>
                    <p class="card-text">{{ $rentalRequests['Menunggu Pembayaran'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Ditolak</h5>
                    <p class="card-text">{{ $pengajuanDitolak }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Menunggu</h5>
                    <p class="card-text">{{ $pengajuanMenunggu }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.payments') }}" class="btn btn-primary w-100">Lihat Pembayaran</a>
        </div>
        <div class="col-md-9">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Pengajuan</h5>
                    <p class="card-text">{{ $totalPengajuan }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <canvas id="pengajuanChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="pengajuanLineChart"></canvas>
        </div>
    </div>

    {{-- Tabel Pengajuan Terbaru --}}
    <div class="row">
        <div class="col-md-12">
            <h4>Pengajuan Terbaru</h4>
            <table id="pengajuanTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Alat Berat - Nama Pemesan</th>
            <!-- <th>Transportasi</th> -->
            <!-- <th>Biaya Transportasi</th> -->
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengajuan-table-body">
                    @foreach($pengajuanTerbaru as $index => $pengajuan)
                        <tr data-id="{{ $pengajuan->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pengajuan->heavyEquipment->name ?? 'N/A' }} - {{ $pengajuan->full_name }}</td>
                            <!-- <td>{{ $pengajuan->transportasi }}</td> -->
                            <!-- <td>Rp{{ number_format($pengajuan->transportation_cost, 0, ',', '.') }}</td> -->
                            <td>Rp{{ number_format($pengajuan->total_cost, 0, ',', '.') }}</td>
                            <td>
                                @if($pengajuan->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($pengajuan->status == 'payment_received')
                                    <span class="badge bg-success">Sudah di bayar</span>
                                @elseif($pengajuan->status == 'payment_verified')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($pengajuan->status == 'payment_pending')
                                    <span class="badge bg-info text-dark">Menunggu Pembayaran</span>
                                @elseif($pengajuan->status == 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                            </td>
                            <td>{{ $pengajuan->created_at->format('d M Y') }}</td>
                            <td>
                    @if($pengajuan->status == 'pending')
                        <form method="POST" action="{{ route('admin.verify.request', $pengajuan->id) }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                        </form>
                        <form method="POST" action="{{ route('admin.verify.request', $pengajuan->id) }}" style="display: inline;">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.send.invoices.gmail') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Kirim Tagihan ke Gmail</button>
                        </form>
                        @if($pengajuan->status == 'approved' && auth()->user()->role === 'super_admin')
                            <a href="{{ route('admin.create.work.order', $pengajuan->id) }}" class="btn btn-info btn-sm">Buat Surat Tugas</a>
                        @endif
                    @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if(auth()->user()->role === 'super_admin')
    {{-- Surat Perintah Tugas Operator --}}
    <div class="row">
        <div class="col-md-12">
            <h4>Surat Perintah Tugas Operator</h4>
            <table id="workOrderTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Operator</th>
                        <th>Surat Perintah Tugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\WorkOrder::with('rentalRequest')->latest()->get() as $index => $workOrder)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $workOrder->operator_name }}</td>
                            <td>{{ Str::limit($workOrder->assignment_letter, 50) }}</td>
                            <td>
                                @if($workOrder->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($workOrder->status == 'in_progress')
                                    <span class="badge bg-info">Dalam Pengerjaan</span>
                                @elseif($workOrder->status == 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.create.work.order', $workOrder->rentalRequest->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
    // Doughnut Chart
    let pengajuans = <?php echo json_encode($rentalRequests); ?>;
    const ctx = document.getElementById('pengajuanChart').getContext('2d');
    const pengajuanChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(pengajuans),
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: Object.values(pengajuans),
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(255, 193, 7, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Line Chart: Tren Pengajuan Bulanan
    let monthlyData = <?php echo json_encode($pengajuanMonthly); ?>; // format: { "2025-01": {Disetujui: 5, Ditolak: 2, Menunggu:3}, ...}
    const lineLabels = Object.keys(monthlyData);
    const disetujuiData = lineLabels.map(m => monthlyData[m].Disetujui ?? 0);
    const ditolakData = lineLabels.map(m => monthlyData[m].Ditolak ?? 0);
    const menungguData = lineLabels.map(m => monthlyData[m].Menunggu ?? 0);

    const ctxLine = document.getElementById('pengajuanLineChart').getContext('2d');
    const pengajuanLineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [
                { label: 'Disetujui', data: disetujuiData, borderColor: 'rgba(40,167,69,1)', backgroundColor: 'rgba(40,167,69,0.2)', tension: 0.3 },
                { label: 'Ditolak', data: ditolakData, borderColor: 'rgba(220,53,69,1)', backgroundColor: 'rgba(220,53,69,0.2)', tension: 0.3 },
                { label: 'Menunggu', data: menungguData, borderColor: 'rgba(255,193,7,1)', backgroundColor: 'rgba(255,193,7,0.2)', tension: 0.3 },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // DataTables
    $(document).ready(function() {
        $('#pengajuanTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5,10,25,50],
            "order": [[3, "desc"]]
        });

        $('#workOrderTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5,10,25,50],
            "order": [[0, "desc"]]
        });
    });

    // Listen for rental request update event
    window.addEventListener('rentalRequestUpdated', function(e) {
        const updatedRequest = e.detail;
        const row = document.querySelector(`tr[data-id="${updatedRequest.id}"]`);

        if (row) {
            // Update the total cost column (3rd column, index 2)
            row.cells[2].textContent = 'Rp' + parseFloat(updatedRequest.total_cost).toLocaleString('id-ID');

            // Show success message
            const alertContainer = document.createElement('div');
            alertContainer.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertContainer.innerHTML = `
                <strong>Berhasil!</strong> Biaya administrasi telah diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertContainer);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertContainer.parentNode) {
                    alertContainer.remove();
                }
            }, 5000);
        }
    });
</script>
@endsection
