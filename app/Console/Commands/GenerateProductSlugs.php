<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Str;

class GenerateProductSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for products that do not have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::whereNull('slug')->orWhere('slug', '')->get();

        $this->info("Found {$products->count()} products without slugs.");

        foreach ($products as $product) {
            $slug = Str::slug($product->name);
            
            // Ensure slug is not empty if name is not slug-able (e.g. only symbols)
            if (empty($slug)) {
                $slug = 'product-' . $product->id;
            }

            $originalSlug = $slug;
            $count = 1;

            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $product->slug = $slug;
            $product->save();

            $this->info("Generated slug for '{$product->name}': {$slug}");
        }

        $this->info('All slugs generated successfully.');
    }
}
