<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount_total',
        'tax_total',
        'shipping_amount',
        'freight_charge',
        'advance_amount',
        'total',
        'status',
        'notes',
        'pdf_path',
        'ci',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Outstanding amount after the advance payment.
     * Total already includes freight and global discount, so the advance is
     * simply deducted from it. A negative value means the customer overpaid.
     */
    public function getBalanceDueAttribute(): float
    {
        return round((float) $this->total - (float) $this->advance_amount, 2);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
