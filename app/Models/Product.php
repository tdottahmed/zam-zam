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
        'slug',
        'category_id',
        'brand_id',
        'unit_value',
        'unit_id',
        'pcs_in_ctn',
        'box_price',
        'unit_price',
        'tax_id',
        'buying_price',
        'quantity',
        'alert_quantity',
        'notes',
        'image',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'pcs_in_ctn' => 'integer',
        'box_price' => 'float',
        'unit_price' => 'float',
        'buying_price' => 'float',
        'quantity' => 'integer',
        'alert_quantity' => 'integer',
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'unit_id' => 'integer',
        'tax_id' => 'integer',
    ];

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
