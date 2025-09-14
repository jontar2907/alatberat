<?php

namespace App\Http\Controllers;

use App\Models\HeavyEquipment;
use App\Models\RentalRequest;
use App\Models\Transportation;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    // Halaman utama menampilkan daftar alat berat
    public function index()
    {
        // Gunakan field availability yang benar
        $equipments = HeavyEquipment::where('availability', true)->get();

        return view('landing', compact('equipments'));
    }

    // Form pengajuan sewa
    public function requestRental($id)
    {
        $equipment = HeavyEquipment::findOrFail($id);
        $transportations = Transportation::all();

        $defaultStartDate = date('Y-m-d');
        $defaultJumlahHari = 1;

        return view('rental-form-improved', compact('equipment', 'transportations', 'defaultStartDate', 'defaultJumlahHari'));
    }
}
