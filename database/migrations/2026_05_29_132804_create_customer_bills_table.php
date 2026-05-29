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
        Schema::create('customer_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('month'); // format: 2026-05
            $table->decimal('total_liters', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->integer('total_days')->default(0);
            $table->enum('status', ['Unpaid', 'Paid'])->default('Unpaid');
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['customer_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_bills');
    }
};
