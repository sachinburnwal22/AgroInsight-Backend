<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('government_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type'); // 'MSP', 'Subsidy', 'Disaster', 'Deadline', 'Policy'
            $table->string('state')->default('All India');
            $table->string('severity')->default('info'); // 'info', 'moderate', 'severe'
            $table->date('deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_alerts');
    }
};
