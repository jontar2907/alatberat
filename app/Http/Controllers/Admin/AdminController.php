<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalRequest; // <- changed from Pengajuan
use App\Models\HeavyEquipment; // added for equipments method
use App\Models\User; // added for user management
use App\Models\WorkOrder; // added for work order management
use App\Models\Transportation; // added for transportation management
use Illuminate\Http\Request;
use Carbon\Carbon; // <- import Carbon
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Mail\RentalApproved;
use App\Mail\PaymentInvoice;

class AdminController extends Controller
{
    public function index()
    {
        $rentalRequests = [
            'Disetujui' => RentalRequest::where('status', 'approved')->count(),
            'Menunggu Pembayaran' => RentalRequest::where('status', 'payment_pending')->count(),
            'Terverifikasi' => RentalRequest::where('status', 'payment_verified')->count(),
            'Ditolak' => RentalRequest::where('status', 'rejected')->count(),
            'Menunggu' => RentalRequest::where('status', 'pending')->count(),
        ];

        $totalPengajuan = array_sum($rentalRequests);
        $pengajuanDisetujui = $rentalRequests['Disetujui'];
        $pengajuanDitolak = $rentalRequests['Ditolak'];
        $pengajuanMenunggu = $rentalRequests['Menunggu'];
        $pengajuanTerbaru = RentalRequest::with('heavyEquipment')->latest()->take(5)->get();

        // Tambahkan untuk chart tren bulanan
        $pengajuanMonthly = RentalRequest::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, status, COUNT(*) as count")
            ->groupBy('month', 'status')
            ->get()
            ->groupBy('month')
            ->map(function($item){
                $mapped = [];
                foreach($item as $statusItem) {
                    $status = $statusItem->status;
                    $count = $statusItem->count;
                    if ($status == 'approved') {
                        $mapped['Disetujui'] = $count;
                    } elseif ($status == 'payment_pending') {
                        $mapped['Menunggu Pembayaran'] = $count;
                    } elseif ($status == 'rejected') {
                        $mapped['Ditolak'] = $count;
                    } elseif ($status == 'pending') {
                        $mapped['Menunggu'] = $count;
                    }
                }
                return $mapped;
            });

        return view('admin.dashboard', compact(
            'rentalRequests',
            'totalPengajuan',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'pengajuanMenunggu',
            'pengajuanTerbaru',
            'pengajuanMonthly' // <- ditambahkan di sini
        ));
    }

    public function jenisSewaIndex()
    {
        $rentalRequests = \App\Models\RentalRequest::with('heavyEquipment')->get();
        return view('admin.jenis-sewa', compact('rentalRequests'));
    }

    public function createJenisSewa()
    {
        $equipments = HeavyEquipment::all();
        return view('admin.jenis-sewa-create', compact('equipments'));
    }

    public function storeJenisSewa(Request $request)
    {
        $validated = $request->validate([
            'jenis_sewa' => 'required|string|in:perhari,perjam',
            'heavy_equipment_id' => 'required|exists:heavy_equipments,id',
        ]);

        $equipment = \App\Models\HeavyEquipment::find($validated['heavy_equipment_id']);
        if (!$equipment) {
            return redirect()->back()->withErrors(['error' => 'Selected heavy equipment not found.']);
        }

        $rentalRequest = new \App\Models\RentalRequest();
        $rentalRequest->jenis_sewa = $validated['jenis_sewa'];
        $rentalRequest->heavy_equipment_id = $equipment->id;
        // Set other required fields with default or dummy values
        $rentalRequest->full_name = 'Dummy Name'; // dummy value
        $rentalRequest->nik = '1234567890123456'; // dummy NIK
        $rentalRequest->address = 'Dummy Address'; // dummy value
        $rentalRequest->phone_number = '081234567890'; // dummy value
        $rentalRequest->email = 'dummy@example.com'; // dummy value
        $rentalRequest->work_location = 'Dummy Location'; // dummy value
        $rentalRequest->work_purpose = 'Dummy Purpose'; // dummy value
        $rentalRequest->start_date = now(); // current date
        $rentalRequest->end_date = now()->addDay(); // next day
        $rentalRequest->transportasi = 'default'; // dummy value
        $rentalRequest->transportation_cost = 0;
        $rentalRequest->administration_fee = 0;

        // Calculate jumlah_pemakaian as difference in days between end_date and start_date (inclusive)
        $rentalRequest->jumlah_pemakaian = $rentalRequest->start_date->diffInDays($rentalRequest->end_date) + 1;

        $rentalRequest->status = 'pending';
        // Calculate total_cost based on equipment price
        if ($validated['jenis_sewa'] === 'perhari') {
            $rentalRequest->total_cost = $equipment->price_per_day * $rentalRequest->jumlah_pemakaian + $rentalRequest->administration_fee + $rentalRequest->transportation_cost;
        } else {
            $pricePerHour = $equipment->price_per_day / 24;
            $rentalRequest->total_cost = $pricePerHour * $rentalRequest->jumlah_pemakaian + $rentalRequest->administration_fee + $rentalRequest->transportation_cost;
        }
        $rentalRequest->save();

        return redirect()->route('admin.jenis-sewa.index')->with('success', 'Jenis sewa alat berat berhasil ditambahkan.');
    }

    // Kepala Dinas: List rental requests with verified payments
    public function assignmentsIndex()
    {
        $rentalRequests = RentalRequest::where('status', 'payment_verified')
            ->with('heavyEquipment')
            ->get();

        return view('admin.kepala-dinas-assignments', compact('rentalRequests'));
    }

    // Kepala Dinas: Show form to assign operator
    public function createAssignment($id)
    {
        $rentalRequest = RentalRequest::findOrFail($id);
        $operators = \App\Models\Operator::all();

        return view('admin.work-order-form', compact('rentalRequest', 'operators'));
    }

    // Kepala Dinas: Store assignment (create WorkOrder)
    public function storeAssignment(Request $request, $id)
    {
        $rentalRequest = RentalRequest::findOrFail($id);

        $validated = $request->validate([
            'operator_name' => 'required|string|max:255',
            'assignment_letter' => 'required|string',
        ]);

        \App\Models\WorkOrder::create([
            'rental_request_id' => $rentalRequest->id,
            'operator_name' => $validated['operator_name'],
            'assignment_letter' => $validated['assignment_letter'],
            'status' => 'pending',
        ]);

        return redirect()->route('kepala-dinas.assignments.index')->with('success', 'Surat perintah tugas operator berhasil dibuat.');
    }

    public function operators()
    {
        $operators = \App\Models\Operator::all();
        return view('admin.operators', compact('operators'));
    }

    public function createWorkOrder($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        $rentalRequest = RentalRequest::findOrFail($id);
        return view('admin.work-order-form', compact('rentalRequest'));
    }

    public function storeWorkOrder(Request $request, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        $rentalRequest = RentalRequest::findOrFail($id);

        $validated = $request->validate([
            'operator_name' => 'required|string|max:255',
            'assignment_letter' => 'required|string',
        ]);

        WorkOrder::create([
            'rental_request_id' => $rentalRequest->id,
            'operator_name' => $validated['operator_name'],
            'assignment_letter' => $validated['assignment_letter'],
            'status' => 'pending',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Surat perintah tugas operator berhasil dibuat.');
    }

    public function equipments()
    {
        $equipments = HeavyEquipment::with(['rentalRequests' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])->get();

        return view('admin.equipments', compact('equipments'));
    }

    public function exportEquipments()
    {
        $equipments = HeavyEquipment::with(['rentalRequests' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])->get();

        $filename = 'equipments_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $appUrl = config('app.url');

        return response()->stream(function () use ($equipments, $appUrl) {
            $handle = fopen('php://output', 'w');

            // Write CSV headers (include image column and jenis_sewa)
            fputcsv($handle, ['name', 'description', 'price_per_day', 'availability', 'image_url', 'jenis_sewa']);

            // Write equipment data
            foreach ($equipments as $equipment) {
                $imageUrl = '';
                if ($equipment->image) {
                    $imagePath = $equipment->image;
                    if (!str_starts_with($imagePath, 'equipments/')) {
                        $imagePath = 'equipments/' . basename($imagePath);
                    }
                    $imageUrl = $appUrl . '/storage/' . $imagePath;
                }

                fputcsv($handle, [
                    $equipment->name,
                    $equipment->description,
                    $equipment->price_per_day,
                    $equipment->availability ? 1 : 0,
                    $imageUrl,
                    $equipment->rentalRequests->first()->jenis_sewa ?? 'N/A'
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function verifyRequest(Request $request, $id)
    {
        $rentalRequest = RentalRequest::findOrFail($id);

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($validated['action'] === 'approve') {
            $rentalRequest->update(['status' => 'payment_pending']);

            // Send approval email
            Mail::to($rentalRequest->email)->send(new RentalApproved($rentalRequest));

            // Send payment invoice email
            Mail::to($rentalRequest->email)->send(new PaymentInvoice($rentalRequest));

            // Load the heavy equipment relationship for the view
            $rentalRequest->load('heavyEquipment');

            // Return the invoice view instead of redirecting
            return view('admin.invoice', compact('rentalRequest'));
        } elseif ($validated['action'] === 'reject') {
            $rentalRequest->update(['status' => 'rejected']);

            return redirect()->route('admin.dashboard')->with('success', 'Permohonan sewa telah ditolak.');
        }
    }

    public function sendInvoicesToGmail()
    {
        $gmailRequests = RentalRequest::where('status', 'payment_pending')
            ->where('email', 'like', '%@gmail.com')
            ->with('heavyEquipment')
            ->get();

        $sentCount = 0;
        foreach ($gmailRequests as $request) {
            try {
                Mail::to($request->email)->send(new PaymentInvoice($request));
                $sentCount++;
            } catch (\Exception $e) {
                // Log error or handle failure
                Log::error('Failed to send invoice to ' . $request->email . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.dashboard')->with('success', "Tagihan telah dikirim ke {$sentCount} email Gmail.");
    }

    // User Management Methods
    public function users()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        return view('admin.user-form');
    }

    public function storeUser(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,super_admin,kepala_dinas',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat.');
    }

    public function editUser($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        $user = User::findOrFail($id);
        return view('admin.user-form', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin,super_admin,kepala_dinas',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized. Only Super Admin can access this page.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    // Payment management
    public function payments(Request $request)
    {
        $query = \App\Models\Payment::with('rentalRequest.heavyEquipment')->latest();

        if ($request->has('status') && in_array($request->status, ['pending', 'verified', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $payments = $query->get();

        return view('admin.payments', compact('payments'));
    }

    public function exportPayments()
    {
        $payments = \App\Models\Payment::with('rentalRequest.heavyEquipment')->latest()->get();

        $filename = 'payments_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->stream(function () use ($payments) {
            $handle = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($handle, ['ID', 'Rental Request', 'Amount', 'Status', 'Payment Proof', 'Created At']);

            // Write payment data
            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->id,
                    $payment->rentalRequest->heavyEquipment->name ?? 'N/A' . ' - ' . $payment->rentalRequest->full_name,
                    $payment->amount,
                    $payment->status,
                    $payment->payment_proof ?? '',
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // Rental Request Edit Methods
    public function editRentalRequest($id)
    {
        $rentalRequest = RentalRequest::findOrFail($id);
        $transportations = Transportation::all();
        return view('admin.rental-request-edit', compact('rentalRequest', 'transportations'));
    }

    public function updateRentalRequest(Request $request, $id)
    {
        $rentalRequest = RentalRequest::findOrFail($id);

        $transportationNames = Transportation::pluck('name')->toArray();
        $validated = $request->validate([
            'start_date' => 'required|date',
            'jumlah_hari' => 'required|integer|min:1',
            'jenis_sewa' => 'required|in:perhari,perjam',
            'transportasi' => 'required|string|in:' . implode(',', $transportationNames),
            'transportation_cost' => 'required|numeric|min:0',
            'administration_fee' => 'required|numeric|min:0',
        ]);

        $equipment = $rentalRequest->heavyEquipment;

        // Calculate end_date from start_date + jumlah_hari - 1
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays($validated['jumlah_hari'] - 1);

        // Calculate total cost based on jenis_sewa
        if ($validated['jenis_sewa'] === 'perhari') {
            $baseCost = $equipment->price * $validated['jumlah_hari'];
        } else { // perjam
            $pricePerHour = $equipment->price / 24;
            $baseCost = $pricePerHour * $validated['jumlah_hari'];
        }

        $totalCost = $baseCost + $validated['administration_fee'] + $validated['transportation_cost'];

        $rentalRequest->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'transportasi' => $validated['transportasi'],
            'jenis_sewa' => $validated['jenis_sewa'],
            'transportation_cost' => $validated['transportation_cost'],
            'administration_fee' => $validated['administration_fee'],
            'total_cost' => $totalCost,
            'jumlah_hari' => $validated['jumlah_hari'],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permintaan sewa berhasil diupdate.',
                'rentalRequest' => $rentalRequest->load('heavyEquipment')
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Permintaan sewa berhasil diupdate.');
    }
}
