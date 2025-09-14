@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Permintaan Sewa</h2>

    <div id="alert-container"></div>

    <form id="edit-form" action="{{ route('admin.rental-requests.update', $rentalRequest->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $rentalRequest->start_date->format('Y-m-d')) }}" required>
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="jumlah_hari" class="form-label">Jumlah Hari</label>
            <input type="number" min="1" name="jumlah_hari" id="jumlah_hari" class="form-control @error('jumlah_hari') is-invalid @enderror" value="{{ old('jumlah_hari', $rentalRequest->jumlah_hari) }}" required>
            @error('jumlah_hari')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Tanggal Selesai field -->
        <div class="mb-3">
            <label for="end_date" class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $rentalRequest->end_date ? $rentalRequest->end_date->format('Y-m-d') : '') }}" readonly>
        </div>

        <div class="mb-3">
            <label for="jenis_sewa" class="form-label">Jenis Sewa</label>
            <select name="jenis_sewa" id="jenis_sewa" class="form-select @error('jenis_sewa') is-invalid @enderror" required>
                <option value="perhari" {{ old('jenis_sewa', $rentalRequest->jenis_sewa) == 'perhari' ? 'selected' : '' }}>Per Hari</option>
                <option value="perjam" {{ old('jenis_sewa', $rentalRequest->jenis_sewa) == 'perjam' ? 'selected' : '' }}>Per Jam</option>
            </select>
            @error('jenis_sewa')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="transportasi" class="form-label">Transportasi</label>
            <select name="transportasi" id="transportasi" class="form-select @error('transportasi') is-invalid @enderror" required>
                <option value="">Pilih opsi transportasi</option>
                @foreach($transportations as $transportation)
                    <option value="{{ $transportation->name }}" {{ old('transportasi', $rentalRequest->transportasi) == $transportation->name ? 'selected' : '' }}>{{ $transportation->name }}</option>
                @endforeach
            </select>
            @error('transportasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="transportation_cost" class="form-label">Jumlah Angka</label>
            <input type="number" step="0.01" min="0" name="transportation_cost" id="transportation_cost" class="form-control @error('transportation_cost') is-invalid @enderror" value="{{ old('transportation_cost', $rentalRequest->transportation_cost ?? 0) }}" required>
            @error('transportation_cost')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="administration_fee" class="form-label">Biaya Administrasi (Rp)</label>
            <input type="number" step="0.01" min="0" name="administration_fee" id="administration_fee" class="form-control @error('administration_fee') is-invalid @enderror" value="{{ old('administration_fee', $rentalRequest->administration_fee ?? 0) }}" required>
            @error('administration_fee')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" id="submit-btn">Update Permintaan</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('edit-form');
    const submitBtn = document.getElementById('submit-btn');
    const alertContainer = document.getElementById('alert-container');
    const transportasiSelect = document.getElementById('transportasi');
    const transportationCostInput = document.getElementById('transportation_cost');
    const startDateInput = document.getElementById('start_date');
    const jumlahHariInput = document.getElementById('jumlah_hari');
    const endDateInput = document.getElementById('end_date');

    function updateTransportationCostField() {
        if (transportasiSelect.value === 'jemput sendiri') {
            transportationCostInput.value = 0;
            transportationCostInput.readOnly = true;
        } else if (transportasiSelect.value === 'diantar oleh dinas') {
            transportationCostInput.readOnly = false;
            // Optionally keep existing value or set default
            if (transportationCostInput.value == 0) {
                transportationCostInput.value = '{{ $rentalRequest->transportation_cost ?? 0 }}';
            }
        } else {
            transportationCostInput.readOnly = false;
        }
    }

    function calculateEndDate() {
        const startDateValue = startDateInput.value;
        const jumlahHariValue = parseInt(jumlahHariInput.value);

        if (startDateValue && jumlahHariValue && jumlahHariValue > 0) {
            const startDate = new Date(startDateValue);
            // Calculate end date by adding jumlahHariValue - 1 days
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + jumlahHariValue - 1);

            // Format date as yyyy-mm-dd
            const yyyy = endDate.getFullYear();
            const mm = String(endDate.getMonth() + 1).padStart(2, '0');
            const dd = String(endDate.getDate()).padStart(2, '0');
            endDateInput.value = `${yyyy}-${mm}-${dd}`;
        } else {
            endDateInput.value = '';
        }
    }

    transportasiSelect.addEventListener('change', updateTransportationCostField);
    startDateInput.addEventListener('change', calculateEndDate);
    jumlahHariInput.addEventListener('input', calculateEndDate);

    // Initialize on page load
    updateTransportationCostField();
    calculateEndDate();

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertContainer.innerHTML = '<div class="alert alert-success">Permintaan sewa berhasil diupdate.</div>';
                // Trigger custom event for other parts of the app to listen
                window.dispatchEvent(new CustomEvent('rentalRequestUpdated', { detail: data.rentalRequest }));
            } else {
                alertContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat update.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertContainer.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat update.</div>';
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Permintaan';
        });
    });
});
</script>
@endsection
