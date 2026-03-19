<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildsTable extends Migration
{
    public function up()
    {
        Schema::create('builds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            
            // Personaje obligatorio vinculado a items por string ID ("hacha-purpura")
            $table->string('character_id');
            $table->foreign('character_id')->references('id')->on('items')->cascadeOnDelete();
            
            // Armas
            $table->string('weapon_1_id');
            $table->foreign('weapon_1_id')->references('id')->on('items')->cascadeOnDelete();
            $table->string('weapon_2_id');
            $table->foreign('weapon_2_id')->references('id')->on('items')->cascadeOnDelete();
            $table->string('weapon_3_id')->nullable();
            $table->string('weapon_4_id')->nullable();
            
            // Tomos
            $table->string('tome_1_id');
            $table->foreign('tome_1_id')->references('id')->on('items')->cascadeOnDelete();
            $table->string('tome_2_id');
            $table->foreign('tome_2_id')->references('id')->on('items')->cascadeOnDelete();
            $table->string('tome_3_id')->nullable();
            $table->string('tome_4_id')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('builds');
    }
}
