<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class UpdateProductPriceFromBuyingPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-price-from-buying
                            {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set unit_price from buying_price and zero out buying_price for products where unit_price is 0';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $products = Product::where('unit_price', 0)
            ->orWhereNull('unit_price')
            ->get();

        $count = $products->count();

        if ($count === 0) {
            $this->info('No products found with unit_price 0 (or null). Nothing to update.');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('Dry run – no changes will be saved.');
            $this->newLine();
        }

        $updated = 0;
        $skipped = 0;

        foreach ($products as $product) {
            $buyingPrice = $product->buying_price ?? 0;
            $buyingPrice = (float) $buyingPrice;

            if ($buyingPrice <= 0) {
                $this->line("  Skip: <comment>{$product->name}</comment> (ID: {$product->id}) – buying_price is {$buyingPrice}, nothing to copy.");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  Would update: <comment>{$product->name}</comment> (ID: {$product->id}) – unit_price 0 → {$buyingPrice}, buying_price {$buyingPrice} → 0");
                $updated++;
                continue;
            }

            $product->update([
                'unit_price'   => $buyingPrice,
                'buying_price' => 0,
            ]);

            $this->line("  Updated: <comment>{$product->name}</comment> (ID: {$product->id}) – unit_price = {$buyingPrice}, buying_price = 0");
            $updated++;
        }

        $this->newLine();
        if ($dryRun) {
            $this->info("Dry run complete. Would update {$updated} product(s), skipped {$skipped}.");
        } else {
            $this->info("Done. Updated {$updated} product(s), skipped {$skipped}. Rest of product data was left unchanged.");
        }

        return self::SUCCESS;
    }
}
