<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CreditNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'credit_note_number',
        'user_id',
        'subtotal',
        'tax_amount',
        'shipping_adjustment',
        'discount_adjustment',
        'grand_total',
        'credit_type',
        'status',
        'reason',
        'admin_notes',
        'approved_by',
        'approved_at',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->credit_note_number)) {
                $model->credit_note_number = 'CN-' . strtoupper(Str::random(10)); // Temporary, Service should handle this
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CreditNoteItem::class);
    }
}
