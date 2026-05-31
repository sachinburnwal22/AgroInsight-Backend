<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Community Invites Table
        Schema::create('community_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });

        // 2. Market Sessions Table
        Schema::create('market_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('room_id')->unique();
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('guest_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, active, ended
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 3. Market Messages Table
        Schema::create('market_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('market_sessions')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_messages');
        Schema::dropIfExists('market_sessions');
        Schema::dropIfExists('community_invites');
    }
};
