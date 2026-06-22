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
            $table->enum('generation_mode', ['realtime', 'archive'])->default('realtime')->after('story_step');
            $table->integer('api_response_time')->nullable()->comment('Response time in milliseconds')->after('generation_mode');
            $table->enum('fallback_reason', ['timeout', 'token_error', 'network_error', 'api_error'])->nullable()->after('api_response_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_histories', function (Blueprint $table) {
            $table->dropColumn(['generation_mode', 'api_response_time', 'fallback_reason']);
        });
    }
};
