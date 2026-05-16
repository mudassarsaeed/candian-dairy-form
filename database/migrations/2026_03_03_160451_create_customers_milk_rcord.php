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
        Schema::create('customers_milk_records', function (Blueprint $table) {
        $table->id();
        $table->string('customer_id');
        $table->datetime('date');
        $table->string('day_liter');
        $table->integer('price_day_liter')->nullable();
        $table->tinyInteger('milk_delivered')->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_milk_records');
    }
};
