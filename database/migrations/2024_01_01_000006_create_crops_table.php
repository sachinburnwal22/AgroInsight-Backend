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
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('water_requirement');
            $table->enum('season', ['kharif', 'rabi', 'zaid', 'year-round', 'monsoon', 'winter', 'summer'])->nullable();
            $table->text('why_grown')->nullable();
            $table->string('ideal_soil')->nullable();
            $table->string('market_demand')->nullable();
            $table->string('government_support')->nullable();
            $table->string('emoji')->nullable();
            $table->string('expected_yield')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
