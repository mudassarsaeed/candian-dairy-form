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
        Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->string('cat_id');
        $table->date('order_date');
        $table->date('arrival_date');
        $table->date('ending_date');
        $table->string('receipt')->nullable();
        $table->string('unit');
        $table->string('number_bags_bails')->nullable();
        $table->decimal('unit_price', 10, 2);
        $table->decimal('total', 10, 2);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
