<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_name',
        'supplier_contact',
        'order_date',
        'expected_delivery_date',
        'status',
        'soap_receipt_number',
        'total_amount',
        'currency',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relasi ke item-item dalam Purchase Order
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProcurementItem::class);
    }
}
