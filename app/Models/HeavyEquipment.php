<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeavyEquipment extends Model
{
    use HasFactory;

    // Pastikan tabel konsisten dengan migration
    protected $table = 'heavy_equipments'; // ✅ sesuaikan nama tabel

    protected $fillable = [
        'name',
        'description',
        'price',
        'jenis_sewa',
        'image',
        'availability',
        'available_dates',
        'status'
    ];

    protected $casts = [
        'availability' => 'boolean',
        'available_dates' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Relasi dengan RentalRequest
     */
    public function rentalRequests()
    {
        return $this->hasMany(\App\Models\RentalRequest::class);
    }

    /**
     * Cek ketersediaan pada tanggal tertentu
     */
    public function isAvailableOn($date)
    {
        if (!$this->availability) {
            return false;
        }

        if ($this->available_dates && is_array($this->available_dates)) {
            return in_array($date, $this->available_dates);
        }

        return true;
    }
}
