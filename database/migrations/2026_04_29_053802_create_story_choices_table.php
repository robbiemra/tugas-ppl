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
    Schema::create('story_choices', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel story_nodes
        $table->foreignId('story_node_id')->constrained('story_nodes')->onDelete('cascade');
        $table->string('choice_text'); 
        $table->unsignedBigInteger('next_node_id'); 
        $table->foreign('next_node_id')->references('id')->on('story_nodes');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_choices');
    }
};
