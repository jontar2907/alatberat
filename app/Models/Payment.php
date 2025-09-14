<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_request_id',
        'amount',
        'payment_proof',
        'status',
        'verified_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime'
    ];

    // Relasi dengan rental request
    public function rentalRequest()
    {
        return $this->belongsTo(RentalRequest::class);
    }

    // Status options
    public static function statusOptions()
    {
        return [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak'
        ];
    }

    // Get status text
    public function getStatusTextAttribute()
    {
        $statuses = self::statusOptions();
        return $statuses[$this->status] ?? $this->status;
    }
}