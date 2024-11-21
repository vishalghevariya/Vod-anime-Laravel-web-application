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
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mal_id')->unique();
            $table->string('anime_slug');
            $table->string('url');
            $table->boolean('approved')->default(false);
            $table->string('title');
            $table->string('title_english')->nullable();
            $table->string('title_japanese')->nullable();
            $table->string('trailer_youtube_id')->nullable();
            $table->string('trailer_url')->nullable();
            $table->string('trailer_embed_url')->nullable();
            $table->string('type')->nullable();
            $table->string('source')->nullable();
            $table->integer('episodes')->nullable();
            $table->string('status')->nullable();
            $table->boolean('airing')->default(false);
            $table->string('duration')->nullable();
            $table->string('rating')->nullable();
            $table->float('score', 4, 2)->nullable();
            $table->integer('scored_by')->nullable();
            $table->integer('rank')->nullable();
            $table->integer('popularity')->nullable();
            $table->integer('members')->nullable();
            $table->integer('favorites')->nullable();
            $table->text('synopsis')->nullable();
            $table->text('background')->nullable();
            $table->string('season')->nullable();
            $table->integer('year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
