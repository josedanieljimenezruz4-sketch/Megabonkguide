<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->string('id')->primary(); // Mismo ID de HTML "hacha-purpura"
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('requirement')->nullable();
            $table->string('type'); // 'arma', 'tomo', 'personaje', 'item'
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
