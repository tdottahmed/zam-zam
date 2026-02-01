<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'label',
        'value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
