<?php

namespace App\Http\Controllers;

use App\Models\HeavyEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class HeavyEquipmentController extends Controller
{
    // ... existing methods ...

    // Import alat berat dari Excel dengan gambar embedded
    public function importEquipmentsExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();

        $images = [];
        foreach ($worksheet->getDrawingCollection() as $drawing) {
            if ($drawing instanceof Drawing) {
                $coordinates = $drawing->getCoordinates();
                $imageContents = null;
                $extension = 'png';

                $zipReader = null;
                if (method_exists($drawing, 'getPath')) {
                    $zipReader = fopen($drawing->getPath(), 'r');
                }

                if ($zipReader) {
                    $imageContents = stream_get_contents($zipReader);
                    fclose($zipReader);
                } else {
                    $imageContents = file_get_contents($drawing->getPath());
                }

                if (method_exists($drawing, 'getExtension')) {
                    $extension = $drawing->getExtension();
                }

                $filename = 'equipments/' . uniqid() . '.' . $extension;
                Storage::disk('public')->put($filename, $imageContents);
                $images[$coordinates] = $filename;
            }
        }

        $allowedJenisSewa = ['Perhari', 'Perjam', 'Pertrip', 'PerTitik', 'PerBuah/Test', 'PerSampel', 'Per20Km', 'Test'];
        $highestRow = $worksheet->getHighestRow();
        $imported = 0;
        $errors = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            try {
                $name = trim($worksheet->getCell('A' . $row)->getValue());
                $description = trim($worksheet->getCell('B' . $row)->getValue());
                $price = (float) trim($worksheet->getCell('C' . $row)->getValue());
                $availability = (bool) trim($worksheet->getCell('D' . $row)->getValue());
                $jenisSewa = trim($worksheet->getCell('F' . $row)->getValue());

                if (!in_array($jenisSewa, $allowedJenisSewa)) {
                    $errors[] = "Row $row: Jenis sewa '$jenisSewa' tidak valid.";
                    continue; // Skip this row
                }

                $imagePath = $images['E' . $row] ?? null;

                $equipmentData = [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'availability' => $availability,
                    'image' => $imagePath,
                    'jenis_sewa' => $jenisSewa ?: 'Perhari',
                ];

                $equipment = HeavyEquipment::create($equipmentData);

                // Create a related RentalRequest with jenis_sewa and default placeholders
                $rentalRequestData = [
                    'heavy_equipment_id' => $equipment->id,
                    'full_name' => 'Default Name',
                    'nik' => '0000000000000000',
                    'address' => 'Default Address',
                    'phone_number' => '0000000000',
                    'email' => 'default@example.com',
                    'work_location' => 'Default Location',
                    'work_purpose' => 'Default Purpose',
                    'jenis_sewa' => strtolower($jenisSewa) ?: 'perhari',
                    'jumlah_pemakaian' => 1,
                    'start_date' => now(),
                    'end_date' => now()->addDay(),
                    'transportasi' => null,
                    'transportation_cost' => 0,
                    'administration_fee' => 0,
                    'status' => 'pending',
                    'total_cost' => 0,
                ];

                \App\Models\RentalRequest::create($rentalRequestData);

                $imported++;
            } catch (\Exception $e) {
                $errors[] = 'Error importing row ' . $row . ': ' . $e->getMessage();
            }
        }

        $message = $imported . ' alat berat berhasil diimpor dari Excel.';
        if (!empty($errors)) {
            $message .= ' Kesalahan: ' . implode('; ', $errors);
        }

        return redirect()->route('admin.equipments')->with('success', $message);
    }

    // Show the form for creating a new heavy equipment
    public function create()
    {
        return view('heavy_equipments.create');
    }

    // Store a newly created heavy equipment in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'jenis_sewa' => 'required|string|in:Perhari,Perjam,Pertrip,PerTitik,PerBuah/Test,PerSampel,Per20Km,Test',
            'availability' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('equipments', 'public');
        }

        HeavyEquipment::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'jenis_sewa' => $validated['jenis_sewa'],
            'availability' => $validated['availability'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.equipments')->with('success', 'Heavy equipment created successfully.');
    }

    // Show the form for editing the specified heavy equipment
    public function edit($id)
    {
        $equipment = HeavyEquipment::findOrFail($id);
        return view('heavy_equipments.edit', compact('equipment'));
    }

    // Update the specified heavy equipment in storage
    public function update(Request $request, $id)
    {
        $equipment = HeavyEquipment::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'jenis_sewa' => 'required|string|in:Perhari,Perjam,Pertrip,PerTitik,PerBuah/Test,PerSampel,Per20Km,Test',
            'availability' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $equipment->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('equipments', 'public');
        }

        $equipment->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'jenis_sewa' => $validated['jenis_sewa'],
            'availability' => $validated['availability'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.equipments')->with('success', 'Heavy equipment updated successfully.');
    }

    // Remove the specified heavy equipment from storage
    public function destroy($id)
    {
        $equipment = HeavyEquipment::findOrFail($id);

        // Delete associated image if exists
        if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()->route('admin.equipments')->with('success', 'Heavy equipment deleted successfully.');
    }
}
