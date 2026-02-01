<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'tax_id')) {
            Schema::table('products', function (Blueprint $table) {
                // Determine position
                $afterColumn = Schema::hasColumn('products', 'unit_price') ? 'unit_price' : 'id';
                
                $table->foreignId('tax_id')->nullable()->constrained('taxes')->nullOnDelete()->after($afterColumn);
            });
        }
        
        // Also remove 'tax' column if it still exists (legacy cleanup)
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'tax')) {
             Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('tax');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed as this is a fix migration
    }
};
