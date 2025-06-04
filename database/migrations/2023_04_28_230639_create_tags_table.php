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
        Schema::create('filament_news_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->translatable();

            $table->unique(['slug', 'locale']);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('filament_news_post_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('news_post_id');
            $table->unsignedBigInteger('news_tag_id');

            $table->foreign('news_post_id')
                ->references('id')
                ->on('filament_news_posts')
                ->cascadeOnDelete();

            $table->foreign('news_tag_id')
                ->references('id')
                ->on('filament_news_tags')
                ->cascadeOnDelete();

            $table->primary(['news_post_id', 'news_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filament_news_post_tag');
        Schema::dropIfExists('filament_news_tags');
    }
};
