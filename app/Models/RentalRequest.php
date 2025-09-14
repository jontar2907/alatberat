<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'heavy_equipment_id',
        'full_name',
        'nik',
        'address',
        'phone_number',
        'email',
        'work_location',
        'work_purpose',
        'jenis_sewa',
        'jumlah_hari',
        'start_date',
        'end_date',
        'transportasi',
        'transportation_cost',
        'administration_fee',
        'status',
        'total_cost',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'administration_fee' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function heavyEquipment()
    {
        return $this->belongsTo(HeavyEquipment::class);
    }
}
