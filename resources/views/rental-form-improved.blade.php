@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center fw-bold text-primary">Form Pengajuan Sewa Alat Berat</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Pesan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <strong>Terjadi kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4 custom-card">
                <div class="card-header bg-primary text-white text-center rounded-top-4 custom-card-header">
                    <h5 class="mb-0">Lengkapi Data Anda</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('rental.store') }}" method="POST" class="custom-form" data-price="{{ $equipment->price ?? 0 }}">
                        @csrf
                        <input type="hidden" name="heavy_equipment_id" value="{{ $equipment->id }}">

                        <div class="row g-3">
                            {{-- Nama --}}
                            <div class="col-md-6">
                                <label for="full_name" class="form-label"><i class="bi bi-person-fill me-1"></i> Nama Lengkap</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                       name="full_name" id="full_name" value="{{ old('full_name') }}" required>
                                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- NIK --}}
                            <div class="col-md-6">
                                <label for="nik" class="form-label"><i class="bi bi-credit-card-2-front-fill me-1"></i> NIK</label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                       name="nik" id="nik" value="{{ old('nik') }}" maxlength="16" required>
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="col-12">
                                <label for="address" class="form-label"><i class="bi bi-geo-alt-fill me-1"></i> Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          name="address" id="address" rows="2" required>{{ old('address') }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label"><i class="bi bi-telephone-fill me-1"></i> Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                       name="phone_number" id="phone_number" value="{{ old('phone_number') }}" maxlength="15" required>
                                @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label"><i class="bi bi-envelope-fill me-1"></i> Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" id="email" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Lokasi Pekerjaan --}}
                            <div class="col-md-6">
                                <label for="work_location" class="form-label"><i class="bi bi-building me-1"></i> Lokasi Pekerjaan</label>
                                <input type="text" class="form-control @error('work_location') is-invalid @enderror"
                                       name="work_location" id="work_location" value="{{ old('work_location') }}" required>
                                @error('work_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Jenis Sewa --}}
                            <div class="col-md-6">
                                <label for="jenis_sewa" class="form-label"><i class="bi bi-list-ul me-1"></i> Jenis Sewa</label>
                                <input type="text" readonly class="form-control" id="jenis_sewa_display" 
                                       value="{{ ucfirst($equipment->jenis_sewa) }}">
                                <input type="hidden" name="jenis_sewa" id="jenis_sewa" value="{{ $equipment->jenis_sewa }}">
                            </div>

                            {{-- Jumlah --}}
                            <div class="col-md-6">
                                <label for="jumlah_hari" class="form-label" id="jumlah_hari_label"><i class="bi bi-calculator-fill me-1"></i> Jumlah</label>
                                <input type="number" class="form-control @error('jumlah_hari') is-invalid @enderror"
                                       name="jumlah_hari" id="jumlah_hari" value="{{ old('jumlah_hari', $defaultJumlahHari ?? 1) }}" min="1" required>
                                @error('jumlah_hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Tujuan --}}
                            <div class="col-12">
                                <label for="work_purpose" class="form-label"><i class="bi bi-briefcase-fill me-1"></i> Tujuan Pekerjaan</label>
                                <textarea class="form-control @error('work_purpose') is-invalid @enderror"
                                          name="work_purpose" id="work_purpose" rows="3" required>{{ old('work_purpose') }}</textarea>
                                @error('work_purpose') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Tanggal Mulai & Selesai --}}
                            <div class="col-md-6">
                                <label for="start_date" class="form-label"><i class="bi bi-calendar-event-fill me-1"></i> Tanggal Mulai</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                       name="start_date" id="start_date" value="{{ old('start_date', $defaultStartDate ?? '') }}" required>
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label"><i class="bi bi-calendar-check-fill me-1"></i> Tanggal Selesai</label>
                                <input type="date" readonly class="form-control @error('end_date') is-invalid @enderror"
                                       name="end_date" id="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Transportasi --}}
                            <div class="col-md-6">
                                <label for="transportasi" class="form-label"><i class="bi bi-truck-front-fill me-1"></i> Transportasi</label>
                                <select class="form-select @error('transportasi') is-invalid @enderror"
                                        name="transportasi" id="transportasi" required>
                                    @if(old('transportasi'))
                                        <option value="" disabled>Pilih opsi transportasi</option>
                                    @else
                                        <option value="" disabled selected>Pilih opsi transportasi</option>
                                    @endif
                                    @foreach ($transportations as $index => $transportation)
                                        <option value="{{ $transportation->id }}" 
                                                data-cost="{{ $transportation->cost }}"
                                                @if(old('transportasi'))
                                                    {{ old('transportasi') == $transportation->id ? 'selected' : '' }}
                                                @else
                                                    {{ $index === 0 ? 'selected' : '' }}
                                                @endif>
                                            {{ $transportation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('transportasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Biaya Transportasi --}}
                            <div class="col-md-6">
                                <label for="transportation_cost" class="form-label"><i class="bi bi-cash-stack me-1"></i> Biaya Transportasi</label>
                                <input type="number" readonly 
                                       class="form-control @error('transportation_cost') is-invalid @enderror"
                                       name="transportation_cost" id="transportation_cost" 
                                       value="{{ old('transportation_cost') }}" min="0">
                                @error('transportation_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Rincian Tagihan --}}
                        <div id="billing-section" class="mt-3">
                            <div class="card border-warning shadow-sm">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0 fw-bold">💰 Rincian Tagihan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6"><p class="mb-1"><strong>Harga:</strong></p></div>
                                        <div class="col-6 text-end"><p class="mb-1" id="price-per-day">Rp{{ number_format($equipment->price, 0, ',', '.') }}</p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6"><p class="mb-1"><strong>Jumlah :</strong></p></div>
                                        <div class="col-6 text-end"><p class="mb-1" id="days-count-display">-</p></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6"><p class="mb-1"><strong>Biaya Transportasi:</strong></p></div>
                                        <div class="col-6 text-end"><p class="mb-1" id="transportation-cost-display">Rp0</p></div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6"><p class="mb-0 fw-bold text-primary"><strong>Total Tagihan:</strong></p></div>
                                        <div class="col-6 text-end"><p class="mb-0 fw-bold text-primary" id="total-billing">Rp0</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Persetujuan --}}
                        <div class="mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreement" name="agreement" value="1" required>
                                <label class="form-check-label fw-bold" for="agreement">
                                    Saya setuju dengan syarat dan ketentuan penyewaan alat berat
                                </label>
                                @error('agreement') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="mt-3">
                            <button type="submit" id="submit-btn" class="btn btn-success w-100 py-2 fw-bold" disabled>
                                🚜 Kirim Pengajuan Sewa
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Alat Berat --}}
            <div class="card shadow-sm border-0 rounded-4 mt-4">
                <div class="card-body text-center">
                    <h5 class="fw-bold">{{ $equipment->name }}</h5>
                    <p class="text-muted">{{ $equipment->description }}</p>
                    <p><strong>Harga Sewa:</strong> Rp{{ number_format($equipment->price, 0, ',', '.') }} / hari</p>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const agreementCheckbox = document.getElementById('agreement');
    const submitBtn = document.getElementById('submit-btn');
    const jenisSewaSelect = document.getElementById('jenis_sewa');
    const jumlahPemakaianInput = document.getElementById('jumlah_hari');
    const daysCountDisplay = document.getElementById('days-count-display');
    const transportationCostDisplay = document.getElementById('transportation-cost-display');
    const totalBilling = document.getElementById('total-billing');
    const transportasiSelect = document.getElementById('transportasi');
    const transportationCostInput = document.getElementById('transportation_cost');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const pricePerDay = parseInt(document.querySelector('form').dataset.price) || 0;

    // Update label berdasarkan jenis sewa
    function updateLabel() {
        const jenisSewa = jenisSewaSelect.value;
        const label = document.getElementById('jumlah_hari_label');
        let text = 'Jumlah Hari';
        if (jenisSewa === 'Perjam') {
            text = 'Jumlah Jam';
            jumlahPemakaianInput.disabled = false; // disable input for Perjam
        } else {
            jumlahPemakaianInput.disabled = false; // enable input for others
            if (jenisSewa === 'perminggu') {
                text = 'Jumlah Minggu';
            } else if (jenisSewa === 'perbulan') {
                text = 'Jumlah Bulan';
            } else if (jenisSewa === 'Pertrip') {
                text = 'Jumlah Trip';
            } else if (jenisSewa === 'PerTitik') {
                text = 'Jumlah Titik';
            } else if (jenisSewa === 'PerBuah') {
                text = 'Jumlah Buah';
            } else if (jenisSewa === 'Test') {
                text = 'Jumlah Test';
            } else if (jenisSewa === 'PerSampel') {
                text = 'Jumlah Sampel';
            }
        }
        label.innerHTML = '<i class="bi bi-calculator-fill me-1"></i> ' + text;
    }

    // Hitung tanggal selesai otomatis
    function updateEndDate() {
        const startDateValue = startDateInput.value;
        let jumlahHari = parseInt(jumlahPemakaianInput.value) || 1;
        const jenisSewa = jenisSewaSelect.value;

        if (startDateValue && jumlahHari > 0) {
            const startDate = new Date(startDateValue);
            const endDate = new Date(startDate);

            if (jenisSewa === 'Perjam' || jenisSewa === 'Pertrip' || jenisSewa === 'PerTitik' || jenisSewa === 'PerBuah' || jenisSewa === 'Test' || jenisSewa === 'PerSampel') {
                // Untuk Perjam dan jenis sewa non-waktu, tanggal selesai sama dengan tanggal mulai
                // Jadi tidak perlu menambah hari
                endDateInput.value = startDateValue;
                return;
            } else if (jenisSewa === 'perhari') {
                endDate.setDate(startDate.getDate() + jumlahHari - 1);
            } else if (jenisSewa === 'perminggu') {
                endDate.setDate(startDate.getDate() + (jumlahHari * 7) - 1);
            } else if (jenisSewa === 'perbulan') {
                endDate.setMonth(startDate.getMonth() + jumlahHari);
                endDate.setDate(endDate.getDate() - 1);
            } else {
                // fallback default perhari
                endDate.setDate(startDate.getDate() + jumlahHari - 1);
            }

            const year = endDate.getFullYear();
            const month = String(endDate.getMonth() + 1).padStart(2, '0');
            const day = String(endDate.getDate()).padStart(2, '0');
            endDateInput.value = `${year}-${month}-${day}`;
        } else {
            endDateInput.value = '';
        }
    }

    // Hitung biaya
    function calculateBilling() {
        const jenisSewa = jenisSewaSelect.value;
        let jumlahPemakaian = parseInt(jumlahPemakaianInput.value) || 1;
        const selectedOption = transportasiSelect.options[transportasiSelect.selectedIndex];
        const transportationCost = selectedOption ? parseInt(selectedOption.getAttribute('data-cost')) || 0 : 0;

        let equipmentTotal = 0;
        let satuan = '';

        if (jenisSewa === 'perhari') {
            equipmentTotal = pricePerDay * jumlahPemakaian;
            satuan = 'hari';
        } else if (jenisSewa === 'Perjam') {
            const pricePerHour = pricePerDay
            equipmentTotal = pricePerHour * jumlahPemakaian;
            satuan = 'jam';
        } else if (jenisSewa === 'perminggu') {
            const pricePerWeek = pricePerDay * 7;
            equipmentTotal = pricePerWeek * jumlahPemakaian;
            satuan = 'minggu';
        } else if (jenisSewa === 'perbulan') {
            const pricePerMonth = pricePerDay * 30;
            equipmentTotal = pricePerMonth * jumlahPemakaian;
            satuan = 'bulan';
        } else if (jenisSewa === 'Pertrip') {
            equipmentTotal = pricePerDay * jumlahPemakaian;
            satuan = 'trip';
        } else if (jenisSewa === 'PerTitik') {
            equipmentTotal = pricePerDay * jumlahPemakaian;
            satuan = 'titik';
        } else if (jenisSewa === 'PerBuah') {
            equipmentTotal = pricePerDay * jumlahPemakaian;
            satuan = 'buah';
        } else if (jenisSewa === 'Test') {
            equipmentTotal = pricePerDay * jumlahPemakaian;
            satuan = 'test';
        } else if (jenisSewa === 'PerSampel') {
            equipmentTotal = pricePerDay * jumlahPemakaian;
            satuan = 'sampel';
        } else {
            // fallback
            equipmentTotal = pricePerDay * jumlahPemakaian;
            satuan = 'hari';
        }

        // tampilkan jumlah + satuan di rincian tagihan
        daysCountDisplay.textContent = jumlahPemakaian + ' ' + satuan;

        transportationCostDisplay.textContent = 'Rp' + transportationCost.toLocaleString('id-ID');
        totalBilling.textContent = 'Rp' + (equipmentTotal + transportationCost).toLocaleString('id-ID');
        transportationCostInput.value = transportationCost;
    }

    // Event listeners
    jumlahPemakaianInput.addEventListener('input', function() {
        updateEndDate();
        calculateBilling();
    });

    jenisSewaSelect.addEventListener('change', function() {
        updateEndDate();
        calculateBilling();
        updateLabel();
    });

    startDateInput.addEventListener('change', function() {
        updateEndDate();
        calculateBilling();
    });

    transportasiSelect.addEventListener('change', function() {
        calculateBilling();
    });



    agreementCheckbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
    });

    // Inisialisasi tampilan awal
    updateEndDate();
    calculateBilling();
    updateLabel();
});
</script>
@endverbatim
@endsection
@endsection

