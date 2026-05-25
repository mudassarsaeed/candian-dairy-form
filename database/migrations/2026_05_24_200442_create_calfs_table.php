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
       Schema::create('calfs', function (Blueprint $table) {
            $table->id();
            $table->string('tag_number');
            $table->date('calf_date')->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Female');
            $table->date('expected_insemination_date')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calfs');
    }
};
