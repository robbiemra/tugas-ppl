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
        Schema::table('user_histories', function (Blueprint $table) {
            // Mengubah tipe data menjadi longText
            $table->longText('character_visual')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_histories', function (Blueprint $table) {
            $table->text('character_visual')->nullable()->change();
        });
    }
};