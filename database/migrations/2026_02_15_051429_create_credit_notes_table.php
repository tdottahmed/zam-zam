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
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('credit_note_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Customer
            
            // Financials
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_adjustment', 10, 2)->default(0);
            $table->decimal('discount_adjustment', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            
            // Meta
            $table->string('credit_type'); // adjust_against_invoice, wallet_credit, etc.
            $table->string('status')->default('draft'); // draft, pending, approved, rejected, etc.
            $table->text('reason')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Approval
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->constrained('users'); // Who created it (admin or customer)

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};
