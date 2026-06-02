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
    Schema::create('user_histories', function (Blueprint $table) {
        $table->id();
        $table->string('user_name'); // Nama dari form biodata [cite: 40]
        $table->string('selected_genre'); // Horror atau Petualangan [cite: 48]
        $table->string('character_visual'); // Path gambar karakter
        $table->longText('accumulated_story'); // Menampung semua teks untuk PDF [cite: 27, 28]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_histories');
    }
};
