<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique()->comment('ID de Steam o fuente para evitar duplicados');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('url')->nullable();
            $table->string('type')->default('news')->comment('patch, event, news');
            $table->string('source')->default('steam');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('updates');
    }
}
