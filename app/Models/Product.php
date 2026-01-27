<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'weight',
        'pcs_in_ctn',
        'box_price',
        'unit_price',
        'tax',
        'buying_price',
        'notes',
    ];
}
