<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tax;
use App\Models\SystemSetting;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = Tax::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();
        $profitMargin = SystemSetting::where('group', 'profit_margin')
            ->where('key', 'default_profit_margin')
            ->value('value') ?? 10.00; // Default to 10% if not found

        // Create 50 products with dynamic pricing logic
        Product::factory(50)->make()->each(function ($product) use ($taxes, $units, $categories, $brands, $profitMargin) {
            
            // Assign a random tax if available
            $tax = $taxes->count() > 0 ? $taxes->random() : null;
            $product->tax_id = $tax ? $tax->id : null;

            // Assign random Category and Brand
            $product->category_id = $categories->count() > 0 ? $categories->random()->id : null;
            $product->brand_id = $brands->count() > 0 ? $brands->random()->id : null;

            // Assign a random unit
            $unit = $units->count() > 0 ? $units->random() : null;
            $product->unit_id = $unit ? $unit->id : null;
            $product->unit_value = rand(1, 10) * 100; // e.g. 100, 200... 1000

            // Buying Price (Cost)
            $buyingPrice = rand(10, 500); 
            $product->buying_price = $buyingPrice;
            $product->box_price = $buyingPrice; 

            // quantity and alert quantity
            $product->quantity = rand(20, 100);
            $product->alert_quantity = rand(1, 10);

            // Calculate Selling Price with Profit Margin
            $marginAmount = $buyingPrice * ($profitMargin / 100);
            $baseSellingPrice = $buyingPrice + $marginAmount;
            $product->unit_price = number_format($baseSellingPrice, 2, '.', '');
            
            $product->save();
        });
    }
}
