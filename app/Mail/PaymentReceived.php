<?php

namespace App\Mail;

use App\Models\RentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived extends Mailable
{
    use Queueable, SerializesModels;

    public RentalRequest $rentalRequest;

    public function __construct(RentalRequest $rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function build()
    {
        return $this->subject('Bukti Pembayaran Baru Diterima')
                    ->view('emails.payment-received')
                    ->with([
                        'rentalRequest' => $this->rentalRequest,
                    ]);
    }
}
