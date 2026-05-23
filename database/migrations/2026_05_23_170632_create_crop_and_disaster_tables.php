<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Users Table Schema
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('current_region')->nullable();
        });

        // 2. Weather Alerts Table
        Schema::create('weather_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('alert_type');
            $table->string('severity'); // 'severe', 'moderate', 'info'
            $table->text('message');
            $table->timestamps();
        });

        // 3. Crop Recommendations Table
        Schema::create('crop_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('crop_name');
            $table->text('reason');
            $table->timestamps();
        });

        // 4. Recommended Products Table
        Schema::create('recommended_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->foreignId('shop_id')->nullable()->constrained('shops')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommended_products');
        Schema::dropIfExists('crop_recommendations');
        Schema::dropIfExists('weather_alerts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'current_region']);
        });
    }
};
