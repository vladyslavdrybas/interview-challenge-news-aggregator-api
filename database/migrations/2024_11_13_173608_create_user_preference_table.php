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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pivot tables for many-to-many relationships

        Schema::create('user_preference_news_category', function (Blueprint $table) {
            $table->foreignId('user_preference_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_category_id')->constrained()->onDelete('cascade');
            $table->primary(['user_preference_id', 'news_category_id']);

        });

        Schema::create('user_preference_news_author', function (Blueprint $table) {
            $table->foreignId('user_preference_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_author_id')->constrained()->onDelete('cascade');
            $table->primary(['user_preference_id', 'news_author_id']);
        });

        Schema::create('user_preference_news_source', function (Blueprint $table) {
            $table->foreignId('user_preference_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_source_id')->constrained()->onDelete('cascade');
            $table->primary(['user_preference_id', 'news_source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('user_preference_news_category');
        Schema::dropIfExists('user_preference_news_author');
        Schema::dropIfExists('user_preference_news_source');
    }
};
