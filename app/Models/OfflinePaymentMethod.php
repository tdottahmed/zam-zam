<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflinePaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'required_fields',
        'is_active',
    ];

    protected $casts = [
        'required_fields' => 'array',
        'is_active' => 'boolean',
    ];
}
