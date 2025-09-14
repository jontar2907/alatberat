<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceived;

class PaymentController extends Controller
{
    // Menampilkan form pembayaran
    public function showPaymentForm($id)
    {
        $rentalRequest = RentalRequest::with('heavyEquipment')->findOrFail($id);
        
        // Pastikan status adalah payment_pending
        if ($rentalRequest->status !== 'payment_pending') {
            return redirect()->route('landing')->with('error', 'Status pembayaran tidak valid.');
        }

        return view('payment', compact('rentalRequest'));
    }

    // Proses pembayaran
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $rentalRequest = RentalRequest::findOrFail($id);

        // Pastikan status adalah payment_pending
        if ($rentalRequest->status !== 'payment_pending') {
            return redirect()->route('landing')->with('error', 'Status pembayaran tidak valid.');
        }

        // Simpan bukti pembayaran
        $imagePath = $request->file('payment_proof')->store('payment-proofs', 'public');

        // Buat record pembayaran
        Payment::create([
            'rental_request_id' => $id,
            'amount' => $rentalRequest->total_cost,
            'payment_proof' => $imagePath,
            'status' => 'pending',
        ]);

        // Update status permintaan sewa menjadi sudah upload bukti pembayaran
        $rentalRequest->status = 'payment_verified';
        $rentalRequest->save();

        // Kirim notifikasi ke admin
        Mail::to(env('ADMIN_EMAIL', 'admin@example.com'))->send(new PaymentReceived($rentalRequest));

        // Kirim notifikasi ke pemohon
        Mail::to($rentalRequest->email)->send(new PaymentReceived($rentalRequest));

        return redirect()->route('landing')->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    // Verifikasi pembayaran (admin)
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        $payment = Payment::with('rentalRequest')->findOrFail($id);
        $payment->status = 'verified';
        $payment->keterangan = $request->input('keterangan');
        $payment->verified_at = now();
        $payment->save();

        $rentalRequest = $payment->rentalRequest;
        $rentalRequest->status = 'payment_verified';
        $rentalRequest->save();

        // Kirim notifikasi ke kepala dinas
        // Notification::send($headOfDepartment, new PaymentVerifiedNotification($rentalRequest));

        return back()->with('success', 'Pembayaran telah diverifikasi. Notifikasi telah dikirim ke kepala dinas.');
    }

    // Contoh method baru untuk mengubah status menjadi processing
    public function processingPayment($id)
    {
        $payment = Payment::with('rentalRequest')->findOrFail($id);
        $payment->status = 'processing';
        $payment->save();

        $rentalRequest = $payment->rentalRequest;
        $rentalRequest->status = 'payment_processing';
        $rentalRequest->save();

        return back()->with('success', 'Status pembayaran diubah menjadi Sedang Diproses.');
    }

    // Tolak pembayaran (admin)
    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        $payment = Payment::with('rentalRequest')->findOrFail($id);
        $payment->status = 'rejected';
        $payment->keterangan = $request->input('keterangan');
        $payment->save();

        $rentalRequest = $payment->rentalRequest;
        $rentalRequest->status = 'payment_pending'; // Kembalikan ke status menunggu pembayaran
        $rentalRequest->save();

        // Kirim notifikasi ke user bahwa pembayaran ditolak
        // Mail::to($rentalRequest->email)->send(new PaymentRejected($rentalRequest, $payment));

        return back()->with('success', 'Pembayaran ditolak. Notifikasi telah dikirim ke pemohon.');
    }

    // Menampilkan detail pembayaran
    public function show($id)
    {
        $payment = Payment::with('rentalRequest.heavyEquipment')->findOrFail($id);
        return view('admin.payment-detail', compact('payment'));
    }
}