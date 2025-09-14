<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_request_id',
        'operator_name',
        'assignment_letter',
        'status'
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
            'pending' => 'Menunggu',
            'in_progress' => 'Dalam Pengerjaan',
            'completed' => 'Selesai'
        ];
    }

    // Get status text
    public function getStatusTextAttribute()
    {
        $statuses = self::statusOptions();
        return $statuses[$this->status] ?? $this->status;
    }
}