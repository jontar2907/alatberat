<?php

namespace App\Mail;

use App\Models\RentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public RentalRequest $rentalRequest;

    public function __construct(RentalRequest $rentalRequest)
    {
        $this->rentalRequest = $rentalRequest;
    }

    public function build()
    {
        return $this->subject('Tagihan Pembayaran Sewa Alat Berat')
                    ->view('emails.payment-invoice')
                    ->with([
                        'rentalRequest' => $this->rentalRequest,
                    ]);
    }
}
