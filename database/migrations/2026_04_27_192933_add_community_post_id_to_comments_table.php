<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommunityPostIdToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('community_post_id')->nullable()->constrained('community_posts')->onDelete('cascade');
            $table->unsignedBigInteger('tier_list_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['community_post_id']);
            $table->dropColumn('community_post_id');
            $table->unsignedBigInteger('tier_list_id')->nullable(false)->change();
        });
    }
}
