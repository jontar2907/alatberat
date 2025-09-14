<?php

namespace App\Mail;

use App\Models\RentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalApproved extends Mailable
{
    use Queueable, SerializesModels;

    public RentalRequest $rentalRequest;

    public function __construct(RentalRequest $rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function build()
    {
        return $this->subject('Pengajuan Sewa Disetujui — Lanjutkan Pembayaran')
                    ->view('emails.rental-approved') // ganti dengan path blade Anda
                    ->with([
                        'rentalRequest' => $this->rentalRequest,
                    ]);
    }
}
