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
            unset($product->weight); // Remove weight property if factory generates it
            $taxRate = $tax ? $tax->value : 0;

            // Generate prices
            // Buying Price (Cost)
            $buyingPrice = rand(10, 500); 
            $product->buying_price = $buyingPrice;
            $product->box_price = $buyingPrice; // Assuming box price is same as buying price for now, or could be distinct

            // Calculate Selling Price with Profit Margin
            // Selling Price = Buying Price + Profit
            // Profit = Buying Price * (Margin / 100)
            $marginAmount = $buyingPrice * ($profitMargin / 100);
            $baseSellingPrice = $buyingPrice + $marginAmount;

            // Unit Price (Final Selling Price)
            // If we want to include tax in the display price, formula depends on business logic. 
            // Usually Unit Price is the shelf price. If tax is exclusive, it's Base Selling Price.
            // If inclusive? Let's assume Unit Price is the base selling price.
            $product->unit_price = number_format($baseSellingPrice, 2, '.', '');
            
            $product->save();
        });
    }
}
