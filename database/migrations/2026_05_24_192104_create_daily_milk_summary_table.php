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
        Schema::create('daily_milk_summary', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->decimal('farm_use', 8, 2)->default(0);
            $table->decimal('samples', 8, 2)->default(0);
            $table->decimal('waste', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_milk_summary');
    }
};
