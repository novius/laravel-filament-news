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
        Schema::create('news_posts', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug');
            $table->translatable();
            $table->boolean('featured')->default(false);
            $table->longText('intro')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('card_image')->nullable();

            $table->publishable();

            $table->string('preview_token')->nullable();

            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();

            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();

            $table->json('extras')->nullable();

            $table->unique(['slug', 'locale']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_posts');
    }
};
