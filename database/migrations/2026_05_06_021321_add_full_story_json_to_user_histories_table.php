<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullStoryJsonToUserHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('user_histories', function (Blueprint $table) {
            $table->longText('full_story_json')->nullable()->after('accumulated_story');
            $table->string('selected_location')->nullable()->after('selected_genre');
            $table->integer('user_age')->nullable()->after('user_name');
        });
    }
    
    public function down()
    {
        Schema::table('user_histories', function (Blueprint $table) {
            $table->dropColumn(['full_story_json', 'selected_location', 'user_age']);
        });
    }
}