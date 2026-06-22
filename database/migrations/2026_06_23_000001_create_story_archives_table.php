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
        Schema::create('story_archives', function (Blueprint $table) {
            $table->id();
            $table->string('genre'); // 'horror', 'adventure', etc
            $table->string('location'); // 'gunung', 'hutan', etc
            $table->longText('node_content'); // The story text
            $table->json('choices_json'); // Available choices
            $table->string('image_url')->nullable(); // Associated image
            $table->integer('usage_count')->default(0); // Track how many times used
            $table->timestamps();
            
            // Index untuk query random dengan genre & location
            $table->index(['genre', 'location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_archives');
    }
};
