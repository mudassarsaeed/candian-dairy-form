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
        if (!Schema::hasTable('animals')) {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();

            $table->string('tag_id');

            $table->enum('animal_type', ['Cow', 'Heifer', 'Calf'])
                  ->default('Cow');

            $table->enum('gender', ['Male', 'Female'])
                  ->default('Female');

            $table->string('timer')->nullable();

            $table->date('insemination_date')->nullable();

            $table->string('semen_type')->nullable();

            $table->date('exp_insemination_date')->nullable();

            $table->string('confirmation_date')->nullable();

            $table->enum('status', [
                'Milking',
                'Pregnant',
                'Dry',
                'Sold',
                'Fresh'
            ])->default('Milking');

            $table->date('calf_date')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
          });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
