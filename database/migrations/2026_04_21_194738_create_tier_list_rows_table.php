<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTierListRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tier_list_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tier_list_id');
            $table->string('item_id'); // Assuming items.id is a string since keyType='string' in Item.php
            $table->string('rank'); // 'S', 'A', 'B', 'C'
            $table->timestamps();

            $table->foreign('tier_list_id')->references('id')->on('tier_lists')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tier_list_rows');
    }
}
