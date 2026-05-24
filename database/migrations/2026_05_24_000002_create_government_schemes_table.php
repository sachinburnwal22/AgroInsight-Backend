<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('government_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('eligibility');
            $table->text('benefits');
            $table->string('apply_link')->nullable();
            $table->string('state')->default('All India');
            $table->string('category');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_schemes');
    }
};
