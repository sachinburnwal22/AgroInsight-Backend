<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('summary');
            $table->text('ai_summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->string('source');
            $table->string('category');
            $table->dateTime('published_at');
            $table->string('state')->nullable();
            $table->string('crop')->nullable();
            $table->boolean('is_trending')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
