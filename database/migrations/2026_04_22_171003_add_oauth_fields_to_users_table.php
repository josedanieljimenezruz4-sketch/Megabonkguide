<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOauthFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('discord_id')->nullable()->unique()->after('google_id');
            $table->string('avatar')->nullable()->after('discord_id');
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'discord_id', 'avatar']);
            // Nota: revertir password a no nullable podría fallar si hay nulos, no se hace revert.
        });
    }
}
