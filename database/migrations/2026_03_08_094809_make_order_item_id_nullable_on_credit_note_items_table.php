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
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->foreignId('order_item_id')->nullable()->change();
            $table->integer('ordered_quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->foreignId('order_item_id')->nullable(false)->change();
            $table->integer('ordered_quantity')->nullable(false)->change();
        });
    }
};
