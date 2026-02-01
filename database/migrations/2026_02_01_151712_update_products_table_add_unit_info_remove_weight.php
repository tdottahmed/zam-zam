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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->decimal('unit_value', 10, 2)->nullable()->after('name');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete()->after('unit_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['unit_id', 'unit_value']);
            $table->string('weight')->nullable();
        });
    }
};
