<?php

namespace App\Http\Controllers;

use App\Models\RentalRequest;
use App\Models\HeavyEquipment;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;

class RentalRequestController extends Controller
{
    public function store(Request $request)
    {
        // First, find the equipment to get its jenis_sewa
        $equipment = HeavyEquipment::findOrFail($request->input('heavy_equipment_id'));

        $validated = $request->validate([
            'heavy_equipment_id' => 'required|exists:heavy_equipments,id',
            'full_name'          => 'required|string|max:255',
            'nik'                => 'required|string|max:16',
            'address'            => 'required|string',
            'phone_number'       => 'required|string|max:15',
            'email'              => 'required|email',
            'work_location'      => 'required|string',
            'work_purpose'       => 'required|string',
            // Validate jenis_sewa to match equipment's jenis_sewa (case-insensitive)
            'jenis_sewa'         => ['required', 'string', function ($attribute, $value, $fail) use ($equipment) {
                if (strtolower($value) !== strtolower($equipment->jenis_sewa)) {
                    $fail('The selected jenis sewa is invalid.');
                }
            }],
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
            'transportasi'       => 'required|exists:transportations,id',
            'transportation_cost'=> 'required|numeric|min:0',
        ]);

        // Calculate jumlah_hari from date difference
        $startDate = new \DateTime($validated['start_date']);
        $endDate = new \DateTime($validated['end_date']);
        $diff = $startDate->diff($endDate);
        $validated['jumlah_hari'] = $diff->days + 1; // inclusive

        // Cek apakah sudah ada pemesanan alat yang sama di tanggal yang sama
        $existingRequest = RentalRequest::where('heavy_equipment_id', $validated['heavy_equipment_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function ($query2) use ($validated) {
                          $query2->where('start_date', '<=', $validated['start_date'])
                                 ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->where('status', '!=', 'rejected')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->withErrors(['heavy_equipment_id' => 'Anda sudah memesan alat ini pada tanggal yang sama.']);
        }

        $equipment = HeavyEquipment::findOrFail($validated['heavy_equipment_id']);
        $transportation = \App\Models\Transportation::find($validated['transportasi']);

        // Hitung total biaya
        $transportationCost = $transportation ? $validated['transportation_cost'] : 0;
        $totalCost = 0;
        if ($validated['jenis_sewa'] === 'perhari') {
            $totalCost = ($equipment->price * $validated['jumlah_hari']) + $transportationCost;
        } elseif ($validated['jenis_sewa'] === 'perjam') {
            $pricePerHour = $equipment->price / 24;
            $totalCost = ($pricePerHour * $validated['jumlah_hari']) + $transportationCost;
        } elseif ($validated['jenis_sewa'] === 'perminggu') {
            $pricePerWeek = $equipment->price * 7;
            $totalCost = ($pricePerWeek * $validated['jumlah_hari']) + $transportationCost;
        } elseif ($validated['jenis_sewa'] === 'perbulan') {
            $pricePerMonth = $equipment->price * 30;
            $totalCost = ($pricePerMonth * $validated['jumlah_hari']) + $transportationCost;
        } else {
            // fallback
            $totalCost = ($equipment->price * $validated['jumlah_hari']) + $transportationCost;
        }

        // Simpan permintaan sewa
        $rentalRequest = RentalRequest::create([
            'heavy_equipment_id' => $validated['heavy_equipment_id'],
            'full_name'          => $validated['full_name'],
            'nik'                => $validated['nik'],
            'address'            => $validated['address'],
            'phone_number'       => $validated['phone_number'],
            'email'              => $validated['email'],
            'work_location'      => $validated['work_location'],
            'jenis_sewa'         => $validated['jenis_sewa'],
            'jumlah_hari'        => $validated['jumlah_hari'],
            'work_purpose'       => $validated['work_purpose'],
            'start_date'         => $validated['start_date'],
            'end_date'           => $validated['end_date'],
            'transportasi'       => $transportation ? $transportation->name : null,
            'transportation_cost'=> $transportationCost,
            'status'             => 'payment_pending', // changed to payment_pending
            'total_cost'         => $totalCost,
        ]);

        return redirect()->route('payment.form', ['id' => $rentalRequest->id])->with('success', 'Pengajuan sewa berhasil dikirim. Silakan lakukan pembayaran.');
    }

    /**
     * API endpoint to get booked date ranges for a given equipment.
     *
     * @param int $equipmentId
     * @return JsonResponse
     */
    public function getBookedDates($equipmentId)
    {
        $bookings = RentalRequest::where('heavy_equipment_id', $equipmentId)
            ->where('status', '!=', 'rejected')
            ->get(['start_date', 'end_date']);

        $dateRanges = $bookings->map(function ($booking) {
            return [
                'start_date' => $booking->start_date->format('Y-m-d'),
                'end_date' => $booking->end_date->format('Y-m-d'),
            ];
        });

        return response()->json($dateRanges);
    }

    /**
     * Remove the specified rental request from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $rentalRequest = RentalRequest::findOrFail($id);
        $rentalRequest->delete();

            return redirect()->route('admin.jenis-sewa.index')->with('success', 'Jenis sewa berhasil dihapus.');
        }
    
    }
